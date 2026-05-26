<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(int $id)
    {
        $product = Product::findorFail($id);
        
        return view('product',['product' => $product]);
    }

    public function edit(int $id)
    {
        $product = Product::findOrFail($id);
    
        return view('product_edit', ['product' => $product]);
    }
}
