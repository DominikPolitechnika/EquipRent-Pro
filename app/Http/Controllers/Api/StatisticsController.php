<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function reservationsCount(): JsonResponse
    {
        [$startThis, $now] = [now()->startOfMonth(), now()];
        [$startPrev, $endPrev] = [
            now()->subMonthNoOverflow()->startOfMonth(),
            now()->subMonthNoOverflow()->endOfMonth(),
        ];

        $current = Reservation::where('isDeleted', false)
            ->whereBetween('createdAt', [$startThis, $now])->count();

        $previous = Reservation::where('isDeleted', false)
            ->whereBetween('createdAt', [$startPrev, $endPrev])->count();

        return response()->json([
            'currentMonth'     => $current,
            'previousMonth'    => $previous,
            'percentageChange' => $this->percentageChange($previous, $current),
        ]);
    }

    public function monthlyRevenue(): JsonResponse
    {
        [$startThis, $now] = [now()->startOfMonth(), now()];
        [$startPrev, $endPrev] = [
            now()->subMonthNoOverflow()->startOfMonth(),
            now()->subMonthNoOverflow()->endOfMonth(),
        ];

        $current = (int) Reservation::where('isDeleted', false)
            ->whereBetween('createdAt', [$startThis, $now])->sum('totalPrice');

        $previous = (int) Reservation::where('isDeleted', false)
            ->whereBetween('createdAt', [$startPrev, $endPrev])->sum('totalPrice');

        return response()->json([
            'currentMonth'     => $current,
            'previousMonth'    => $previous,
            'percentageChange' => $this->percentageChange($previous, $current),
        ]);
    }

    public function topProducts(): JsonResponse
    {
        $startThis = now()->startOfMonth();

        $rows = Reservation::select('productId', DB::raw('COUNT(*) as rentals_count'))
            ->where('isDeleted', false)
            ->whereBetween('createdAt', [$startThis, now()])
            ->groupBy('productId')
            ->orderByDesc('rentals_count')
            ->limit(3)
            ->get();

        $products = $rows->map(function ($row) {
            $product = Product::find($row->productId);
            if (!$product) {
                return null;
            }

            return [
                'id'           => $product->id,
                'title'        => $product->title,
                'oneDayPrice'  => $product->one_day_price,
                'rentalsCount' => (int) $row->rentals_count,
                'isAvailable'  => (bool) $product->is_available,
                'thumbnailUrl' => $product->getThumbnailUrl(),
            ];
        })->filter()->values()->all();

        return response()->json(['products' => $products]);
    }

    public function weeklyIncome(): JsonResponse
    {
        $now = now();
        $sevenDaysAgo = $now->copy()->subDays(6)->startOfDay();

        $byDay = Reservation::select(
                DB::raw('DATE(createdAt) as day'),
                DB::raw('SUM(totalPrice) as total')
            )
            ->where('isDeleted', false)
            ->whereBetween('createdAt', [$sevenDaysAgo, $now])
            ->groupBy(DB::raw('DATE(createdAt)'))
            ->pluck('total', 'day');

        $days = [];
        $totalIncome = 0;

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $income = (int) ($byDay[$date] ?? 0);
            $totalIncome += $income;

            $days[] = ['date' => $date, 'income' => $income];
        }

        return response()->json([
            'totalIncome' => $totalIncome,
            'days'        => $days,
        ]);
    }

    public function latestReservations(): JsonResponse
    {
        $reservations = Reservation::with(['user', 'product.equipmentCategory'])
            ->where('isDeleted', false)
            ->orderByDesc('createdAt')
            ->limit(4)
            ->get();

        $data = $reservations->map(function (Reservation $r) {
            return [
                'name'            => $r->user?->name,
                'surname'         => $r->user?->surname,
                'avatarUrl'       => $r->user?->getAvatarUrl(),
                'productTitle'    => $r->product?->title,
                'productCategory' => $r->product?->equipmentCategory?->name,
                'startDate'       => optional($r->startDate)->format('Y-m-d'),
                'endDate'         => optional($r->endDate)->format('Y-m-d'),
                'status'          => $r->statusOfReservation,
            ];
        })->all();

        return response()->json(['reservations' => $data]);
    }

    private function percentageChange(int|float $previous, int|float $current): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
}