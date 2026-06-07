<div class="products-grid" id="productsGrid">
    @forelse ($products ?? [] as $product)
        <div class="product-cards">

            <div class="product-image">
                <img
                    src="{{ $product->getMainImageUrl() }}"
                    alt="{{ $product->title ?? 'PRODUKT' }}"
                >

                @if($product->getStatus() === 'Dostępny')
                    <span class="product-badge">
                        Dostępny
                    </span>
                @elseif($product->getStatus() === 'Wypożyczony')
                    <span class="product-badge" style="color: #f16012cc; background: #ffd7c2;">
                        Wypożyczony
                    </span>
                @else
                    <span class="product-badge" style="color: #8e98aa; background: #e5edfb">
                        Serwis
                    </span>
                @endif

            </div>

            <div class="product-body">

                <div class="product-meta">
                    <p class="product-category">
                        {{ $product->getProductCategoryName() ?? 'SPRZĘT' }}
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
                        <strong>{{ $product->one_day_price ?? 0 }} zł</strong>
                        <span>/ dzień</span>
                    </div>

                    <a href="{{ route('product', $product->id) }}" class="rent-btn" style="text-decoration: none;">
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