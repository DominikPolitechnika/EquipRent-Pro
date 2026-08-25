<?php

namespace App\Console\Commands;

use App\Models\Alert;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateRentalNotifications extends Command
{
    protected $signature = 'app:generate-rental-notifications';

    protected $description = 'Generuje powiadomienia dla użytkowników, których wynajem zaczyna się lub kończy jutro';

    private array $excludedStatuses = [
        'cancelled',
        'canceled',
        'anulowana',
        'completed',
        'finished',
        'zakończona',
        'returned',
        'zwrócona',
    ];

    public function handle(): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $created = 0;
        $created += $this->generateForReservations('startDate', $tomorrow, 'rental_starting', 'info',
            fn (string $productTitle) => "Twój wynajem \"{$productTitle}\" zaczyna się jutro.");

        $created += $this->generateForReservations('endDate', $tomorrow, 'rental_ending', 'warning',
            fn (string $productTitle) => "Twój wynajem \"{$productTitle}\" kończy się jutro.");

        $this->info("Wygenerowano {$created} powiadomień.");

        return self::SUCCESS;
    }

    private function generateForReservations(string $dateColumn, string $tomorrow, string $type, string $severity, callable $describe): int
    {
        $reservations = DB::table('reservation')
            ->join('products', 'reservation.productId', '=', 'products.id')
            ->whereDate("reservation.{$dateColumn}", $tomorrow)
            ->where('reservation.isDeleted', false)
            ->whereNotIn('reservation.statusOfReservation', $this->excludedStatuses)
            ->select('reservation.id', 'reservation.userId', 'products.title as productTitle')
            ->get();

        $created = 0;

        foreach ($reservations as $reservation) {
            $alreadyNotified = Alert::where('reservationId', $reservation->id)
                ->where('type', $type)
                ->whereDate('createdAt', Carbon::today())
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            Alert::create([
                'userId' => $reservation->userId,
                'reservationId' => $reservation->id,
                'description' => $describe($reservation->productTitle),
                'severity' => $severity,
                'type' => $type,
                'state' => false,
            ]);

            $created++;
        }

        return $created;
    }
}
