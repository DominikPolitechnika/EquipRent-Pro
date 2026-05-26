<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista Sprzętu – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-list-equipment.css') }}">
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
                        <div class="le-filter-group">
                            <label class="le-filter-label">Cena za dzień</label>
                            <select class="le-select">
                                <option>Dowolna</option>
                            </select>
                        </div>
                        <div class="le-filter-group">
                            <label class="le-filter-label">Dostępność</label>
                            <input type="text" class="le-input-date" placeholder="Wybierz daty">
                        </div>
                        <div class="le-filter-group">
                            <label class="le-filter-label">Konserwacja</label>
                            <div class="le-toggle-group">
                                <button type="button" class="le-toggle-btn active">Sprawny</button>
                                <button type="button" class="le-toggle-btn">Serwis</button>
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
                                        <button type="button" class="le-action-btn" aria-label="Historia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><polyline points="12 7 12 12 15 15"/>
                                            </svg>
                                        </button>
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
                                        <button type="button" class="le-action-btn" aria-label="Historia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><polyline points="12 7 12 12 15 15"/>
                                            </svg>
                                        </button>
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
                                        <button type="button" class="le-action-btn" aria-label="Historia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><polyline points="12 7 12 12 15 15"/>
                                            </svg>
                                        </button>
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
                                        <button type="button" class="le-action-btn" aria-label="Historia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><polyline points="12 7 12 12 15 15"/>
                                            </svg>
                                        </button>
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
</body>
</html>