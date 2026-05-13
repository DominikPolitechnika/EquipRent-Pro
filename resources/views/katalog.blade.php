<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog sprzętu — EquipRent Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;900&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
   
    <link rel="stylesheet" href="{{ asset('style-katalog.css') }}">
</head>
<body>

@include('partials.header')

<main>
    <div class="katalog-page">

        <div class="katalog-breadcrumb">
            <a href="{{ route('home') }}">Strona główna</a>
            <span>›</span>
            <span class="katalog-breadcrumb-active">Katalog sprzętu</span>
        </div>

        <div class="katalog-header">
            <h1 class="katalog-title">Katalog sprzętu</h1>
            <p class="katalog-subtitle">Wybierz sprzęt i zarezerwuj na wybrany okres.</p>
        </div>

        <div class="product-grid">
            @forelse ($products as $product)
                <div class="product-card">
                    {{-- z tym warunkiem w if to tymczasowo --}}
                    <div class="product-card-img">
                        <img src="https://placehold.co/400x240/1a1a1a/444?text={{ urlencode($product->title) }}" alt="{{ $product->title }}">
                        @if('dostepny' === 'dostepny') 
                            <span class="product-card-badge badge-green">Dostępny</span>
                        @elseif('tymczasowo'=== 'wypozyczony')
                            <span class="product-card-badge badge-orange">Wypożyczony</span>
                        @else
                            <span class="product-card-badge badge-gray">Serwis</span>
                        @endif
                    </div>

                    <div class="product-card-body">
                        <div class="product-card-category">{{ $product->category->name }}</div>
                        <h2 class="product-card-name">{{ $product->title }}</h2>
                        <p class="product-card-desc">{{ $product->body }}</p>
                        <div class="product-card-footer">
                            <span class="product-card-price">{{ $product->oneDayPrice }} zł <small>/ dzień</small></span>
                            <a href="{{ route('product', $product->id) }}" class="product-card-btn">Zobacz →</a>
                        </div>
                    </div>

                </div>
            @empty
                <p class="katalog-empty">Brak produktów w katalogu.</p>
            @endforelse
        </div>

    </div>
</main>

@include('partials.footer')

<style>
    /* ================================================
       KATALOG PAGE — inline styles
       (przenieś do osobnego style-katalog.css)
    ================================================ */

    body {
        font-family: 'Barlow', sans-serif;
        background: #f7f9fb;
        margin: 0;
        padding: 0;
    }

    .katalog-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 40px 60px;
    }

    /* BREADCRUMB */
    .katalog-breadcrumb {
        padding: 14px 0;
        font-size: 12px;
        color: #777;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .katalog-breadcrumb a {
        color: #777;
        text-decoration: none;
    }
    .katalog-breadcrumb a:hover { color: #2563eb; }
    .katalog-breadcrumb span { margin: 0 8px; }
    .katalog-breadcrumb-active { color: #2a3439; font-weight: 600; }

    /* HEADER */
    .katalog-header {
        margin-bottom: 32px;
    }
    .katalog-title {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 48px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -.01em;
        color: #2a3439;
        margin: 0 0 8px;
    }
    .katalog-subtitle {
        font-size: 14px;
        color: #777;
        margin: 0;
    }

    /* GRID */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    /* CARD */
    .product-card {
        background: #fff;
        border: 1px solid #e8ebee;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: box-shadow .2s, transform .2s;
    }
    .product-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,.08);
        transform: translateY(-2px);
    }

    .product-card-img {
        position: relative;
        height: 200px;
        background: #e8e8e8;
    }
    .product-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .product-card-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 4px 10px;
    }
    .badge-green  { background: #dcfce7; color: #16a34a; }
    .badge-orange { background: #ffedd5; color: #ea580c; }
    .badge-gray   { background: #f3f4f6; color: #6b7280; }

    .product-card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .product-card-category {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #aaa;
        margin-bottom: 6px;
    }

    .product-card-name {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 20px;
        font-weight: 700;
        text-transform: uppercase;
        color: #2a3439;
        margin: 0 0 8px;
        line-height: 1.1;
    }

    .product-card-desc {
        font-size: 13px;
        color: #777;
        line-height: 1.6;
        margin: 0 0 16px;
        flex: 1;
    }

    .product-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
    }

    .product-card-price {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 22px;
        font-weight: 900;
        color: #2a3439;
    }
    .product-card-price small {
        font-size: 13px;
        font-weight: 400;
        color: #777;
    }

    .product-card-btn {
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .06em;
        padding: 9px 18px;
        transition: background .2s;
    }
    .product-card-btn:hover { background: #1d4ed8; }

    .katalog-empty {
        grid-column: 1 / -1;
        text-align: center;
        color: #aaa;
        font-size: 14px;
        padding: 60px 0;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
        .katalog-page { padding: 0 20px 40px; }
        .product-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .product-grid { grid-template-columns: 1fr; }
        .katalog-title { font-size: 32px; }
    }
</style>

</body>
</html>