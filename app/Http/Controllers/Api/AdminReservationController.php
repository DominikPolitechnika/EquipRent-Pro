<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminReservationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Reservation::query()
            ->with(['user', 'product'])
            ->where('isDeleted', false);

        if ($request->filled('status')) {
            $query->where('statusOfReservation', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'ILIKE', "%{$search}%")
                        ->orWhere('surname', 'ILIKE', "%{$search}%")
                        ->orWhere('email', 'ILIKE', "%{$search}%");
                })
                ->orWhereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('title', 'ILIKE', "%{$search}%")
                        ->orWhere('serial_number', 'ILIKE', "%{$search}%");
                });
            });
        }

        $reservations = $query
            ->orderByDesc('createdAt')
            ->get();

        return response()->json([
            'data' => $reservations->map(fn (Reservation $reservation) => $this->formatReservation($reservation)),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $reservation = Reservation::with(['user', 'product'])
            ->where('isDeleted', false)
            ->findOrFail($id);

        return response()->json([
            'data' => $this->formatReservation($reservation),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $reservation = Reservation::with(['product'])
            ->where('isDeleted', false)
            ->findOrFail($id);

        $validated = $request->validate([
            'startDate' => ['sometimes', 'date'],
            'endDate' => ['sometimes', 'date'],
            'statusOfReservation' => [
                'sometimes',
                'string',
                Rule::in([
                    'pending',
                    'confirmed',
                    'active',
                    'completed',
                    'cancelled',
                    'repair',
                ]),
            ],
        ]);

        $startDate = array_key_exists('startDate', $validated)
            ? Carbon::parse($validated['startDate'])
            : Carbon::parse($reservation->startDate);

        $endDate = array_key_exists('endDate', $validated)
            ? Carbon::parse($validated['endDate'])
            : Carbon::parse($reservation->endDate);

        if ($endDate->lt($startDate)) {
            throw ValidationException::withMessages([
                'endDate' => 'Data zakończenia nie może być wcześniejsza niż data rozpoczęcia.',
            ]);
        }

        if (array_key_exists('startDate', $validated)) {
            $reservation->startDate = $startDate;
        }

        if (array_key_exists('endDate', $validated)) {
            $reservation->endDate = $endDate;
        }

        if (array_key_exists('statusOfReservation', $validated)) {
            $reservation->statusOfReservation = $validated['statusOfReservation'];
        }

        if (array_key_exists('startDate', $validated) || array_key_exists('endDate', $validated)) {
            $days = $this->calculateRentalDaysFromDates($startDate, $endDate);
            $reservation->totalPrice = $days * $reservation->product->one_day_price;
        }

        $reservation->updatedAt = now();
        $reservation->save();

        $reservation->load(['user', 'product']);

        return response()->json([
            'message' => 'Reservation updated successfully.',
            'data' => $this->formatReservation($reservation),
        ]);
    }

    private function formatReservation(Reservation $reservation): array
    {
        $user = $reservation->user;
        $product = $reservation->product;

        return [
            'id' => $reservation->id,

            'client' => [
                'id' => $user?->id,
                'avatar' => $user?->getAvatarUrl(),
                'name' => trim(($user?->name ?? '') . ' ' . ($user?->surname ?? '')),
                'email' => $user?->email,
            ],

            'product' => [
                'id' => $product?->id,
                'title' => $product?->title,
                'serialNumber' => $product?->serial_number,
            ],

            'rentalPeriod' => [
                'startDate' => $reservation->startDate?->format('Y-m-d H:i:s'),
                'endDate' => $reservation->endDate?->format('Y-m-d H:i:s'),
                'days' => $this->calculateRentalDays($reservation),
            ],

            'totalPrice' => $reservation->totalPrice,
            'statusOfReservation' => $reservation->statusOfReservation,
            'statusLabel' => $this->getStatusLabel($reservation->statusOfReservation),
        ];
    }

    private function calculateRentalDays(Reservation $reservation): int
    {
        return $this->calculateRentalDaysFromDates(
            Carbon::parse($reservation->startDate),
            Carbon::parse($reservation->endDate)
        );
    }

    private function calculateRentalDaysFromDates(Carbon $startDate, Carbon $endDate): int
    {
        return (int) $startDate
            ->copy()
            ->startOfDay()
            ->diffInDays($endDate->copy()->startOfDay()) + 1;
    }

    private function getStatusLabel(?string $status): string
    {
        return match ($status) {
            'pending' => 'Oczekująca',
            'confirmed' => 'Zarezerwowana',
            'active' => 'Aktywna',
            'completed' => 'Oddane',
            'repair' => 'Naprawa',
            'cancelled' => 'Anulowana',
            default => $status ?? 'Nieznany',
        };
    }
}