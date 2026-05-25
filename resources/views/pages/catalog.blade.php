<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog sprzętu</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-catalog.css') }}">
</head>
<body>

@include('partials.header')

<main class="catalog-page">

    <aside class="catalog-sidebar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input 
            type="text" 
            placeholder="Szukaj sprzętu..." 
            class="catalog-search"
            name="search">
        </div>

        <h3>Filtry</h3>

        <div class="filter-group">
            <p class="filter-title">RODZAJ SPRZĘTU</p>
            @foreach($categories as $category)
            <label class="filter-option">
                <input 
                    type="checkbox" 
                    name="categories[]" 
                    value="{{ $category->id }}">
                {{ $category->name }}
            </label>
            @endforeach
        </div>

        <div class="filter-group price-group">
            <p class="filter-title">ZAKRES CENY (DZIEŃ)</p>

            <input 
                type="range" 
                min="0" 
                max="200" 
                value="200" 
                class="price-range" 
                name="price_range">

            <div class="price-values">
                <span>0 zł</span>
                <span>200 zł</span>
            </div>
        </div>

        <div class="filter-group">
            <p class="filter-title">TERMIN DOSTĘPNOŚCI</p>

            <div class="date-group">
                <div class="date-field">
                    <label class="date-label">Data od</label>
                    <div class="date-box">
                        <i class="fa-regular fa-calendar date-icon"></i>
                        <input type="date" class="date-input" name="date_from">
                    </div>
                </div>

                <div class="date-field">
                    <label class="date-label">Data do</label>
                    <div class="date-box">
                        <i class="fa-regular fa-calendar date-icon"></i>
                        <input type="date" class="date-input" name="date_to">
                    </div>
                </div>
            </div>

            <button class="reset-btn">Resetuj filtry</button>
        </div>
    </aside>

    <section class="catalog-content">

        <div class="active-rentals-section">
            <div class="active-rentals-header">
                <h2>Aktualne Wypożyczenia</h2>
                <span>AKTYWNE</span>
            </div>

            <div class="active-rentals-list">
                @forelse ($activeRentals ?? [] as $rental)
                    <a href="{{ route('product', $rental['product_id'] ?? 1) }}" class="active-rental-card">
                        <div class="active-rental-image">
                            <img src="{{ $rental['image'] ?? asset('images/placeholder.jpg') }}" alt="{{ $rental['name'] ?? 'produkt' }}">
                            <span>WYPOŻYCZONE</span>
                        </div>

                        <div class="active-rental-body">
                            <h3>{{ $rental['name'] ?? 'Nazwa produktu' }}</h3>
                            <p>Zwrot: {{ $rental['return_date'] ?? 'brak daty' }}</p>
                        </div>
                    </a>
                @empty
                    <p class="empty-rentals">Brak aktualnych wypożyczeń.</p>
                @endforelse
            </div>
        </div>

        <div class="catalog-header">
            <div>
                <span class="catalog-breadcrumb">Sprzęt / Katalog</span>

                <h1>Katalog Sprzętu Sportowego</h1>

                <p>
                    Przeglądaj naszą bogatą ofertę profesjonalnego sprzętu sportowego.
                    Najwyższe standardy dla Twojego treningu.
                </p>
            </div>

            <div class="catalog-tools">
                <button class="sort-button">
                    <i class="fa-solid fa-arrow-down-wide-short"></i>
                    Sortuj według
                    <i class="fa-solid fa-chevron-down"></i>
                </button>

                <button class="view-button active">
                    <i class="fa-solid fa-grip"></i>
                </button>

                <button class="view-button">
                    <i class="fa-solid fa-list"></i>
                </button>
            </div>
        </div>

        <div class="products-grid">
            @forelse ($products ?? [] as $product)
                <div class="product-cards">

                    <div class="product-image">
                        <img
                            src="{{ asset('images/placeholder.jpg') }}"
                            alt="{{ $product->title ?? 'produkt' }}"
                        >

                        <span class="product-badge">
                            {{ $product->isAvailable ? 'DOSTĘPNY' : 'WYPOŻYCZONY' }}
                        </span>
                    </div>

                    <div class="product-body">

                        <div class="product-meta">
                            <p class="product-category">
                                {{ $product->category->name ?? 'SPRZĘT' }}
                            </p>
                        </div>

                        <h3 class="product-title">
                            {{ $product->title ?? 'Nazwa produktu' }}
                        </h3>

                        <p class="product-desc">
                            {{ $product->body ?? 'Krótki opis produktu...' }}
                        </p>

                        <div class="product-footer">
                            <div class="price">
                                <strong>{{ $product->oneDayPrice ?? 0 }} zł</strong>
                                <span>/ dzień</span>
                            </div>

                            <a href="{{ route('product', $product->id) }}" class="rent-btn">
                                Wypożycz
                            </a>
                        </div>

                    </div>

                </div>
            @empty
                <div class="empty-products">
                    Brak produktów do wyświetlenia.
                </div>
            @endforelse
        </div>

        <div class="pagination-wrapper">
            {{ $products->onEachSide(1)->links() }}
        </div>

    </section>

</main>

@include('partials.footer')

<script>
    document.querySelectorAll('.date-input').forEach((input) => {
        input.addEventListener('click', () => {
            if (input.showPicker) {
                input.showPicker();
            }
        });
    });

    document.querySelector('.reset-btn').addEventListener('click', () => {
        document.querySelectorAll('input').forEach(input => {
            if (input.type === 'checkbox') input.checked = false;
            if (input.type === 'range') input.value = 200;
            if (input.type === 'date') input.value = '';
            if (input.type === 'text') input.value = '';
        });
    });
</script>

</body>
</html>