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
            ->orderBy('name')
            ->get();

        $query = Product::with('equipment_category')
        ->where('is_deleted',false)
        ->where('is_available',true);

        if($search = $request->input('search')){
            $escapedSearch = addcslashes($search,'%_\\'); //sanityzacja
            $query->where(function ($q) use ($escapedSearch){
                $q->where('title','like',"%{$escapedSearch}%")
                ->orWhere('body','like',"%{$escapedSearch}%");
            });
        }

        if($categoryIds = $request->input('categories')){
            $query->whereIn('equipment_category_id',$categoryIds);
        }

        if($request->filled('price_range')){
            $query->where('one_day_price',"<=",$request->input('price_range'));
        }

        if($request->filled('date_to') && $request->filled('date_from')){
            $query->whereDoesntHave('reservation', function ($q) use ($request){
                $q->where('startDate','<=',$request->input('date_to'))
                ->where('endDate','>=',$request->input('date_from'));
            });
        }

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
        ->withQueryString();//zachowanie filtrów w paginacji

        if($request->ajax()){
            return view(
                'partials.catalog-products',
                compact('products')
            );
        }

        return view(
            'pages.catalog',
            compact('products', 'categories')
        );
    }
}