<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = "reservation";

    public function product(){
        return $this->belongsTo(Product::class,'productId','id');
    }
}
