<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

/*
pola formularza powinny mieć opcje name odpowiednio
wyszukiwanie: search,
kategories: categories[] i pole value ustawione na id kategorii
zakres ceny: price_range
data od: date_from
data do: date_to
*/
class CatalogController extends Controller{
  public function index(Request $request){
    $categories = Category::query()
    ->has('products')
    ->withCount('products')
    ->orderBy('name')
    ->get(); //wybranie kategorii z produktami wraz liczbą produktów i sortowanie po nazwie

    $query = Product::with('equipment_category')->where('is_available',true);

    if($search = $request->input('search')){ //wyszukiwanie produktu
      $query->where('title','like',"%{$search}%");
    }

    if($categoryIds = $request->input('categories')){ //filtrowanie po kategorii
      $query->whereIn('equipment_category_id',$categoryIds);
    }

    if($request->filled('price_range')){ //filtrowanie po cenie
      $query->where('one_day_price','<=',$request->input('price_range'));
    }

    //filtrowanie po datach bedzie wykonane wkrótce
    $products = $query->paginate(12)->withQueryString(); //paginacja i zachowanie filtrow

    return view('pages.catalog', compact('products','categories'));  // ← compact('products',categories) jest kluczowe
  }
}