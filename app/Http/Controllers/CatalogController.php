<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->has('products')
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $query = Product::with('equipment_category');

        switch ($request->sort) {

            case 'price_asc':
                $query->orderBy('one_day_price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('one_day_price', 'desc');
                break;

            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        $products = $query
            ->paginate(12)
            ->withQueryString();

        return view(
            'pages.catalog',
            compact('products', 'categories')
        );
    }
}