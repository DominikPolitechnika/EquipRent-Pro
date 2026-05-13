<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class KatalogController extends Controller
{
  public function index()
{
    $products = Product::with('category')->get();

    return view('katalog', compact('products'));  // ← compact('products') jest kluczowe
}
}
