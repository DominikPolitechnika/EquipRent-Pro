<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\Filter\ProductFilterRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(int $id)
    {
        $product = Product::withCount('opinions')
        ->withAvg('opinions','scaleValue')
        ->findOrfail($id);
        
        return view('product',['product' => $product]);
    }

    public function show(ProductFilterRequest $request)
    {
        $validated = $request->validated();

        $categories = Category::query()
            ->has('products')
            ->orderBy('name')
            ->get();

        $query = Product::with('equipment_category')
        ->where('is_deleted',false)
        ->where('is_available',true);

        if($search = $validated['search'] ?? null){
            $query->where(function ($q) use ($search){
                $q->where('title','like',"%{$search}%")
                ->orWhere('body','like',"%{$search}%");
            });
        }

        if($categoryIds = $validated['categories'] ?? []){
            $query->whereIn('equipment_category_id',$categoryIds);
        }

        if($priceRange = $validated['price_range'] ?? []){
            $query->where('one_day_price',"<=",$priceRange);
        }

        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        if($dateFrom && $dateTo){
            $query->whereDoesntHave('reservation', function ($q) use ($dateFrom, $dateTo){
                $q->where('startDate','<=', $dateTo)
                ->where('endDate','>=',$dateFrom);
            });
        }

        switch ($validated['sort'] ?? null) {

            case 'price_asc':
                $query->orderBy('one_day_price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('one_day_price', 'desc');
                break;

            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        $products = $query
        ->paginate(12)
        ->withQueryString();//zachowanie filtrów w paginacji

        if($request->ajax()){
            return view(
                'partials.catalog-products',
                compact('products')
            );
        }

        return view(
            'pages.catalog',
            compact('products', 'categories')
        );
    }

    public function edit(int $id) //placeholder, zostanie dokończony później
    {
        $product = Product::findOrFail($id);
    
        return view('product_edit', ['product' => $product]);
    }

    public function store(Request $request){//placeholder, zostanie dokończony później
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|text',
            'categoryId' => 'required|bigint|exists:equipment_category,id',
            'serialNumber' => 'required|string|max:255',
            'isAvaible' => 'required|boolean',
            'oneDayPrice' => 'required|integer',
            'totalIncome' =>  'required|integer',
            'isDeleted'  => 'required|boolean',
        ]);

        Product::create($validated_data);

        return redirect()->route('product.edit')
        ->with('success', 'Produkt dodany'); 
    }
}
