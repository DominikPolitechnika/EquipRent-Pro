<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    protected array $refundRoles = [0]; //0 - admin

    public function refund(User $user, Payment $payment): bool
    {
        if (in_array($user->role, $this->refundRoles, true)) {
            return true;
        }

        return false;
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->id === $payment->userId || in_array($user->role, $this->refundRoles, true);
    }
}
