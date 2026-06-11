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
        Schema::table('products', function (Blueprint $table){
            $table->dropForeign(['categoryId']);
        });

        Schema::table('products', function (Blueprint $table){

            $table->renameColumn('categoryId','equipment_category_id');
            $table->renameColumn('serialNumber','serial_number');
            $table->renameColumn('isAvaible','is_available');
            $table->renameColumn('oneDayPrice','one_day_price');
            $table->renameColumn('totalIncome','total_income');
            $table->renameColumn('isDeleted','is_deleted');
        });

        Schema::rename('equipementCategory','equipment_category');

        Schema::table('products',function (Blueprint $table) {
            $table->foreign('equipment_category_id')
            ->references('id')
            ->on('equipment_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products',function (Blueprint $table){
            $table->dropForeign(['categoryId']);
        });

        Schema::rename('equipment_category','equipementCategory');

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('equipment_category_id','categoryId');
            $table->renameColumn('serial_number','serialNumber');
            $table->renameColumn('is_available','isAvaible');
            $table->renameColumn('one_day_price','oneDayPrice');
            $table->renameColumn('total_income','totalIncome');
            $table->renameColumn('is_deleted','isDeleted');
        });

        Schema::table('products',function (Blueprint $table) {
            $table->foreign('categoryId')
            ->references('id')
            ->on('equipementCategory');
        });
    }
};