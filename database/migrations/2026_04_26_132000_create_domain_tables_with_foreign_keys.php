<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('roleName');
        });
        DB::table('roles')->insert([
            'id' => 0,
            'roleName' => 'Undefined',
        ]);

        Schema::create('equipementCategory', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->foreignId('categoryId')->constrained('equipementCategory');
            $table->string('serialNumber');
            $table->boolean('isAvaible')->default(true);
            $table->integer('oneDayPrice');
            $table->integer('totalIncome')->default(0);
            $table->timestamps();
            $table->boolean('isDeleted')->default(false);
        });

        Schema::create('reservation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users');
            $table->foreignId('productId')->constrained('products');
            $table->timestamp('startDate');
            $table->timestamp('endDate');
            $table->integer('totalPrice');
            $table->string('statusOfReservation');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable();
            $table->boolean('isDeleted')->default(false);
        });

        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productId')->constrained('products');
            $table->unsignedBigInteger('lastReservationId')->nullable();
            $table->text('description');
            $table->foreignId('userId')->constrained('users');
            $table->integer('repairCost');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable();
            $table->boolean('isDeleted')->default(false);

            $table->foreign('lastReservationId')->references('id')->on('reservation');
        });

        Schema::create('Opinions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users');
            $table->foreignId('productId')->constrained('products');
            $table->text('description');
            $table->integer('scaleValue');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable();
            $table->boolean('isDeleted')->default(false);
        });

        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users');
            $table->text('description');
            $table->text('severity');
            $table->boolean('state')->default(false);
            $table->timestamp('createdAt')->useCurrent();
            $table->boolean('isDeleted')->default(false);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users');
            $table->integer('gatewayId');
            $table->foreignId('reservationID')->constrained('reservation');
            $table->integer('totalPrice');
        });

        DB::statement('ALTER TABLE users ALTER COLUMN role TYPE BIGINT USING role::bigint');

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role']);
        });

        Schema::dropIfExists('payments');
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('Opinions');
        Schema::dropIfExists('repairs');
        Schema::dropIfExists('reservation');
        Schema::dropIfExists('products');
        Schema::dropIfExists('equipementCategory');
        Schema::dropIfExists('roles');
    }
};
