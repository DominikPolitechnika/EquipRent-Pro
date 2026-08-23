<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    
    protected $table = 'payments';

    public $timestamps = false;

    protected $fillable = [
        'userId', 
        'gatewayId', 
        'reservationID',
        'totalPrice',
        'status',
        'stripe_payment_intent_id',
        'payment_method_id',
        'payment_type',
        'idempotency_key',
        'paid_at',
        'refunded_amount',
        'stripe_refund_id',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'totalPrice' => 'integer',
        'gatewayId' => 'integer',
        'refunded_amount' => 'integer',
    ];

    // Statusy kolumny status
    public const STATUS_PENDING = 'pending';

    public const STATUS_REQUIRES_ACTION = 'requires_action';

    public const STATUS_SUCCEEDED = 'succeeded';

    public const STATUS_FAILED = 'failed';

    public const STATUS_REFUNDED = 'refunded';

    public const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';

    // Wartości kolumny payment_type
    public const TYPE_ONE_TIME = 'one_time';

    public const TYPE_OFF_SESSION = 'off_session';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'userId');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class,'reservationID');
    }

}
