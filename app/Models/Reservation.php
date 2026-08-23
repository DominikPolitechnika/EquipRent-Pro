<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasOne;

class Reservation extends Model
{
    protected $table = "reservation";

    protected $fillable = [
        'userId',
        'totalPrice',
        'statusOfReservation',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'userId','id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'productId','id');
    }

    public function payment(): hasOne
    {
        return $this->hasMany(Payment::class,'reservationID');
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }
}
