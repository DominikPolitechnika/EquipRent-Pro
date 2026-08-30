<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista Sprzętu – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-list-equipment.css') }}">
    <style>
        /* ===== Cena - dropdown z suwakiem zakresu ===== */
        .le-price-group { position: relative; }

        .le-price-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            background: #fff;
            border: 1px solid #e8ebee;
            border-radius: 8px;
            padding: 10px 12px;
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            color: #2a3439;
            cursor: pointer;
            box-sizing: border-box;
        }
        .le-price-toggle svg { width: 14px; height: 14px; color: #777; flex-shrink: 0; }
        .le-price-toggle:hover { border-color: #aaa; }

        .le-price-popover {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            width: 260px;
            background: #fff;
            border: 1px solid #e8ebee;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            padding: 18px 18px 16px;
            z-index: 100;
        }
        .le-price-popover.open { display: block; }

        .le-price-values {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 600;
            color: #1a6fa8;
            margin-bottom: 14px;
        }

        .le-price-slider {
            position: relative;
            height: 24px;
            margin: 4px 6px 8px;
        }
        .le-price-track {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 4px;
            background: #e8ebee;
            border-radius: 4px;
            transform: translateY(-50%);
        }
        .le-price-range {
            position: absolute;
            top: 50%;
            height: 4px;
            background: #006398;
            border-radius: 4px;
            transform: translateY(-50%);
        }
        /* Oba inputy nakładają się jeden na drugi - tylko uchwyty są "klikalne" */
        .le-price-slider input[type=range] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 24px;
            background: transparent;
            -webkit-appearance: none;
            appearance: none;
            pointer-events: none;   /* sam track nieklikalny... */
            margin: 0;
        }
        .le-price-slider input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #006398;
            cursor: pointer;
            pointer-events: auto;   /* ...ale uchwyt już tak */
            box-shadow: 0 1px 3px rgba(0,0,0,.15);
        }
        .le-price-slider input[type=range]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #006398;
            cursor: pointer;
            pointer-events: auto;
            box-shadow: 0 1px 3px rgba(0,0,0,.15);
        }

        .le-price-bounds {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #aaa;
        }
    </style>
</head>
<body>
<div class="adm-shell">
    @include('partials.admin-sidebar')

    <div class="adm-body">
        @include('partials.admin-topbar')

        <div class="adm-content">
            <div class="le-content">

                {{-- NAGŁÓWEK --}}
                <div class="le-breadcrumb">
                    <span>Zarządzanie</span>
                    <span>›</span>
                    <span class="active">Inwentarz</span>
                </div>
                <h1 class="le-title">Inwentarz</h1>

                {{-- FILTRY --}}
                <div class="le-filters">
                    <div class="le-filters-row">
                        <div class="le-filter-group">
                            <label class="le-filter-label">Kategoria</label>
                            <select class="le-select" id="le-category-select" name="categories">
                                <option value="">Cały sprzęt</option>
                                {{-- Kategorie doładowywane przez JS --}}
                            </select>
                        </div>
                        <div class="le-filter-group le-price-group">
                            <label class="le-filter-label">Cena za dzień</label>
                            <button type="button" class="le-price-toggle" id="le-price-toggle" aria-haspopup="true" aria-expanded="false">
                                <span id="le-price-label">Dowolna</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="le-price-popover" id="le-price-popover">
                                <div class="le-price-values">
                                    <span><span id="le-price-min-val">0</span> zł</span>
                                    <span><span id="le-price-max-val">2000</span> zł</span>
                                </div>
                                <div class="le-price-slider">
                                    <div class="le-price-track"></div>
                                    <div class="le-price-range" id="le-price-range"></div>
                                    <input type="range" min="0" max="2000" value="0"    step="10" id="le-price-min" name="price_min">
                                    <input type="range" min="0" max="2000" value="2000" step="10" id="le-price-max" name="price_max">
                                </div>
                                <div class="le-price-bounds">
                                    <span>0 zł</span>
                                    <span>2000 zł</span>
                                </div>
                            </div>
                        </div>
                        <div class="le-filter-group">
                            <label class="le-filter-label">Dostępność</label>
                            <input type="text" class="le-input-date" placeholder="Wybierz daty">
                        </div>
                        <div class="le-filter-group">
                            <label class="le-filter-label">Konserwacja</label>
                            <div class="le-toggle-group" data-radio>
                                <button type="button" class="le-toggle-btn active" data-value="sprawny">Sprawny</button>
                                <button type="button" class="le-toggle-btn" data-value="serwis">Serwis</button>
                            </div>
                        </div>
                        <div class="le-filter-group">
                            <label class="le-filter-label">Sortuj według</label>
                            <select class="le-select" id="le-sort-select" name="sort">
                                <option value="name_asc">Nazwa (A-Z)</option>
                                <option value="name_desc">Nazwa (Z-A)</option>
                                <option value="price_asc">Cena rosnąco</option>
                                <option value="price_desc">Cena malejąco</option>
                            </select>
                        </div>
                    </div>

                    <div class="le-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="le-search-input" name="search" placeholder="Szukaj roweru, sprzętu, klienta...">
                    </div>
                </div>

                {{-- TABELA --}}
                <div class="le-card">
                    <table class="le-table">
                        <thead>
                            <tr>
                                <th>Sprzęt i identyfikacja</th>
                                <th>Kategoria</th>
                                <th>Stawka dobowa</th>
                                <th>Status</th>
                                <th class="right">Akcje</th>
                            </tr>
                        </thead>
                        <tbody id="le-tbody">
                            {{-- Skeleton - pokazywany do momentu fetchu --}}
                            <tr id="le-skeleton-row">
                                <td>
                                    <div class="le-product">
                                        <div class="le-product-img"></div>
                                        <div>
                                            <div class="le-product-name"><span class="le-skel" style="width:180px;height:14px;"></span></div>
                                            <div class="le-product-sn"><span class="le-skel" style="width:110px;height:11px;"></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="le-skel" style="width:70px;height:13px;"></span></td>
                                <td><span class="le-skel" style="width:80px;height:13px;"></span></td>
                                <td><span class="le-badge available"><span class="le-skel" style="width:60px;height:11px;"></span></span></td>
                                <td class="right">
                                    <div class="le-actions">
                                        <span class="le-skel" style="width:80px;height:20px;"></span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- STOPKA --}}
                <div class="le-footer">
                    <div class="le-footer-count" id="le-footer-count">
                        Wyświetlono <span>—</span> sztuk sprzętu
                    </div>
                    <div class="le-pagination" id="le-pagination">
                        {{-- Wypełniane przez JS --}}
                    </div>
                </div>

            </div>
        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}
