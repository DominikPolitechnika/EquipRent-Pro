<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(int $id)
    {
        $product = Product::findorFail($id);
        
        return view('product',['product' => $product]);
    }

    public function show(Request $request)
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

    public function edit(int $id)
    {
        $product = Product::findOrFail($id);
    
        return view('product_edit', ['product' => $product]);
    }

    public function store(Request $request){
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|text',
            'categoryId' => 'required|bigint|exists:equipementCategory,id',
            'serialNumber' => 'required|string|max:255',
            'isAvaible' => 'required|boolean',
            'oneDayPrice' => 'required|integer',
            'totalIncome' =>  'required|integer',
            'isDeleted'  => 'required|boolean',
        ]);

        Product::create($validated_data);

        return redirect()->route('product.edit')
        ->with('success', 'Produkt dodany'); 
    }
}
