<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\EquipmentCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(int $id)
    {
        $product = Product::withCount('opinions')
            ->withAvg('opinions', 'scaleValue')
            ->findOrFail($id);

        return view('product', ['product' => $product]);
    }

    public function show(\App\Http\Requests\Filter\ProductFilterRequest $request)
    {
        $validated = $request->validated();

        $categories = EquipmentCategory::query()
            ->has('products')
            ->orderBy('name')
            ->get();

        $query = Product::with('equipment_category')
            ->where('is_deleted', false)
            ->where('is_available', true);

        if ($search = $validated['search'] ?? null) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($categoryIds = $validated['categories'] ?? []) {
            $query->whereIn('equipment_category_id', $categoryIds);
        }

        if (($priceRange = $validated['price_range'] ?? null) !== null) {
            $query->where('one_day_price', '<=', $priceRange);
        }

        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        if ($dateFrom && $dateTo) {
            $query->whereDoesntHave('reservation', function ($q) use ($dateFrom, $dateTo) {
                $q->where('isDeleted', false)
                    ->whereNotIn('statusOfReservation', [
                        'cancelled', 'canceled', 'anulowana',
                        'completed', 'finished', 'zakończona',
                        'returned', 'zwrócona',
                    ])
                    ->where('startDate', '<=', $dateTo)
                    ->where('endDate', '>=', $dateFrom);
            });
        }

        switch ($validated['sort'] ?? null) {
            case 'price_asc':
                $query->orderBy('one_day_price');
                break;
            case 'price_desc':
                $query->orderByDesc('one_day_price');
                break;
            case 'name_asc':
                $query->orderBy('title');
                break;
            case 'name_desc':
                $query->orderByDesc('title');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        if ($request->ajax()) {
            return view('partials.catalog-products', compact('products'));
        }

        return view('pages.catalog', compact('products', 'categories'));
    }

    public function inventory(Request $request)
    {
        $categories = EquipmentCategory::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        if (!$request->expectsJson() && !$request->ajax()) {
            return view('list_equipment', compact('categories'));
        }

        $query = Product::with('equipment_category')
            ->where('is_deleted', false);

        if ($search = trim((string) $request->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                    ->orWhere('serial_number', 'ilike', "%{$search}%")
                    ->orWhere('body', 'ilike', "%{$search}%");
            });
        }

        if ($category = $request->query('category')) {
            $query->where('equipment_category_id', $category);
        }

        $min = $request->query('price_min');
        $max = $request->query('price_max');
        if (is_numeric($min)) {
            $query->where('one_day_price', '>=', (int) $min);
        }
        if (is_numeric($max)) {
            $query->where('one_day_price', '<=', (int) $max);
        }

        $status = $request->query('status', 'sprawny');
        if ($status === 'serwis') {
            $query->where('is_available', false);
        } elseif ($status === 'sprawny') {
            $query->where('is_available', true);
        }

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        if ($dateFrom || $dateTo) {
            $dateFrom = $dateFrom ?: $dateTo;
            $dateTo = $dateTo ?: $dateFrom;

            try {
                $from = Carbon::parse($dateFrom)->startOfDay();
                $to = Carbon::parse($dateTo)->endOfDay();
                if ($from->gt($to)) {
                    [$from, $to] = [$to, $from];
                }

                $query->whereDoesntHave('reservation', function ($q) use ($from, $to) {
                    $q->where('isDeleted', false)
                        ->whereNotIn('statusOfReservation', [
                            'cancelled', 'canceled', 'anulowana',
                            'completed', 'finished', 'zakończona',
                            'returned', 'zwrócona',
                        ])
                        ->where('startDate', '<=', $to)
                        ->where('endDate', '>=', $from);
                });
            } catch (\Throwable $e) {

            }
        }

        switch ($request->query('sort', 'name_asc')) {
            case 'name_desc':
                $query->orderByDesc('title');
                break;
            case 'price_asc':
                $query->orderBy('one_day_price');
                break;
            case 'price_desc':
                $query->orderByDesc('one_day_price');
                break;
            default:
                $query->orderBy('title');
        }

        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);
        $products = $query->paginate($perPage)->withQueryString();

        return response()->json([
            'data' => $products->getCollection()->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'sn' => $product->serial_number,
                    'category' => $product->equipment_category?->name,
                    'price' => (int) $product->one_day_price,
                    'image' => $product->getThumbnailUrl(),
                    'is_available' => (bool) $product->is_available,
                    'status' => $product->getStatus(),
                ];
            })->values(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function edit(int $id)
    {
        $product = Product::findOrFail($id);
        $categories = EquipmentCategory::orderBy('name')->get(['id', 'name']);
        $disk = Storage::disk('public');
        $directory = "images/products/{$product->id}";
        $images = collect($disk->files($directory))
            ->filter(fn ($path) => preg_match('~/\d+\.avif$~i', $path))
            ->sortBy(fn ($path) => (int) basename($path, '.avif'))
            ->map(fn ($path) => [
                'name' => basename($path),
                'url' => $disk->url($path),
            ])->values();

        return view('product_edit', compact('product', 'categories', 'images'));
    }

    public function toggleAvailability(Request $request, int $id)
    {
        $product = Product::where('is_deleted', false)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'is_available' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Niepoprawny status produktu.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $product->is_available = $request->boolean('is_available');
        $product->save();

        return response()->json([
            'message' => $product->is_available
                ? 'Produkt został oznaczony jako sprawny.'
                : 'Produkt został przeniesiony do serwisu.',
            'is_available' => (bool) $product->is_available,
            'status' => $product->getStatus(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $product = Product::where('is_deleted', false)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'equipment_category_id' => ['required', 'integer', 'exists:equipment_category,id'],
            'one_day_price' => ['required', 'integer', 'min:0'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
            'remove_photos' => ['nullable', 'array'],
            'remove_photos.*' => ['string'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $disk = Storage::disk('public');
        $directory = "images/products/{$product->id}";

        $existing = collect($disk->files($directory))
            ->filter(fn ($path) => preg_match('~/(\d+)\.avif$~i', $path))
            ->sortBy(fn ($path) => (int) basename($path, '.avif'))
            ->values();

        $removed = collect($request->input('remove_photos', []))
            ->map(fn ($name) => basename($name))
            ->filter(fn ($name) => preg_match('/^\d+\.avif$/i', $name))
            ->values();

        $existing = $existing->reject(fn ($path) => $removed->contains(basename($path)))->values();

        $newFiles = collect($request->file('photos', []))->filter(fn ($file) => $file && $file->isValid())->values();

        if ($existing->count() + $newFiles->count() < 3) {
            return back()
                ->withErrors(['photos' => 'Produkt musi posiadać co najmniej 3 zdjęcia.'])
                ->withInput();
        }

        try {
            $disk->makeDirectory($directory);

            $items = [];
            foreach ($existing as $path) {
                $items[] = [
                    'contents' => $disk->get($path),
                    'extension' => 'avif',
                ];
            }

            foreach ($disk->files($directory) as $oldPath) {
                $disk->delete($oldPath);
            }
            foreach ($newFiles as $file) {
                $items[] = [
                    'contents' => file_get_contents($file->getRealPath()),
                    'extension' => strtolower($file->getClientOriginalExtension()),
                ];
            }

            foreach ($items as $index => $item) {
                if ($index === 0) {
                    $this->saveResizedAvif($item['contents'], $disk, "{$directory}/1.avif", 1200, 1200);
                    $this->saveResizedAvif($item['contents'], $disk, "{$directory}/1_thumb.avif", 480, 240);
                    continue;
                }

                $target = ($index + 1) . '.avif';
                $this->saveResizedAvif($item['contents'], $disk, "{$directory}/{$target}", 1200, 1200);
            }
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['photos' => 'Nie udało się przetworzyć zdjęć: ' . $e->getMessage()])
                ->withInput();
        }

        $product->title = $request->string('title')->toString();
        $product->body = $request->input('body');
        $product->equipment_category_id = (int) $request->input('equipment_category_id');
        $product->one_day_price = (int) $request->input('one_day_price');
        if ($request->has('is_available')) {
            $product->is_available = $request->boolean('is_available');
        }
        $product->save();

        return redirect()
            ->route('product.edit', $product->id)
            ->with('success', 'Produkt został zaktualizowany.');
    }

    public function destroy(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $product->is_deleted = true;
        $product->is_available = false;
        $product->save();

        return response()->json([
            'message' => 'Produkt został usunięty z inwentarza.',
            'redirect' => route('equipment.list'),
        ]);
    }

    public function storeRepair(Request $request, int $id)
    {
        $product = Product::where('is_deleted', false)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'description' => ['required', 'string', 'max:5000'],
            'serviceman_name' => ['required', 'string', 'max:255'],
            'repairCost' => ['required', 'integer', 'min:0'],
            'date' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Niepoprawne dane naprawy.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $repairId = DB::table('repairs')->insertGetId([
            'productId' => $product->id,
            'lastReservationId' => null,
            'description' => $request->input('description'),
            'serviceman_name' => $request->input('serviceman_name'),
            'userId' => $request->user()->id,
            'repairCost' => (int) $request->input('repairCost'),
            'createdAt' => Carbon::parse($request->input('date'))->startOfDay(),
            'updatedAt' => now(),
            'isDeleted' => false,
        ]);

        return response()->json([
            'message' => 'Wpis naprawy został dodany.',
            'id' => $repairId,
        ], 201);
    }

    public function deleteRepair(int $id, int $repairId)
    {
        $updated = DB::table('repairs')
            ->where('id', $repairId)
            ->where('productId', $id)
            ->where('isDeleted', false)
            ->update([
                'isDeleted' => true,
                'updatedAt' => now(),
            ]);

        if (!$updated) {
            return response()->json(['message' => 'Wpis naprawy nie istnieje.'], 404);
        }

        return response()->json(['message' => 'Wpis naprawy został usunięty.']);
    }

    public function repairs(int $id)
    {
        Product::where('is_deleted', false)->findOrFail($id);

        $repairs = DB::table('repairs')
            ->where('productId', $id)
            ->where('isDeleted', false)
            ->orderByDesc('createdAt')
            ->get([
                'id', 'description', 'serviceman_name', 'repairCost',
                'createdAt', 'updatedAt', 'lastReservationId',
            ]);

        return response()->json(['data' => $repairs]);
    }

    public function reservations(int $id)
    {
        Product::findOrFail($id);

        $reservations = DB::table('reservation as r')
            ->leftJoin('users as u', 'u.id', '=', 'r.userId')
            ->where('r.productId', $id)
            ->where('r.isDeleted', false)
            ->orderByDesc('r.startDate')
            ->get([
                'r.id', 'r.productId', 'r.startDate', 'r.endDate',
                'r.totalPrice', 'r.statusOfReservation', 'r.createdAt', 'r.updatedAt',
                'r.userId',
                DB::raw("NULLIF(TRIM(CONCAT(COALESCE(u.name, ''), ' ', COALESCE(u.surname, ''))), '') as \"userName\""),
            ]);

        return response()->json(['data' => $reservations]);
    }

    private function saveResizedAvif(string $contents, $disk, string $path, int $targetWidth, int $targetHeight): void
    {
        if (!function_exists('imageavif')) {
            throw new \RuntimeException('Serwer PHP nie ma włączonej obsługi AVIF w bibliotece GD.');
        }

        $source = @imagecreatefromstring($contents);
        if (!$source) {
            throw new \RuntimeException('Nie można odczytać jednego ze zdjęć.');
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);

        $scale = min($targetWidth / $sourceWidth, $targetHeight / $sourceHeight);
        $newWidth = max(1, (int) round($sourceWidth * $scale));
        $newHeight = max(1, (int) round($sourceHeight * $scale));

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);

        $x = (int) floor(($targetWidth - $newWidth) / 2);
        $y = (int) floor(($targetHeight - $newHeight) / 2);

        imagecopyresampled(
            $canvas, $source,
            $x, $y, 0, 0,
            $newWidth, $newHeight,
            $sourceWidth, $sourceHeight
        );

        if (!@imageavif($canvas, $tmp = tempnam(sys_get_temp_dir(), 'product_'), 80)) {
            imagedestroy($source);
            imagedestroy($canvas);
            throw new \RuntimeException('Nie udało się utworzyć pliku AVIF.');
        }

        $bytes = file_get_contents($tmp);
        @unlink($tmp);
        imagedestroy($source);
        imagedestroy($canvas);

        if ($bytes === false) {
            throw new \RuntimeException('Nie udało się odczytać wygenerowanego AVIF.');
        }

        $disk->put($path, $bytes);
    }
}
