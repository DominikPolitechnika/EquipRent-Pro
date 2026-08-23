<?php

namespace App\Services;

use Exception;
use Throwable;

class PaymentFailedException extends Exception
{
    public function __construct(
        string $message,
        public readonly string $errorCode = 'payment_failed',
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function httpStatus(): int
    {
        return match ($this->errorCode) {
            'already_refunded', 'invalid_status' => 409,
            'stripe_error' => 502,
            default => 422,
        };
    }
}
