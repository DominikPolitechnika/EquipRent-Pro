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
}
