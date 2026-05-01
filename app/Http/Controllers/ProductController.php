<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
class ProductController extends Controller
{
    private function getProducts(): array
    {
        return [
            1 => [
                'id'          => 1,
                'name'        => 'Rower MTB Trek Marlin 7',
                'category'    => 'Rowery MTB',
                'price'       => 120,
                'status'      => 'dostepny',
                'description' => 'Rower górski idealny na trasy XC.',
            ],
            2 => [
                'id'          => 2,
                'name'        => 'Rower szosowy Giant Contend',
                'category'    => 'Rowery szosowe',
                'price'       => 150,
                'status'      => 'dostepny',
                'description' => 'Lekki rower szosowy do długich dystansów.',
            ],
            3 => [
                'id'          => 3,
                'name'        => 'E-bike Specialized Turbo',
                'category'    => 'E-bikes',
                'price'       => 200,
                'status'      => 'wypozyczony',
                'description' => 'Elektryczny rower z zasięgiem 100 km.',
            ],
        ];
    }
 
    public function showPage(int $id)
    {
        $products = $this->getProducts();
 
        if (!isset($products[$id])) {
            abort(404, 'Produkt nie istnieje.');
        }
 
        $product = $products[$id];
 
        return view('product', compact('product'));
    }
}