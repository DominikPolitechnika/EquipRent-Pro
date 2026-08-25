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
        Schema::table('alerts', function (Blueprint $table) {
            $table->foreignId('reservationId')->nullable()->after('userId')->constrained('reservation')->nullOnDelete();
            $table->string('type')->nullable()->after('severity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropForeign(['reservationId']);
            $table->dropColumn(['reservationId', 'type']);
        });
    }
};
