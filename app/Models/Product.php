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

    public function category(){ //relacja products equipementCategory
        return $this->belongsTo(Category::class,'categoryId','id');
    }
}
