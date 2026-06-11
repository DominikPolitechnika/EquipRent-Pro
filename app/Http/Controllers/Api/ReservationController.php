<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReservationController extends Controller
{
    private array $cancelledStatuses = [
        'cancelled',
        'canceled',
        'anulowana',
    ];

    private array $completedStatuses = [
        'completed',
        'finished',
        'zakończona',
        'returned',
        'zwrócona',
    ];

    public function my(Request $request)
    {
        $userId = $request->user()->id;

        $reservations = DB::table('reservation')
            ->join('products', 'reservation.productId', '=', 'products.id')
            ->where('reservation.userId', $userId)
            ->where('reservation.isDeleted', false)
            ->select(
                'reservation.id',
                'reservation.productId',
                'products.title as productTitle',
                'products.oneDayPrice',
                'reservation.startDate',
                'reservation.endDate',
                'reservation.totalPrice',
                'reservation.statusOfReservation',
                'reservation.createdAt',
                'reservation.updatedAt'
            )
            ->orderByDesc('reservation.createdAt')
            ->get();

        return response()->json([
            'data' => $reservations,
        ]);
    }

    public function active(Request $request)
    {
        $userId = $request->user()->id;

        $reservations = DB::table('reservation')
            ->join('products', 'reservation.productId', '=', 'products.id')
            ->where('reservation.userId', $userId)
            ->where('reservation.isDeleted', false)
            ->whereNotIn('reservation.statusOfReservation', array_merge(
                $this->cancelledStatuses,
                $this->completedStatuses
            ))
            ->where('reservation.endDate', '>=', now())
            ->select(
                'reservation.id',
                'reservation.productId',
                'products.title as productTitle',
                'products.oneDayPrice',
                'reservation.startDate',
                'reservation.endDate',
                'reservation.totalPrice',
                'reservation.statusOfReservation',
                'reservation.createdAt',
                'reservation.updatedAt'
            )
            ->orderBy('reservation.startDate')
            ->get();

        return response()->json([
            'data' => $reservations,
        ]);
    }

        public function completed(Request $request)
    {
        $userId = $request->user()->id;

        $reservations = DB::table('reservation')
            ->join('products', 'reservation.productId', '=', 'products.id')
            ->where('reservation.userId', $userId)
            ->where('reservation.isDeleted', false)
            ->where(function ($query) {
                $query->whereIn('reservation.statusOfReservation', $this->completedStatuses)
                    ->orWhere('reservation.endDate', '<', now());
            })
            ->select(
                'reservation.id',
                'reservation.productId',
                'products.title as productTitle',
                'products.oneDayPrice',
                'reservation.startDate',
                'reservation.endDate',
                'reservation.totalPrice',
                'reservation.statusOfReservation',
                'reservation.createdAt',
                'reservation.updatedAt'
            )
            ->orderByDesc('reservation.endDate')
            ->get();

        return response()->json([
            'data' => $reservations,
        ]);
    }

        public function myForProduct(Request $request, int $productId)
    {
        $userId = $request->user()->id;

        $reservations = DB::table('reservation')
            ->where('userId', $userId)
            ->where('productId', $productId)
            ->where('isDeleted', false)
            ->select(
                'id',
                'productId',
                'startDate',
                'endDate',
                'totalPrice',
                'statusOfReservation',
                'createdAt',
                'updatedAt'
            )
            ->orderByDesc('createdAt')
            ->get();

        return response()->json([
            'data' => $reservations,
        ]);
    }

        public function cancel(Request $request, int $reservationId)
    {
        $userId = $request->user()->id;

        $reservation = DB::table('reservation')
            ->where('id', $reservationId)
            ->where('userId', $userId)
            ->where('isDeleted', false)
            ->first();

        if (!$reservation) {
            return response()->json([
                'message' => 'Rezerwacja nie istnieje albo nie należy do zalogowanego użytkownika.',
            ], 404);
        }

        if (in_array($reservation->statusOfReservation, $this->cancelledStatuses, true)) {
            return response()->json([
                'message' => 'Rezerwacja jest już anulowana.',
            ], 409);
        }

        if (in_array($reservation->statusOfReservation, $this->completedStatuses, true)) {
            return response()->json([
                'message' => 'Nie można anulować zakończonej rezerwacji.',
            ], 409);
        }

        DB::table('reservation')
            ->where('id', $reservationId)
            ->update([
                'statusOfReservation' => 'cancelled',
                'updatedAt' => now(),
            ]);

        return response()->json([
            'message' => 'Rezerwacja została anulowana.',
        ]);
    }

        public function store(Request $request, int $productId)
    {
        $validator = Validator::make($request->all(), [
            'startDate' => ['required', 'date', 'after_or_equal:today'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Niepoprawne dane rezerwacji.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = $request->user()->id;

        $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
        $endDate = Carbon::parse($request->input('endDate'))->endOfDay();

        try {
            $reservationId = DB::transaction(function () use ($userId, $productId, $startDate, $endDate) {
                $product = DB::table('products')
                    ->where('id', $productId)
                    ->where('isDeleted', false)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    abort(response()->json([
                        'message' => 'Produkt nie istnieje.',
                    ], 404));
                }

                if (!$product->isAvaible) {
                    abort(response()->json([
                        'message' => 'Produkt jest niedostępny.',
                    ], 409));
                }

                $overlappingReservationExists = DB::table('reservation')
                    ->where('productId', $productId)
                    ->where('isDeleted', false)
                    ->whereNotIn('statusOfReservation', array_merge(
                        $this->cancelledStatuses,
                        $this->completedStatuses
                    ))
                    ->where('startDate', '<=', $endDate)
                    ->where('endDate', '>=', $startDate)
                    ->exists();

                if ($overlappingReservationExists) {
                    abort(response()->json([
                        'message' => 'Produkt jest już zarezerwowany w podanym terminie.',
                    ], 409));
                }

                $days = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) + 1;
                $totalPrice = $days * (int) $product->oneDayPrice;

                return DB::table('reservation')->insertGetId([
                    'userId' => $userId,
                    'productId' => $productId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'totalPrice' => $totalPrice,
                    'statusOfReservation' => 'active',
                    'createdAt' => now(),
                    'updatedAt' => now(),
                    'isDeleted' => false,
                ]);
            });

            return response()->json([
                'message' => 'Rezerwacja została utworzona.',
                'reservationId' => $reservationId,
            ], 201);
        } catch (HttpResponseException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Błąd podczas tworzenia rezerwacji.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}