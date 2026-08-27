<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasOne;

class Reservation extends Model
{
    protected $table = 'reservation';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'userId', 'productId', 'startDate', 'endDate',
        'totalPrice', 'statusOfReservation', 'isDeleted',
    ];

    protected $casts = [
        'startDate'  => 'datetime',
        'endDate'    => 'datetime',
        'isDeleted'  => 'boolean',
        'totalPrice' => 'integer',
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
