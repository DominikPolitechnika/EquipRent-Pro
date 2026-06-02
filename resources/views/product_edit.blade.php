<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Zarządzanie produktem – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-product-edit.css') }}">
    <style>
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.45; } }
        .placeholder { background: #e2e8f0; display: inline-block; border-radius: 3px; }
        .placeholder-block { background: #e2e8f0; display: block; border-radius: 3px; }
        .animate-pulse { animation: pulse 1.6s ease-in-out infinite; }
    </style>
</head>
<body class="pe-page">
<div class="adm-shell">
    @include('partials.admin-sidebar')

    <div class="adm-body">
        @include('partials.admin-topbar')
        <div class="adm-content">

{{-- ===== NAGŁÓWEK STRONY ===== --}}
<div class="pe-page-header">
    <div class="pe-page-header-text">
        <div class="pe-breadcrumb">
            <span>Zarządzanie</span>
            <span class="sep">›</span>
            <a href="{{ route('equipment.list') }}">Inwentarz</a>
            <span class="sep">›</span>
            <span class="active">Zarządzanie Produktem</span>
        </div>
        <h1>
            <span class="placeholder animate-pulse" style="width:320px;height:32px;"></span>
            <span class="pe-title-badge">
                <span class="placeholder animate-pulse" style="width:70px;height:12px;background:#ffffff66;"></span>
            </span>
        </h1>
        <p class="pe-serial">
            # Nr seryjny: <span class="placeholder animate-pulse" style="width:120px;height:13px;"></span>
        </p>
    </div>
    <div class="pe-header-actions">
        <a href="{{ route('catalog') }}" class="pe-btn-secondary">Odrzuć</a>
        <button type="submit" form="form-edycja-produktu" class="pe-btn-primary">Aktualizuj Produkt</button>
    </div>
</div>

<div class="pe-wrapper">

    {{-- ===== LEWA KOLUMNA ===== --}}
    <div class="pe-main">

        {{-- INFORMACJE OGÓLNE --}}
        <form id="form-edycja-produktu" method="POST" action="#" onsubmit="return false;" class="pe-section" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="pe-section-header">
                <div class="pe-section-title">
                    <div class="pe-section-title-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </div>
                    Informacje ogólne
                </div>

                <div class="pe-status-toggle">
                    <span class="pe-status-toggle-label">Przełącz status</span>
                    <span class="pe-status-state" id="pe-status-text">
                        <span class="placeholder animate-pulse" style="width:60px;height:12px;"></span>
                    </span>
                    <label class="pe-switch">
                        <input type="checkbox" id="pe-status-switch" checked>
                        <span class="pe-switch-slider"></span>
                    </label>
                </div>
            </div>

            <div class="pe-form-row">
                <div class="pe-form-group">
                    <label class="pe-form-label">Nazwa produktu</label>
                    <div class="pe-form-input" style="display:flex;align-items:center;">
                        <span class="placeholder-block animate-pulse" style="width:60%;height:14px;"></span>
                    </div>
                </div>
                <div class="pe-form-group">
                    <label class="pe-form-label">Kategoria</label>
                    <div class="pe-form-input" style="display:flex;align-items:center;">
                        <span class="placeholder-block animate-pulse" style="width:50%;height:14px;"></span>
                    </div>
                </div>
            </div>

            <div class="pe-form-row single">
                <div class="pe-form-group">
                    <label class="pe-form-label">Opis produktu</label>
                    <div class="pe-form-textarea" style="display:block;">
                        <span class="placeholder-block animate-pulse" style="width:100%;height:12px;margin-bottom:8px;"></span>
                        <span class="placeholder-block animate-pulse" style="width:95%;height:12px;margin-bottom:8px;"></span>
                        <span class="placeholder-block animate-pulse" style="width:80%;height:12px;"></span>
                    </div>
                </div>
            </div>

            <div class="pe-form-row single">
                <div class="pe-form-group">
                    <label class="pe-form-label">Numer seryjny</label>
                    <div class="pe-form-readonly">
                        <span class="placeholder-block animate-pulse" style="width:160px;height:14px;"></span>
                    </div>
                </div>
            </div>

            {{-- GALERIA ZDJĘĆ --}}
            <div class="pe-gallery-block">
                <div class="pe-gallery-head">
                    <span class="pe-gallery-label">Galeria zdjęć</span>
                    <span class="pe-gallery-count">
                        <span class="placeholder animate-pulse" style="width:70px;height:10px;"></span>
                    </span>
                </div>
                <div class="pe-gallery-grid">
                    <div class="pe-gallery-thumb animate-pulse"></div>
                    <div class="pe-gallery-thumb animate-pulse"></div>
                    <label class="pe-gallery-upload">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <span>Prześlij</span>
                        <input type="file" name="photos[]" accept="image/*" hidden multiple>
                    </label>
                </div>
            </div>
        </form>

        {{-- KONSERWACJA I NAPRAWY --}}
        <section class="pe-section">
            <div class="pe-section-header">
                <div class="pe-section-title">
                    <div class="pe-section-title-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                    </div>
                    Konserwacja i Naprawy
                </div>
                <button type="button" class="pe-btn-secondary" id="pe-maint-add-toggle">+ Dodaj wpis</button>
            </div>

            <div class="pe-maint-section-label">Zgłoś nową naprawę</div>
            <div class="pe-maint-form">
                <div class="pe-form-group">
                    <input type="text" class="pe-form-input" placeholder="Opis usterki...">
                </div>
                <div class="pe-form-group">
                    <input type="text" class="pe-form-input" placeholder="PLN Koszt">
                </div>
                <div class="pe-form-group">
                    <input type="date" class="pe-form-input">
                </div>
                <button type="button" class="pe-maint-add-btn">Wyślij</button>
            </div>

            <table class="pe-table" style="margin-top:20px;">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Opis</th>
                        <th>Technik</th>
                        <th class="right">Koszt</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="placeholder animate-pulse" style="width:50px;height:13px;"></span></td>
                        <td><span class="placeholder animate-pulse" style="width:160px;height:13px;"></span></td>
                        <td><span class="placeholder animate-pulse" style="width:70px;height:13px;"></span></td>
                        <td class="right"><span class="placeholder animate-pulse" style="width:50px;height:13px;"></span></td>
                    </tr>
                    <tr>
                        <td><span class="placeholder animate-pulse" style="width:50px;height:13px;"></span></td>
                        <td><span class="placeholder animate-pulse" style="width:180px;height:13px;"></span></td>
                        <td><span class="placeholder animate-pulse" style="width:60px;height:13px;"></span></td>
                        <td class="right"><span class="placeholder animate-pulse" style="width:45px;height:13px;"></span></td>
                    </tr>
                </tbody>
            </table>
        </section>

        {{-- HISTORIA REZERWACJI --}}
        <section class="pe-section">
            <div class="pe-section-header">
                <div class="pe-section-title">
                    <div class="pe-section-title-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    Historia Rezerwacji
                </div>
            </div>

            <table class="pe-table">
                <thead>
                    <tr>
                        <th>Użytkownik / Klub</th>
                        <th>Okres wynajmu</th>
                        <th>Status</th>
                        <th class="right">Przychód</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div><span class="placeholder animate-pulse" style="width:110px;height:14px;"></span></div>
                            <div class="pe-rez-user-id"><span class="placeholder animate-pulse" style="width:80px;height:11px;margin-top:4px;"></span></div>
                        </td>
                        <td><span class="placeholder animate-pulse" style="width:130px;height:13px;"></span></td>
                        <td>
                            <span class="pe-rez-badge done">
                                <span class="placeholder animate-pulse" style="width:70px;height:11px;background:#ffffff66;"></span>
                            </span>
                        </td>
                        <td class="right"><span class="placeholder animate-pulse" style="width:55px;height:13px;"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <div><span class="placeholder animate-pulse" style="width:120px;height:14px;"></span></div>
                            <div class="pe-rez-user-id"><span class="placeholder animate-pulse" style="width:80px;height:11px;margin-top:4px;"></span></div>
                        </td>
                        <td><span class="placeholder animate-pulse" style="width:130px;height:13px;"></span></td>
                        <td>
                            <span class="pe-rez-badge reserved">
                                <span class="placeholder animate-pulse" style="width:80px;height:11px;background:#ffffff66;"></span>
                            </span>
                        </td>
                        <td class="right"><span class="placeholder animate-pulse" style="width:55px;height:13px;"></span></td>
                    </tr>
                </tbody>
            </table>

            <div class="pe-section-footer">
                <a href="#">Pokaż wszystkie rezerwacje</a>
            </div>
        </section>

    </div>

    {{-- ===== PRAWA KOLUMNA ===== --}}
    <aside class="pe-side">

        {{-- MODEL CENNIKOWY --}}
        <div class="pe-price-card">
            <div class="pe-price-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                Model Cennikowy
            </div>

            <div class="pe-price-label">Stawka dobowa</div>
            <div class="pe-price-daily">
                <span class="placeholder-block animate-pulse" style="width:90px;height:22px;"></span>
            </div>

            <div class="pe-price-label">Całkowity przychód</div>
            <div class="pe-price-income">
                <span class="placeholder-block animate-pulse" style="width:140px;height:28px;"></span>
            </div>
        </div>

        {{-- STREFA ZAGROŻENIA --}}
        <div class="pe-danger-card">
            <div class="pe-danger-title">Strefa zagrożenia</div>
            <div class="pe-danger-text">
                Usunięcie tego sprzętu spowoduje trwałe zarchiwizowanie wszystkich danych operacyjnych. Tego procesu nie można cofnąć.
            </div>
            <button type="button" class="pe-danger-btn" id="pe-delete-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                Usuń z inwentarza
            </button>
        </div>

    </aside>
</div>

        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}

<script>
(function() {
    // Toggle statusu - tylko wizualne przełączenie (podgląd)
    const sw = document.getElementById('pe-status-switch');
    if (sw) {
        sw.addEventListener('change', () => { /* podgląd - bez akcji */ });
    }
})();
</script>
</body>
</html>