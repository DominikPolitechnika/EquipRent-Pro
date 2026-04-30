<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KatalogController extends Controller
{
  public function index()
{
    $products = [
        [
            'id'          => 1,
            'name'        => 'Rower MTB Trek Marlin 7',
            'category'    => 'Rowery MTB',
            'price'       => 120,
            'status'      => 'dostepny',
            'description' => 'Rower górski idealny na trasy XC.',
            'images'      => ['/img/bike1.jpg'],
        ],
        [
            'id'          => 2,
            'name'        => 'Rower szosowy Giant Contend',
            'category'    => 'Rowery szosowe',
            'price'       => 150,
            'status'      => 'dostepny',
            'description' => 'Lekki rower szosowy do długich dystansów.',
            'images'      => ['/img/bike2.jpg'],
        ],
    ];

    return view('katalog', compact('products'));  // ← compact('products') jest kluczowe
}
}
