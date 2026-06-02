<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'equipment_category';

    protected $fillable = [
        'name',
    ];

    public function products(){
        return $this->hasMany(Product::class,'equipment_category_id','id');
    }
}
