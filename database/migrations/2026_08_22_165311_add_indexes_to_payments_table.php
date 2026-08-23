<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Zapobieżenie wielokrotnemu zapisaniu tej samej płatności
        // przy na przykład ponownym żądaniu
        Schema::table('payments', function (Blueprint $table) {
            $table->unique('idempotency_key');
            $table->index('stripe_payment_intent_id');
            $table->index(['reservationID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['idempotency_key']);
            $table->dropIndex(['stripe_payment_intent_id']);
            $table->dropIndex(['reservationID']);
        });
    }
};
