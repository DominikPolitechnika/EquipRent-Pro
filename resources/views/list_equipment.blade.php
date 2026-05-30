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
                    <a href="#">Panel główny</a>
                    <span>›</span>
                    <span>Inwentarz</span>
                </div>
                <h1 class="le-title">Lista Sprzętu</h1>

                {{-- FILTRY --}}
                <div class="le-filters">
                    <div class="le-filters-row">
                        <div class="le-filter-group">
                            <label class="le-filter-label">Kategoria</label>
                            <select class="le-select">
                                <option>Cały sprzęt</option>
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
                            <select class="le-select">
                                <option>Nazwa (A-Z)</option>
                            </select>
                        </div>
                    </div>

                    <div class="le-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" placeholder="Szukaj roweru, sprzętu, klienta...">
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
                        <tbody>
                            {{-- Wiersz 1 --}}
                            <tr>
                                <td>
                                    <div class="le-product">
                                        <div class="le-product-img"></div>
                                        <div>
                                            <div class="le-product-name"><span class="le-skel" style="width:180px;height:14px;"></span></div>
                                            <div class="le-product-sn"><span class="le-skel" style="width:110px;height:11px;"></span></div>
                                            <div class="le-product-rating"><span class="le-skel" style="width:90px;height:11px;"></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="le-skel" style="width:70px;height:13px;"></span></td>
                                <td><span class="le-skel" style="width:80px;height:13px;"></span></td>
                                <td><span class="le-badge rented"><span class="le-skel" style="width:70px;height:11px;"></span></span></td>
                                <td class="right">
                                    <div class="le-actions">
                                        <a href="{{ route('product.edit', 1) }}" class="le-action-btn" aria-label="Edytuj">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('rentals.list') }}" class="le-action-btn" aria-label="Historia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><polyline points="12 7 12 12 15 15"/>
                                            </svg>
                                        </a>
                                        <button type="button" class="le-action-btn danger" aria-label="Usuń">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Wiersz 2 --}}
                            <tr>
                                <td>
                                    <div class="le-product">
                                        <div class="le-product-img"></div>
                                        <div>
                                            <div class="le-product-name"><span class="le-skel" style="width:200px;height:14px;"></span></div>
                                            <div class="le-product-sn"><span class="le-skel" style="width:110px;height:11px;"></span></div>
                                            <div class="le-product-rating"><span class="le-skel" style="width:80px;height:11px;"></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="le-skel" style="width:90px;height:13px;"></span></td>
                                <td><span class="le-skel" style="width:80px;height:13px;"></span></td>
                                <td><span class="le-badge available"><span class="le-skel" style="width:60px;height:11px;"></span></span></td>
                                <td class="right">
                                    <div class="le-actions">
                                        <a href="{{ route('product.edit', 2) }}" class="le-action-btn" aria-label="Edytuj">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('rentals.list') }}" class="le-action-btn" aria-label="Historia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><polyline points="12 7 12 12 15 15"/>
                                            </svg>
                                        </a>
                                        <button type="button" class="le-action-btn danger" aria-label="Usuń">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Wiersz 3 --}}
                            <tr>
                                <td>
                                    <div class="le-product">
                                        <div class="le-product-img"></div>
                                        <div>
                                            <div class="le-product-name"><span class="le-skel" style="width:210px;height:14px;"></span></div>
                                            <div class="le-product-sn"><span class="le-skel" style="width:120px;height:11px;"></span></div>
                                            <div class="le-product-rating"><span class="le-skel" style="width:80px;height:11px;"></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="le-skel" style="width:60px;height:13px;"></span></td>
                                <td><span class="le-skel" style="width:80px;height:13px;"></span></td>
                                <td><span class="le-badge service"><span class="le-skel" style="width:80px;height:11px;"></span></span></td>
                                <td class="right">
                                    <div class="le-actions">
                                        <a href="{{ route('product.edit', 3) }}" class="le-action-btn" aria-label="Edytuj">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('rentals.list') }}" class="le-action-btn" aria-label="Historia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><polyline points="12 7 12 12 15 15"/>
                                            </svg>
                                        </a>
                                        <button type="button" class="le-action-btn danger" aria-label="Usuń">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Wiersz 4 --}}
                            <tr>
                                <td>
                                    <div class="le-product">
                                        <div class="le-product-img"></div>
                                        <div>
                                            <div class="le-product-name"><span class="le-skel" style="width:200px;height:14px;"></span></div>
                                            <div class="le-product-sn"><span class="le-skel" style="width:120px;height:11px;"></span></div>
                                            <div class="le-product-rating"><span class="le-skel" style="width:80px;height:11px;"></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="le-skel" style="width:70px;height:13px;"></span></td>
                                <td><span class="le-skel" style="width:80px;height:13px;"></span></td>
                                <td><span class="le-badge repair"><span class="le-skel" style="width:100px;height:11px;"></span></span></td>
                                <td class="right">
                                    <div class="le-actions">
                                        <a href="{{ route('product.edit', 4) }}" class="le-action-btn" aria-label="Edytuj">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('rentals.list') }}" class="le-action-btn" aria-label="Historia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><polyline points="12 7 12 12 15 15"/>
                                            </svg>
                                        </a>
                                        <button type="button" class="le-action-btn danger" aria-label="Usuń">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- STOPKA --}}
                <div class="le-footer">
                    <div class="le-footer-count">
                        Wyświetlono <span class="le-skel" style="width:14px;height:11px;"></span> z <span class="le-skel" style="width:24px;height:11px;"></span> sztuk sprzętu
                    </div>
                    <div class="le-pagination">
                        <a href="#" class="le-page nav">‹</a>
                        <a href="#" class="le-page active">1</a>
                        <a href="#" class="le-page">2</a>
                        <a href="#" class="le-page">3</a>
                        <a href="#" class="le-page nav">›</a>
                    </div>
                </div>

            </div>
        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}
