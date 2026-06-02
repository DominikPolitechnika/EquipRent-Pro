<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [ //pola ktore będą wypełniane
        'title',
        'body',
        'categoryId',
        'serialNumber',
        'isAvaible',
        'oneDayPrice',
        'totalIncome',
        'isDeleted',
    ];

    public function equipment_category(){ //relacja products equipment_category
        return $this->belongsTo(Category::class);
    }
}
