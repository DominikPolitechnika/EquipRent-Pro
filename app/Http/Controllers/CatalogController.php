<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CatalogController extends Controller
{
  public function index()
{
    $products = Product::with('category')->paginate(12);

    return view('pages.catalog', compact('products'));  // ← compact('products') jest kluczowe
}
}