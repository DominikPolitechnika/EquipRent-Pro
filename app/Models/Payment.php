<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'userId', 'gatewayId', 'reservationID','totalPrice','status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'userId');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class,'reservationID');
    }

    public function isSuccessful(): bool
    {
        return $this->status == "succeeded";
    }
}
