<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Imię i nazwisko posiadacza karty (billing_details.name ze Stripe) —
 * przechowywane lokalnie, żeby dało się je pokazać na liście zapisanych
 * metod płatności bez dodatkowego zapytania do Stripe API.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('cardholder_name')->nullable()->after('last4');
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('cardholder_name');
        });
    }
};