<script>
(function() {
    // ===== Cena - dropdown z dual-range slider =====
    const toggle  = document.getElementById('le-price-toggle');
    const popover = document.getElementById('le-price-popover');
    const minIn   = document.getElementById('le-price-min');
    const maxIn   = document.getElementById('le-price-max');
    const minVal  = document.getElementById('le-price-min-val');
    const maxVal  = document.getElementById('le-price-max-val');
    const range   = document.getElementById('le-price-range');
    const label   = document.getElementById('le-price-label');

    if (toggle && popover && minIn && maxIn) {
        const MAX = parseInt(maxIn.max, 10); // 2000

        function updateUI() {
            let lo = parseInt(minIn.value, 10);
            let hi = parseInt(maxIn.value, 10);

            // gdyby uchwyty się minęły - blokujemy
            if (lo > hi - 10) {
                if (this === minIn) { lo = hi - 10; minIn.value = lo; }
                else                { hi = lo + 10; maxIn.value = hi; }
            }

            minVal.textContent = lo;
            maxVal.textContent = hi;

            const leftPct  = (lo / MAX) * 100;
            const rightPct = (hi / MAX) * 100;
            range.style.left  = leftPct  + '%';
            range.style.right = (100 - rightPct) + '%';

            // Etykieta na przycisku
            if (lo === 0 && hi === MAX) {
                label.textContent = 'Dowolna';
            } else {
                label.textContent = lo + ' - ' + hi + ' zł';
            }
        }

        minIn.addEventListener('input', updateUI);
        maxIn.addEventListener('input', updateUI);
        updateUI();

        // Toggle popovera
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = popover.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        // klik poza - zamknij
        document.addEventListener('click', (e) => {
            if (!popover.classList.contains('open')) return;
            if (popover.contains(e.target) || toggle.contains(e.target)) return;
            popover.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        });
        // Escape - zamknij
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && popover.classList.contains('open')) {
                popover.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ===== Konserwacja - grupy radio =====
    document.querySelectorAll('.le-toggle-group[data-radio]').forEach(group => {
        const buttons = group.querySelectorAll('.le-toggle-btn');
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    });
})();
</script>
</body>
</html>