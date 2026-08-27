<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    protected $table = 'equipment_category';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'equipment_category_id');
    }
}