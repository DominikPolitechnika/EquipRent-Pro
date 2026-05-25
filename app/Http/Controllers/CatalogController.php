<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
  public function index()
{
    $categories = Category::query()
    ->has('products')
    ->withCount('products')
    ->orderBy('name')
    ->get();

    $query = Product::with('category');

    $products = $query->paginate(12);

    return view('pages.catalog', compact('products','categories'));  // ← compact('products','categories') jest kluczowe
}
}