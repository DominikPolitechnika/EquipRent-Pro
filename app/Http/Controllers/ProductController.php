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
