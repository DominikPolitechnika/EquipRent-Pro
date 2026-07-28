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
            $table->string('status')->after('totalPrice');
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            DB::statement('ALTER TABLE payments ALTER COLUMN "gatewayId" TYPE integer USING "gatewayId"::integer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            DB::statement('ALTER TABLE payments ALTER COLUMN "gatewayId" TYPE integer USING "gatewayId"::integer');
            $table->dropColumn('stripe_payment_intent_id');
            $table->dropColumn('status');
        });

    }
};
