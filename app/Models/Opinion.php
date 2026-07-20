<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Opinion extends Model
{
    protected $table = 'Opinions';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'productId','id');
    }
}
