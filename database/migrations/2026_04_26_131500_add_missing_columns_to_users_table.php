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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role')->default(0)->after('id');
            $table->string('surname')->nullable()->after('name');
            $table->string('telephone_number')->nullable()->after('surname');
            $table->timestamp('lastLogin')->nullable()->after('updated_at');
            $table->string('klub')->nullable()->after('lastLogin');
            $table->text('profilDescription')->nullable()->after('klub');
            $table->boolean('isBlocked')->default(false)->after('profilDescription');
            $table->boolean('isDeleted')->default(false)->after('isBlocked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'surname',
                'telephone_number',
                'lastLogin',
                'klub',
                'profilDescription',
                'isBlocked',
                'isDeleted',
            ]);
        });
    }
};
