<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [ //pola ktore będą wypełniane
        'title',
        'body',
        'equipment_category_id',
        'serial_number',
        'is_available',
        'one_day_price',
        'total_income',
        'is_deleted',
    ];

    public function equipment_category(){ //relacja products equipment_category
        return $this->belongsTo(Category::class);
    }
}