<script>
(function () {
    'use strict';

    // ==============================================================
    // Konfiguracja i selektory
    // ==============================================================
    const CATALOG_URL = '/catalog';
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const tbody      = document.getElementById('le-tbody');
    const footerCnt  = document.getElementById('le-footer-count');
    const pagerEl    = document.getElementById('le-pagination');

    const searchInput   = document.getElementById('le-search-input');
    const categorySel   = document.getElementById('le-category-select');
    const sortSel       = document.getElementById('le-sort-select');
    const priceMinIn    = document.getElementById('le-price-min');
    const priceMaxIn    = document.getElementById('le-price-max');
    const priceLabel    = document.getElementById('le-price-label');
    const priceMinVal   = document.getElementById('le-price-min-val');
    const priceMaxVal   = document.getElementById('le-price-max-val');
    const priceRangeEl  = document.getElementById('le-price-range');

    let currentPage = 1;
    let debounceTimer = null;

    // ==============================================================
    // Utils
    // ==============================================================
    function escapeHtml(s) {
        if (s === null || s === undefined) return '';
        return String(s)
            .replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;').replaceAll("'", '&#039;');
    }

    function badgeForStatus(status) {
        const s = (status || '').toLowerCase();
        if (s.includes('dost')) return '<span class="le-badge available">Dostępny</span>';
        if (s.includes('wyp'))  return '<span class="le-badge rented">Wypożyczony</span>';
        if (s.includes('serw')) return '<span class="le-badge service">Serwis</span>';
        if (s.includes('napr')) return '<span class="le-badge repair">Naprawa</span>';
        return `<span class="le-badge available">${escapeHtml(status || '—')}</span>`;
    }

    // ==============================================================
    // Renderowanie wiersza tabeli
    // ==============================================================
    function renderRow(p) {
        // p = { id, image, title, sn, category, price, status }
        return `
        <tr>
            <td>
                <div class="le-product">
                    <div class="le-product-img" style="${p.image ? `background:url('${p.image}') center/cover;` : ''}"></div>
                    <div>
                        <div class="le-product-name">${escapeHtml(p.title)}</div>
                        <div class="le-product-sn">${escapeHtml(p.sn || '—')}</div>
                    </div>
                </div>
            </td>
            <td>${escapeHtml(p.category || '—')}</td>
            <td>${p.price ? escapeHtml(p.price) : '—'}</td>
            <td>${badgeForStatus(p.status)}</td>
            <td class="right">
                <div class="le-actions">
                    <a href="/produkt/${p.id}/edytuj" class="le-action-btn" aria-label="Edytuj">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>
                    <a href="/produkt/${p.id}" class="le-action-btn" aria-label="Podgląd">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </a>
                    <button type="button" class="le-action-btn danger" aria-label="Usuń" disabled title="Usuwanie dostępne po dodaniu API">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>`;
    }

    // ==============================================================
    // Parsowanie partiala HTML (z /catalog + X-Requested-With)
    // Wyciąga dane z kart produktów i buduje wiersze tabeli
    // ==============================================================
    function extractProductsFromHtml(html) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const products = [];
        // Twój partial ma karty z klasą .product-cards (mnoga)
        const cards = doc.querySelectorAll('.product-cards, .product-card');

        cards.forEach(card => {
            // Link do produktu - z niego wyciągamy ID
            const link = card.querySelector('a[href*="/produkt/"], a[href*="/product/"]');
            let id = null;
            if (link) {
                const match = link.getAttribute('href').match(/\/(?:produkt|product)\/(\d+)/);
                if (match) id = match[1];
            }

            // Obrazek
            const imgEl = card.querySelector('img');
            const image = imgEl ? imgEl.getAttribute('src') : null;

            // Nazwa
            const nameEl = card.querySelector('.product-title, .product-card-name, h3, h2');
            const title = nameEl ? nameEl.textContent.trim() : '—';

            // Kategoria
            const catEl = card.querySelector('.product-category, .product-card-category');
            const category = catEl ? catEl.textContent.trim() : '';

            // Cena
            const priceEl = card.querySelector('.price, .product-card-price, .price strong');
            let price = '';
            if (priceEl) {
                // Wyciągamy tylko "X zł" bez "/dzień"
                price = priceEl.textContent.replace(/\/\s*dzień/i, '').trim();
            }

            // Status (badge)
            const badgeEl = card.querySelector('.product-badge, .product-card-badge, .badge-green, .badge-orange');
            const status = badgeEl ? badgeEl.textContent.trim() : 'Dostępny';

            // Numer seryjny - w kartach katalogu go zwykle nie ma, pominiemy albo bierzemy z data-attr
            const sn = card.dataset.sn || card.querySelector('[data-sn]')?.dataset.sn || '';

            products.push({ id, image, title, category, price, status, sn });
        });

        // Paginacja - też z partiala
        const pageLinks = doc.querySelectorAll('.pagination-wrapper .pagination a, .katalog-pagination a');
        const pagination = [];
        pageLinks.forEach(a => {
            pagination.push({
                url: a.getAttribute('href'),
                label: a.textContent.trim(),
                active: a.classList.contains('active'),
            });
        });

        // Info o łącznej liczbie
        const info = doc.querySelector('.pagination .flex.justify-between p, .pagination-wrapper p');
        const totalText = info ? info.textContent.trim() : '';

        return { products, pagination, totalText };
    }

    // ==============================================================
    // Główny fetch
    // ==============================================================
    function buildParams(page) {
        const params = new URLSearchParams();

        if (searchInput?.value) params.append('search', searchInput.value);
        if (categorySel?.value) params.append('categories[]', categorySel.value);
        if (sortSel?.value)     params.append('sort', sortSel.value);

        // Cena - jak MAX < 2000 to price_range
        if (priceMaxIn) {
            const priceMax = parseInt(priceMaxIn.value, 10);
            if (priceMax < 2000) params.append('price_range', priceMax);
        }

        // Konserwacja - toggle "Sprawny" / "Serwis"
        const activeToggle = document.querySelector('.le-toggle-group[data-radio] .le-toggle-btn.active');
        // Nie mamy dedykowanego filtra w /catalog na "serwis" vs "sprawny",
        // więc na razie ignorujemy - tylko wpływa na UI

        params.append('page', page);

        return params.toString();
    }

    async function fetchProducts(page = 1) {
        currentPage = page;

        // Skeleton na czas pobierania
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align:center;padding:40px;color:#6b7280;">
                    Ładowanie produktów...
                </td>
            </tr>`;

        try {
            const url = `${CATALOG_URL}?${buildParams(page)}`;
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                    'X-CSRF-TOKEN': CSRF,
                },
                credentials: 'same-origin',
            });

            if (!response.ok) throw new Error('HTTP ' + response.status);

            const html = await response.text();
            const { products, pagination, totalText } = extractProductsFromHtml(html);

            // Wypełnij tabelę
            if (products.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:#6b7280;">
                            Brak produktów spełniających kryteria.
                        </td>
                    </tr>`;
            } else {
                tbody.innerHTML = products.map(renderRow).join('');
            }

            // Licznik
            footerCnt.innerHTML = totalText
                ? escapeHtml(totalText)
                : `Wyświetlono <strong>${products.length}</strong> sztuk sprzętu`;

            // Paginacja
            renderPagination(pagination);

        } catch (err) {
            console.error('Błąd pobierania produktów:', err);
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:#dc2626;">
                        Nie udało się pobrać produktów. Spróbuj odświeżyć stronę.
                    </td>
                </tr>`;
        }
    }

    function renderPagination(pagination) {
        if (!pagination || pagination.length === 0) {
            pagerEl.innerHTML = '';
            return;
        }
        pagerEl.innerHTML = pagination.map(p => {
            // Wyciągamy numer strony z URL-a
            const pageMatch = p.url ? p.url.match(/[?&]page=(\d+)/) : null;
            const page = pageMatch ? pageMatch[1] : null;
            const classes = ['le-page'];
            if (p.active) classes.push('active');
            if (p.label === '«' || p.label === '»' || p.label.includes('&laquo;') || p.label.includes('&raquo;'))
                classes.push('nav');

            return `<a href="#" data-page="${page || ''}" class="${classes.join(' ')}">${escapeHtml(p.label)}</a>`;
        }).join('');
    }

    // Klik w paginację
    pagerEl.addEventListener('click', (e) => {
        const link = e.target.closest('a[data-page]');
        if (!link) return;
        e.preventDefault();
        const page = parseInt(link.dataset.page, 10);
        if (page > 0) fetchProducts(page);
    });

    // ==============================================================
    // Kategorie - pobieramy je z pierwszej strony /catalog (bez X-Requested-With)
    // dostajemy pełną stronę i wyciągamy checkboxy kategorii
    // ==============================================================
    async function loadCategories() {
        try {
            const response = await fetch(CATALOG_URL, {
                headers: { 'Accept': 'text/html' },
                credentials: 'same-origin',
            });
            if (!response.ok) return;

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const checkboxes = doc.querySelectorAll('input[name="categories[]"]');
            checkboxes.forEach(cb => {
                const label = cb.closest('label');
                const name = label ? label.textContent.trim() : '';
                if (name) {
                    const opt = document.createElement('option');
                    opt.value = cb.value;
                    opt.textContent = name;
                    categorySel.appendChild(opt);
                }
            });
        } catch (err) {
            console.warn('Nie udało się pobrać kategorii:', err);
        }
    }

    // ==============================================================
    // Cena - dual-range slider (istniejący kod)
    // ==============================================================
    if (priceMinIn && priceMaxIn && priceLabel) {
        const MAX = parseInt(priceMaxIn.max, 10);

        function updatePriceUI() {
            let lo = parseInt(priceMinIn.value, 10);
            let hi = parseInt(priceMaxIn.value, 10);
            if (lo > hi - 10) {
                if (this === priceMinIn) { lo = hi - 10; priceMinIn.value = lo; }
                else                      { hi = lo + 10; priceMaxIn.value = hi; }
            }
            priceMinVal.textContent = lo;
            priceMaxVal.textContent = hi;

            const leftPct  = (lo / MAX) * 100;
            const rightPct = (hi / MAX) * 100;
            priceRangeEl.style.left  = leftPct + '%';
            priceRangeEl.style.right = (100 - rightPct) + '%';

            if (lo === 0 && hi === MAX) priceLabel.textContent = 'Dowolna';
            else priceLabel.textContent = lo + ' - ' + hi + ' zł';
        }

        priceMinIn.addEventListener('input', updatePriceUI);
        priceMaxIn.addEventListener('input', updatePriceUI);
        // Dodatkowo debounce fetch na zmianę
        priceMinIn.addEventListener('change', () => scheduleFetch());
        priceMaxIn.addEventListener('change', () => scheduleFetch());

        updatePriceUI();

        // Toggle popovera
        const toggle = document.getElementById('le-price-toggle');
        const popover = document.getElementById('le-price-popover');
        if (toggle && popover) {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                popover.classList.toggle('open');
                toggle.setAttribute('aria-expanded', popover.classList.contains('open') ? 'true' : 'false');
            });
            document.addEventListener('click', (e) => {
                if (!popover.classList.contains('open')) return;
                if (popover.contains(e.target) || toggle.contains(e.target)) return;
                popover.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && popover.classList.contains('open')) {
                    popover.classList.remove('open');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    }

    // ==============================================================
    // Debouncing wyszukiwarki i sortowania
    // ==============================================================
    function scheduleFetch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchProducts(1), 400);
    }

    searchInput?.addEventListener('input', scheduleFetch);
    categorySel?.addEventListener('change', () => fetchProducts(1));
    sortSel?.addEventListener('change', () => fetchProducts(1));

    // ==============================================================
    // Konserwacja - grupy radio (bez fetchy - to tylko UI)
    // ==============================================================
    document.querySelectorAll('.le-toggle-group[data-radio]').forEach(group => {
        const buttons = group.querySelectorAll('.le-toggle-btn');
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                // Można by tu ograniczać po statusie ale /catalog tego nie ma
            });
        });
    });

    // ==============================================================
    // START
    // ==============================================================
    loadCategories();
    fetchProducts(1);
})();
</script>
</body>
</html>