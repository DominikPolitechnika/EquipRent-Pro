<?php

namespace App\Services;
use App\Models\Reservation;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function findPayableReservation(int $reservationId, int $userId): Reservation
    {
        $reservation = Reservation::where('id', $reservationId)
            ->where('userId', $userId) // upewnij się, że to rezerwacja TEGO usera
            ->firstOrFail();

        if ($reservation->payment()->exists()) {
            throw ValidationException::withMessages([
                'reservationId' => 'Ta rezerwacja została już opłacona.',
            ]);
        }

        return $reservation;
    }
}
