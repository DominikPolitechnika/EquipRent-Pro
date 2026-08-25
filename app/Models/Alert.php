<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    protected $table = 'alerts';

    public $timestamps = false;

    protected $fillable = [
        'userId',
        'reservationId',
        'description',
        'severity',
        'type',
        'state',
    ];

    protected $casts = [
        'state' => 'boolean',
        'isDeleted' => 'boolean',
        'createdAt' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservationId', 'id');
    }
}
