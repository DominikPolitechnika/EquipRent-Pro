<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    value="{{ $category->id }}"
                    >
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
                value="{{ request('price_range') ?? 200 }}" 
                class="price-range" 
                name="price_range"
                id="price_range">

            <div class="price-values">
                <span>0 zł</span>
                <span id="price-display">{{ request('price_range') ?? 200 }} zł</span>
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
                <select name="sort" class="sort-select">

                    <option value="">Sortuj według</option>

                    <option value="price_asc"
                    {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Cena rosnąco</option>
                    
                    <option value="price_desc"
                    {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Cena malejąco</option>

                    <option value="name_asc"
                    {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nazwa A-Z</option>

                    <option value="name_desc"
                    {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nazwa Z-A</option>
                </select>

                <button class="view-button active" id="gridViewBtn">
                    <i class="fa-solid fa-grip"></i>
                </button>

                <button class="view-button" id="listViewBtn">
                    <i class="fa-solid fa-list"></i>
                </button>
            </div>
        </div>

        <div id="products-container">
            @include('partials.catalog-products')
        </div>

    </section>

</main>

@include('partials.footer')

<script>
    const gridButton = document.getElementById('gridViewBtn');
    const listButton = document.getElementById('listViewBtn');
    const productsGrid = document.getElementById('productsGrid');

    function setView(view) {
        if (view === 'list') {
            productsGrid.classList.add('list-view');
            listButton.classList.add('active');
            gridButton.classList.remove('active');
        } else {
            productsGrid.classList.remove('list-view');
            gridButton.classList.add('active');
            listButton.classList.remove('active');
        }

        localStorage.setItem('catalogView', view);
    }

    const savedView = localStorage.getItem('catalogView') || 'grid';
    setView(savedView);

    gridButton.addEventListener('click', () => {
        setView('grid');
    });

    listButton.addEventListener('click', () => {
        setView('list');
    });

    const sidebar = document.querySelector('.catalog-sidebar');

    sidebar.querySelectorAll('input[type="checkbox"], input[type="date"]')
    .forEach(el => el.addEventListener('change',fetchProducts));

    let debounceTimer; //debounce wyszukiwania
    sidebar.querySelector('[name="search"]').addEventListener('input',() => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchProducts, 400);
    });

    const priceRange = sidebar.querySelector('.price-range');
    priceRange.addEventListener('input', () => {
        document.getElementById('price-display').textContent = priceRange.value + 'zł';
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchProducts,300);
    });

    document.querySelector('[name="sort"]').addEventListener('change',fetchProducts);

    function fetchProducts(){
        const params = new URLSearchParams();

        params.append('search',sidebar.querySelector('[name="search"]').value);
        params.append('price_range',sidebar.querySelector('[name="price_range"]').value);

        sidebar.querySelectorAll('[name="categories[]"]:checked')
        .forEach(cb => params.append('categories[]',cb.value));

        const dateFrom = sidebar.querySelector('[name="date_from"]').value;
        const dateTo = sidebar.querySelector('[name="date_to"]').value;
        if(dateFrom) params.append('date_from',dateFrom);
        if(dateTo) params.append('date_to',dateTo);
        params.append('sort',document.querySelector('[name="sort"]').value);

        const container = document.getElementById('products-container');
        container.style.opacity = '0.5';

        fetch('/catalog?' + params.toString(), {
            headers : { 
                'X-Requested-With' : 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
        if (!response.ok) throw new Error('Błąd serwera: ' + response.status);
        return response.text();
        })
        .then(html => {
            container.innerHTML = html;
            container.style.opacity = '1';
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<p>Wystąpił błąd podczas ładowania produktów.</p>';
            container.style.opacity = '1';
        });
    }

    document.querySelector('.reset-btn').addEventListener('click', () => {
        sidebar.querySelectorAll('input').forEach(input => {
            if (input.type === 'checkbox') input.checked = false;
            if (input.type === 'range')    input.value = 200;
            if (input.type === 'date')     input.value = '';
            if (input.type === 'text')     input.value = '';
        });
        document.getElementById('price-display').textContent = '200 zł';
        fetchProducts();
    });
</script>

</body>
</html>