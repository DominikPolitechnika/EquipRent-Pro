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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('payment_method_id')
                ->constrained('payment_methods')
                ->nullable();

            $table->char('payment_type',20)->default('initial');

            $table->text('idempotency_key')->nullable();

            $table->unique('idempotency_key', 'idx_payments_idempotency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique('idx_payments_idempotency');
            $table->dropColumn('idempotency_key');
            $table->dropColumn('payment_type');
            $table->dropColumn('payment_method_id');
        });
    }
};
