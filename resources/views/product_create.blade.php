<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dodaj sprzęt – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-product-edit.css') }}">
    <style>
        .pe-alert{padding:12px 16px;border-radius:8px;margin-bottom:18px;font-size:13px}
        .pe-alert.success{background:#dcfce7;color:#166534}
        .pe-alert.error{background:#fee2e2;color:#991b1b}
        .pe-alert.info{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
        .pe-gallery-thumb{position:relative}
        .pe-gallery-thumb button{position:absolute;right:4px;top:4px;width:24px;height:24px;border:0;border-radius:50%;background:#dc2626;color:#fff;cursor:pointer;font-weight:700;line-height:1}
        .pe-gallery-help{font-size:11px;color:#6b7280;margin-top:8px}
        .pe-row-error{font-size:11px;color:#dc2626;margin-top:4px}
    </style>
</head>
<body class="pe-page">
<div class="adm-shell">
    @include('partials.admin-sidebar')
    <div class="adm-body">
        @include('partials.admin-topbar')
        <div class="adm-content">

            <div class="pe-page-header">
                <div class="pe-page-header-text">
                    <div class="pe-breadcrumb">
                        <span>Zarządzanie</span><span class="sep">›</span>
                        <a href="{{ route('equipment.list') }}">Inwentarz</a><span class="sep">›</span>
                        <span class="active">Dodaj sprzęt</span>
                    </div>
                    <h1>Nowy sprzęt</h1>
                    <p class="pe-serial">Wypełnij dane produktu i dodaj minimum 3 zdjęcia (max 10).</p>
                </div>
                <div class="pe-header-actions">
                    <a href="{{ route('equipment.list') }}" class="pe-btn-secondary">Anuluj</a>
                    <button type="submit" form="form-dodaj-produkt" class="pe-btn-primary">Dodaj Produkt</button>
                </div>
            </div>

            {{-- Komunikaty flash z sesji (po redirect z POST) --}}
            @if(session('success'))
                <div class="pe-alert success" style="margin:0 40px 20px;">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="pe-alert error" style="margin:0 40px 20px;">
                    <strong>Błąd walidacji:</strong>
                    <ul style="margin:6px 0 0 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="pe-wrapper">
                <div class="pe-main">
                    <div id="pe-alerts"></div>

                    {{-- FORMULARZ - działa jako klasyczna forma HTTP (POST /products) --}}
                    <form id="form-dodaj-produkt"
                          action="{{ url('/products') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="pe-section">
                        @csrf
                        <input type="hidden" name="is_available" id="pe-is-available" value="1">

                        <div class="pe-section-header">
                            <div class="pe-section-title">
                                <div class="pe-section-title-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                </div>
                                Informacje ogólne
                            </div>
                            <div class="pe-status-toggle">
                                <span class="pe-status-toggle-label">Dostępny po dodaniu</span>
                                <span class="pe-status-state" id="pe-status-text">Sprawny</span>
                                <label class="pe-switch">
                                    <input type="checkbox" id="pe-status-switch" checked>
                                    <span class="pe-switch-slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="pe-form-row">
                            <div class="pe-form-group">
                                <label class="pe-form-label" for="title">Nazwa produktu</label>
                                <input class="pe-form-input" id="title" name="title" required maxlength="255"
                                       value="{{ old('title') }}"
                                       placeholder="np. Rower górski MTB">
                            </div>
                            <div class="pe-form-group">
                                <label class="pe-form-label" for="equipment_category_id">Kategoria</label>
                                <select class="pe-form-select" id="equipment_category_id" name="equipment_category_id" required>
                                    <option value="">— Wybierz kategorię —</option>
                                    {{-- Jarosław w GET /products/create zwraca $categories w widoku --}}
                                    @isset($categories)
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('equipment_category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>

                        <div class="pe-form-row">
                            <div class="pe-form-group">
                                <label class="pe-form-label" for="one_day_price">Stawka dobowa (PLN)</label>
                                <input class="pe-form-input" id="one_day_price" name="one_day_price" type="number"
                                       min="0" step="1" required
                                       value="{{ old('one_day_price') }}"
                                       placeholder="np. 45">
                            </div>
                            <div class="pe-form-group">
                                <label class="pe-form-label" for="serial_number">Numer seryjny</label>
                                <input class="pe-form-input" id="serial_number" name="serial_number" required maxlength="255"
                                       value="{{ old('serial_number') }}"
                                       placeholder="np. SN-BIKE-0051">
                            </div>
                        </div>

                        <div class="pe-form-row single">
                            <div class="pe-form-group">
                                <label class="pe-form-label" for="body">Opis produktu</label>
                                <textarea class="pe-form-textarea" id="body" name="body" rows="5"
                                          placeholder="Krótki opis produktu — model, stan, cechy szczególne...">{{ old('body') }}</textarea>
                            </div>
                        </div>

                        <div class="pe-gallery-block">
                            <div class="pe-gallery-head">
                                <span class="pe-gallery-label">Galeria zdjęć</span>
                                <span class="pe-gallery-count"><span id="pe-gallery-count">0</span> / min. 3, max 10 zdjęć</span>
                            </div>
                            <div class="pe-gallery-grid" id="pe-gallery-grid">
                                <label class="pe-gallery-upload">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    <span>Prześlij</span>
                                    <input type="file" id="pe-photos" name="photos[]"
                                           accept="image/jpeg,image/png,image/webp,image/avif"
                                           hidden multiple>
                                </label>
                            </div>
                            <div class="pe-gallery-help">Minimum 3, maksymalnie 10 zdjęć. Każde do 10 MB. Formaty: JPG, PNG, WEBP, AVIF.</div>
                            <div id="pe-photo-error" class="pe-row-error"></div>
                        </div>
                    </form>
                </div>

                <aside class="pe-side">
                    <div class="pe-price-card">
                        <div class="pe-price-title">Podsumowanie</div>
                        <div class="pe-price-label">Stawka dobowa</div>
                        <div class="pe-price-daily" id="pe-price-preview">— zł</div>
                        <div class="pe-price-label">Kategoria</div>
                        <div class="pe-price-income" id="pe-category-preview" style="font-size:14px;">—</div>
                    </div>
                    <div class="pe-alert info" style="margin-top:16px;">
                        <strong>Nowy produkt.</strong><br>
                        Po dodaniu przejdziesz do edycji, gdzie możesz dopisać naprawy i śledzić rezerwacje.
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    'use strict';

    // ==============================================================
    // API Jarosława:
    //   GET  /products/create  - widok Blade z kategoriami ($categories)
    //   POST /products         - klasyczna forma HTTP, redirect 302
    //                            (nie JSON - Laravel z redirect + withErrors)
    // ==============================================================

    const form = document.getElementById('form-dodaj-produkt');
    const switchEl = document.getElementById('pe-status-switch');
    const statusText = document.getElementById('pe-status-text');
    const hiddenAvailable = document.getElementById('pe-is-available');

    const photoInput = document.getElementById('pe-photos');
    const gallery = document.getElementById('pe-gallery-grid');
    const countEl = document.getElementById('pe-gallery-count');
    const photoError = document.getElementById('pe-photo-error');

    const priceInput = document.getElementById('one_day_price');
    const categorySel = document.getElementById('equipment_category_id');
    const pricePreview = document.getElementById('pe-price-preview');
    const categoryPreview = document.getElementById('pe-category-preview');

    // Akumulator wybranych plików
    const selectedPhotos = [];
    const MAX_PHOTOS = 10;

    // ==============================================================
    // Toggle statusu (Sprawny / Serwis)
    // ==============================================================
    switchEl.addEventListener('change', () => {
        const available = switchEl.checked;
        hiddenAvailable.value = available ? '1' : '0';
        statusText.textContent = available ? 'Sprawny' : 'Serwis';
        statusText.classList.toggle('off', !available);
    });

    // ==============================================================
    // Podgląd - cena i kategoria w bocznym panelu
    // ==============================================================
    function updatePricePreview(){
        const v = parseInt(priceInput.value, 10);
        pricePreview.textContent = (isNaN(v) || v <= 0) ? '— zł' : (v.toLocaleString('pl-PL') + ' zł');
    }
    function updateCategoryPreview(){
        const opt = categorySel.options[categorySel.selectedIndex];
        categoryPreview.textContent = opt && opt.value ? opt.textContent : '—';
    }
    priceInput.addEventListener('input', updatePricePreview);
    categorySel.addEventListener('change', updateCategoryPreview);
    updatePricePreview();
    updateCategoryPreview();

    // ==============================================================
    // GALERIA - upload zdjęć (akumulacja + DataTransfer)
    // ==============================================================
    function updateGalleryCount(){
        const count = selectedPhotos.length;
        countEl.textContent = count;

        if(count < 3){
            photoError.textContent = 'Produkt musi mieć co najmniej 3 zdjęcia.';
        } else if(count > MAX_PHOTOS){
            photoError.textContent = `Maksymalnie ${MAX_PHOTOS} zdjęć.`;
        } else {
            photoError.textContent = '';
        }
    }

    function syncInputFiles(){
        const dt = new DataTransfer();
        selectedPhotos.forEach(f => dt.items.add(f));
        photoInput.files = dt.files;
    }

    function renderPreview(){
        gallery.querySelectorAll('.pe-new-photo').forEach(x => x.remove());
        selectedPhotos.forEach((file, idx) => {
            const box = document.createElement('div');
            box.className = 'pe-gallery-thumb pe-new-photo';
            const img = document.createElement('img');
            img.alt = file.name;
            img.src = URL.createObjectURL(file);
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.textContent = '×';
            removeBtn.dataset.removeNew = idx;
            box.appendChild(img);
            box.appendChild(removeBtn);
            gallery.insertBefore(box, gallery.querySelector('.pe-gallery-upload'));
        });
    }

    gallery.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('[data-remove-new]');
        if(!removeBtn) return;
        const idx = parseInt(removeBtn.dataset.removeNew, 10);
        selectedPhotos.splice(idx, 1);
        syncInputFiles();
        renderPreview();
        updateGalleryCount();
    });

    photoInput.addEventListener('change', function(){
        const files = [...this.files];
        if(!files.length){ updateGalleryCount(); return; }

        for(const file of files){
            if(!file.type.startsWith('image/')) continue;
            if(selectedPhotos.length >= MAX_PHOTOS){
                photoError.textContent = `Maksymalnie ${MAX_PHOTOS} zdjęć.`;
                break;
            }
            const isDup = selectedPhotos.some(f => f.name === file.name && f.size === file.size);
            if(!isDup) selectedPhotos.push(file);
        }

        syncInputFiles();
        renderPreview();
        updateGalleryCount();
    });

    // ==============================================================
    // SUBMIT - walidacja frontowa, potem klasyczny POST
    // ==============================================================
    form.addEventListener('submit', function(e){
        if(selectedPhotos.length < 3){
            e.preventDefault();
            photoError.textContent = 'Nie można dodać produktu: wymagane są minimum 3 zdjęcia.';
            gallery.scrollIntoView({behavior:'smooth', block:'center'});
            return;
        }
        if(selectedPhotos.length > MAX_PHOTOS){
            e.preventDefault();
            photoError.textContent = `Maksymalnie ${MAX_PHOTOS} zdjęć.`;
            gallery.scrollIntoView({behavior:'smooth', block:'center'});
            return;
        }
        // Blokada przycisku żeby nie kliknąć dwa razy
        const btn = document.querySelector('button[form="form-dodaj-produkt"]');
        btn.disabled = true;
        btn.textContent = 'Dodaję...';
    });

    updateGalleryCount();
})();
</script>
</body>
</html>
