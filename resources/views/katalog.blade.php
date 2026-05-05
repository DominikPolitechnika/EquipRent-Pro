@extends('layouts.app')

@section('title', 'Katalog sprzętu')

@section('content')

<h1>Katalog sprzętu</h1>

<div class="product-grid">
    @forelse ($products as $product)
        <div class="product-card">
            <h2>{{ $product['name'] }}</h2>
            <p>{{ $product['category'] }}</p>
            <p>{{ $product['price'] }} zł / dzień</p>
            <p>{{ $product['description'] }}</p>
            <a href="{{ route('product', $product['id']) }}">Zobacz</a>
        </div>
    @empty
        <p>Brak produktów w katalogu.</p>
    @endforelse
</div>

@endsection