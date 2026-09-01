<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->title }} – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-product-edit.css') }}">
    <style>
        .pe-alert{padding:12px 16px;border-radius:8px;margin-bottom:18px;font-size:13px}
        .pe-alert.success{background:#dcfce7;color:#166534}
        .pe-alert.error{background:#fee2e2;color:#991b1b}
        .pe-gallery-thumb{position:relative}
        .pe-gallery-thumb button{position:absolute;right:4px;top:4px;width:24px;height:24px;border:0;border-radius:50%;background:#dc2626;color:#fff;cursor:pointer;font-weight:700}
        .pe-gallery-thumb.removed{opacity:.35;filter:grayscale(1)}
        .pe-gallery-thumb.removed::after{content:'USUNIĘTE';position:absolute;left:4px;bottom:4px;background:#dc2626;color:#fff;font-size:8px;padding:3px 5px;border-radius:3px}
        .pe-gallery-help{font-size:11px;color:#6b7280;margin-top:8px}
        .pe-empty{padding:24px;text-align:center;color:#9aa5ad;font-size:13px}
        .pe-delete-repair{border:0;background:transparent;color:#dc2626;cursor:pointer;font-size:12px}
        .pe-row-error{font-size:11px;color:#dc2626;margin-top:4px}
        .pe-status-state.service{color:#6366f1}
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
                        <span class="active">Edycja produktu</span>
                    </div>
                    <h1>
                        {{ $product->title }}
                        <span class="pe-title-badge {{ $product->is_available ? '' : 'unavailable' }}" id="pe-title-status">
                            {{ $product->getStatus() }}
                        </span>
                    </h1>
                    <p class="pe-serial"># Nr seryjny: {{ $product->serial_number }}</p>
                </div>
                <div class="pe-header-actions">
                    <a href="{{ route('equipment.list') }}" class="pe-btn-secondary">Anuluj</a>
                    <button type="submit" form="form-edycja-produktu" class="pe-btn-primary">Aktualizuj Produkt</button>
                </div>
            </div>

            <div class="pe-wrapper">
                <div class="pe-main">
                    @if(session('success'))
                        <div class="pe-alert success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="pe-alert error">
                            <strong>Nie udało się zapisać zmian.</strong>
                            <ul style="margin:6px 0 0 18px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form id="form-edycja-produktu" method="POST" action="{{ route('product.update', $product->id) }}" class="pe-section" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_available" id="pe-is-available" value="{{ $product->is_available ? 1 : 0 }}">

                        <div class="pe-section-header">
                            <div class="pe-section-title">
                                <div class="pe-section-title-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                </div>
                                Informacje ogólne
                            </div>
                            <div class="pe-status-toggle">
                                <span class="pe-status-toggle-label">Przełącz status</span>
                                <span class="pe-status-state {{ $product->is_available ? '' : 'off' }}" id="pe-status-text">{{ $product->is_available ? 'Sprawny' : 'Serwis' }}</span>
                                <label class="pe-switch">
                                    <input type="checkbox" id="pe-status-switch" {{ $product->is_available ? 'checked' : '' }}>
                                    <span class="pe-switch-slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="pe-form-row">
                            <div class="pe-form-group">
                                <label class="pe-form-label" for="title">Nazwa produktu</label>
                                <input class="pe-form-input" id="title" name="title" value="{{ old('title', $product->title) }}" required maxlength="255">
                            </div>
                            <div class="pe-form-group">
                                <label class="pe-form-label" for="equipment_category_id">Kategoria</label>
                                <select class="pe-form-select" id="equipment_category_id" name="equipment_category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (int)old('equipment_category_id', $product->equipment_category_id) === (int)$category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pe-form-row">
                            <div class="pe-form-group">
                                <label class="pe-form-label" for="one_day_price">Stawka dobowa (PLN)</label>
                                <input class="pe-form-input" id="one_day_price" name="one_day_price" type="number" min="0" step="1" value="{{ old('one_day_price', $product->one_day_price) }}" required>
                            </div>
                            <div class="pe-form-group">
                                <label class="pe-form-label">Numer seryjny</label>
                                <div class="pe-form-readonly">{{ $product->serial_number }}</div>
                            </div>
                        </div>

                        <div class="pe-form-row single">
                            <div class="pe-form-group">
                                <label class="pe-form-label" for="body">Opis produktu</label>
                                <textarea class="pe-form-textarea" id="body" name="body" rows="5">{{ old('body', $product->body) }}</textarea>
                            </div>
                        </div>

                        <div class="pe-gallery-block">
                            <div class="pe-gallery-head">
                                <span class="pe-gallery-label">Galeria zdjęć</span>
                                <span class="pe-gallery-count"><span id="pe-gallery-count">{{ $images->count() }}</span> / min. 3 zdjęcia</span>
                            </div>
                            <div class="pe-gallery-grid" id="pe-gallery-grid">
                                @foreach($images as $image)
                                    <div class="pe-gallery-thumb" data-image="{{ $image['name'] }}">
                                        <img src="{{ $image['url'] }}" alt="Zdjęcie produktu {{ $loop->iteration }}">
                                        <button type="button" title="Usuń zdjęcie" data-remove-image="{{ $image['name'] }}">×</button>
                                    </div>
                                @endforeach
                                <label class="pe-gallery-upload">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    <span>Prześlij</span>
                                    <input type="file" id="pe-photos" name="photos[]" accept="image/jpeg,image/png,image/webp,image/avif" hidden multiple>
                                </label>
                            </div>
                            <div class="pe-gallery-help">Minimum 3 zdjęcia. Pierwsze zapisane zdjęcie tworzy miniaturę 370×240, pozostałe są przygotowywane do 680×420. Proporcje obrazu są zachowywane.</div>
                            <div id="pe-photo-error" class="pe-row-error"></div>
                        </div>
                    </form>

                    <section class="pe-section">
                        <div class="pe-section-header">
                            <div class="pe-section-title">
                                <div class="pe-section-title-icon-box">🔧</div>
                                Konserwacja i Naprawy
                            </div>
                        </div>

                        <div class="pe-maint-section-label">Zgłoś nową naprawę</div>
                        <div class="pe-maint-form">
                            <div class="pe-form-group">
                                <label class="pe-form-label">Opis</label>
                                <input type="text" id="repair-description" class="pe-form-input" placeholder="Opis usterki...">
                            </div>
                            <div class="pe-form-group">
                                <label class="pe-form-label">Serwisant</label>
                                <input type="text" id="repair-serviceman" class="pe-form-input" maxlength="255" placeholder="Imię i nazwisko serwisanta">
                            </div>
                            <div class="pe-form-group">
                                <label class="pe-form-label">Koszt</label>
                                <input type="number" id="repair-cost" class="pe-form-input" min="0" step="1" placeholder="PLN">
                            </div>
                            <div class="pe-form-group">
                                <label class="pe-form-label">Data</label>
                                <input type="date" id="repair-date" class="pe-form-input" value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <button type="button" class="pe-maint-add-btn" id="pe-repair-add">Dodaj</button>
                        </div>
                        <div id="pe-repair-error" class="pe-row-error"></div>

                        <table class="pe-table" style="margin-top:20px;">
                            <thead><tr><th>Data</th><th>Opis</th><th>Technik</th><th class="right">Koszt</th><th class="right"></th></tr></thead>
                            <tbody id="pe-repairs-body"><tr><td colspan="5" class="pe-empty">Ładowanie…</td></tr></tbody>
                        </table>
                    </section>

                    <section class="pe-section">
                        <div class="pe-section-header">
                            <div class="pe-section-title">
                                <div class="pe-section-title-icon-box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                Historia Rezerwacji
                            </div>
                        </div>
                        <table class="pe-table">
                            <thead><tr><th>Użytkownik</th><th>Okres wynajmu</th><th>Status</th><th class="right">Przychód</th></tr></thead>
                            <tbody id="pe-reservations-body"><tr><td colspan="4" class="pe-empty">Ładowanie…</td></tr></tbody>
                        </table>
                    </section>
                </div>

                <aside class="pe-side">
                    <div class="pe-price-card">
                        <div class="pe-price-title">Model Cennikowy</div>
                        <div class="pe-price-label">Stawka dobowa</div>
                        <div class="pe-price-daily">{{ number_format($product->one_day_price, 0, ',', ' ') }} zł</div>
                        <div class="pe-price-label">Całkowity przychód</div>
                        <div class="pe-price-income">{{ number_format($product->total_income ?? 0, 0, ',', ' ') }} zł</div>
                    </div>
                    <div class="pe-danger-card">
                        <div class="pe-danger-title">Strefa zagrożenia</div>
                        <div class="pe-danger-text"><strong>Uwaga: operacja jest nieodwracalna.</strong><br>Produkt zostanie trwale wyłączony z inwentarza (archiwizacja), a operacji nie można cofnąć z poziomu panelu. Rezerwacje i historia pozostaną zachowane.</div>
                        <button type="button" class="pe-danger-btn" id="pe-delete-btn">Usuń z inwentarza</button>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    'use strict';
    const id = @json($product->id);
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const headers = {'X-CSRF-TOKEN': csrf, 'Accept':'application/json', 'Content-Type':'application/json'};
    const form = document.getElementById('form-edycja-produktu');
    const switchEl = document.getElementById('pe-status-switch');
    const statusText = document.getElementById('pe-status-text');
    const hiddenAvailable = document.getElementById('pe-is-available');
    const titleBadge = document.getElementById('pe-title-status');
    const photoInput = document.getElementById('pe-photos');
    const gallery = document.getElementById('pe-gallery-grid');
    const countEl = document.getElementById('pe-gallery-count');
    const photoError = document.getElementById('pe-photo-error');
    const removed = new Set();

    let selectedPhotos = [];

    function escapeHtml(s){return String(s ?? '').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'","&#039;");}
    function formatDate(v){ if(!v) return '—'; const d=new Date(v); return Number.isNaN(d.getTime()) ? escapeHtml(v) : d.toLocaleDateString('pl-PL'); }

    function updateGalleryCount(){
        const existing = [...gallery.querySelectorAll('.pe-gallery-thumb:not(.removed):not(.pe-new-photo)')].length;
        const added = selectedPhotos.length;
        countEl.textContent = existing + added;
        photoError.textContent = (existing + added < 3) ? 'Produkt musi mieć co najmniej 3 zdjęcia.' : '';
    }

    function syncPhotoInput(){
        const transfer = new DataTransfer();
        selectedPhotos.forEach(file => transfer.items.add(file));
        photoInput.files = transfer.files;
    }

    function photoKey(file){
        return [file.name, file.size, file.lastModified, file.type].join('|');
    }

    function renderNewPhotos(){
        gallery.querySelectorAll('.pe-new-photo').forEach(x => {
            const img = x.querySelector('img');
            if(img && img.dataset.objectUrl) URL.revokeObjectURL(img.dataset.objectUrl);
            x.remove();
        });

        selectedPhotos.forEach((file, index) => {
            const box=document.createElement('div');
            box.className='pe-gallery-thumb pe-new-photo';
            box.dataset.photoIndex=String(index);

            const img=document.createElement('img');
            img.alt=file.name;
            img.src=URL.createObjectURL(file);
            img.dataset.objectUrl=img.src;
            box.appendChild(img);

            const btn=document.createElement('button');
            btn.type='button';
            btn.title='Usuń nowe zdjęcie';
            btn.textContent='×';
            btn.dataset.removeNewPhoto=String(index);
            box.appendChild(btn);

            gallery.insertBefore(box,gallery.querySelector('.pe-gallery-upload'));
        });
    }

    gallery.addEventListener('click', function(e){
        const newBtn=e.target.closest('[data-remove-new-photo]');
        if(newBtn){
            const index=Number(newBtn.dataset.removeNewPhoto);
            if(Number.isInteger(index) && index >= 0 && index < selectedPhotos.length){
                selectedPhotos.splice(index,1);
                syncPhotoInput();
                renderNewPhotos();
                updateGalleryCount();
            }
            return;
        }

        const btn=e.target.closest('[data-remove-image]');
        if(!btn) return;
        const name=btn.dataset.removeImage;
        removed.add(name);
        const item=btn.closest('.pe-gallery-thumb');
        item.classList.add('removed');
        btn.textContent='✓';
        btn.disabled=true;
        const input=document.createElement('input');
        input.type='hidden'; input.name='remove_photos[]'; input.value=name;
        form.appendChild(input);
        updateGalleryCount();
    });

    photoInput.addEventListener('change', function(){
        const files=[...this.files].filter(file => file.type.startsWith('image/'));
        if(!files.length){
            syncPhotoInput();
            updateGalleryCount();
            return;
        }

        const existingKeys=new Set(selectedPhotos.map(photoKey));
        files.forEach(file=>{
            const key=photoKey(file);
            if(!existingKeys.has(key)){
                selectedPhotos.push(file);
                existingKeys.add(key);
            }
        });

        syncPhotoInput();
        renderNewPhotos();
        updateGalleryCount();

        this.value='';
    });

    switchEl.addEventListener('change', async function(){
        const available=this.checked;
        switchEl.disabled=true;
        try{
            const r=await fetch(`/produkt/${id}/status`,{
                method:'PATCH',headers,
                body:JSON.stringify({is_available:available})
            });
            const data=await r.json();
            if(!r.ok) throw new Error(data.message||'Nie udało się zmienić statusu.');
            hiddenAvailable.value=available?'1':'0';
            statusText.textContent=available?'Sprawny':'Serwis';
            statusText.classList.toggle('off',!available);
            statusText.classList.toggle('service',!available);
            titleBadge.textContent=data.status || (available?'Sprawny':'Serwis');
            titleBadge.classList.toggle('unavailable',!available);
        }catch(e){
            switchEl.checked=!available;
            alert(e.message);
        }finally{switchEl.disabled=false;}
    });

    form.addEventListener('submit', function(e){
        // Upewniamy się, że formularz zawsze wysyła aktualny zestaw nowych zdjęć.
        syncPhotoInput();
        const existing=[...gallery.querySelectorAll('.pe-gallery-thumb:not(.removed):not(.pe-new-photo)')].length;
        if(existing + selectedPhotos.length < 3){
            e.preventDefault();
            photoError.textContent='Nie można zapisać produktu: wymagane są minimum 3 zdjęcia.';
            gallery.scrollIntoView({behavior:'smooth',block:'center'});
        }
    });

    async function loadRepairs(){
        const body=document.getElementById('pe-repairs-body');
        try{
            const r=await fetch(`/produkt/${id}/naprawy`,{headers:{'Accept':'application/json'}});
            const data=await r.json();
            if(!r.ok) throw new Error(data.message||'Błąd');
            body.innerHTML=data.data.length ? data.data.map(x=>`
                <tr>
                    <td>${formatDate(x.createdAt)}</td>
                    <td>${escapeHtml(x.description)}</td>
                    <td>${escapeHtml(x.serviceman_name || '—')}</td>
                    <td class="right">${Number(x.repairCost||0).toLocaleString('pl-PL')} zł</td>
                    <td class="right"><button type="button" class="pe-delete-repair" data-repair-id="${x.id}">Usuń</button></td>
                </tr>`).join('') : '<tr><td colspan="5" class="pe-empty">Brak wpisów napraw.</td></tr>';
        }catch(e){body.innerHTML=`<tr><td colspan="5" class="pe-empty">Nie udało się pobrać napraw.</td></tr>`;}
    }

    document.getElementById('pe-repair-add').addEventListener('click',async()=>{
        const desc=document.getElementById('repair-description').value.trim();
        const serviceman=document.getElementById('repair-serviceman').value.trim();
        const cost=document.getElementById('repair-cost').value;
        const date=document.getElementById('repair-date').value;
        const err=document.getElementById('pe-repair-error');
        err.textContent='';
        if(!desc || !serviceman || cost==='' || !date){err.textContent='Uzupełnij opis, serwisanta, koszt i datę.';return;}
        try{
            const r=await fetch(`/produkt/${id}/naprawy`,{method:'POST',headers,body:JSON.stringify({description:desc,serviceman_name:serviceman,repairCost:Number(cost),date})});
            const data=await r.json();
            if(!r.ok) throw new Error(data.message||'Nie udało się dodać naprawy.');
            document.getElementById('repair-description').value='';
            document.getElementById('repair-serviceman').value='';
            document.getElementById('repair-cost').value='';
            await loadRepairs();
        }catch(e){err.textContent=e.message;}
    });

    document.getElementById('pe-repairs-body').addEventListener('click',async(e)=>{
        const btn=e.target.closest('[data-repair-id]'); if(!btn)return;
        if(!confirm('Czy na pewno usunąć ten wpis naprawy? Operacji nie można cofnąć.'))return;
        const r=await fetch(`/produkt/${id}/naprawy/${btn.dataset.repairId}`,{method:'DELETE',headers});
        if(r.ok) loadRepairs(); else alert('Nie udało się usunąć wpisu naprawy.');
    });

    async function loadReservations(){
        const body=document.getElementById('pe-reservations-body');
        try{
            const r=await fetch(`/produkt/${id}/rezerwacje`,{headers:{'Accept':'application/json'}});
            const data=await r.json();
            if(!r.ok) throw new Error(data.message||'Błąd');
            body.innerHTML=data.data.length ? data.data.map(x=>{
                const status=(x.statusOfReservation||'').toLowerCase();
                let cls='reserved';
                if(['completed','finished','zakończona','returned','zwrócona'].includes(status))cls='done';
                if(['cancelled','canceled','anulowana'].includes(status))cls='late';
                return `<tr>
                    <td><div class="pe-cell-strong">${escapeHtml(x.userName || 'Nieznany użytkownik')}</div><div class="pe-rez-user-id">ID rezerwacji: ${escapeHtml(x.id)}</div></td>
                    <td>${formatDate(x.startDate)} – ${formatDate(x.endDate)}</td>
                    <td><span class="pe-rez-badge ${cls}">${escapeHtml(x.statusOfReservation)}</span></td>
                    <td class="right pe-cell-price">${Number(x.totalPrice||0).toLocaleString('pl-PL')} zł</td>
                </tr>`;
            }).join('') : '<tr><td colspan="4" class="pe-empty">Brak rezerwacji dla tego produktu.</td></tr>';
        }catch(e){body.innerHTML='<tr><td colspan="4" class="pe-empty">Nie udało się pobrać rezerwacji.</td></tr>';}
    }

    document.getElementById('pe-delete-btn').addEventListener('click',async()=>{
        const confirmed=confirm('UWAGA — USUNIĘCIE PRODUKTU\\n\\nProdukt zostanie trwale wyłączony z inwentarza. Tej operacji nie można cofnąć z poziomu panelu. Historia rezerwacji i napraw pozostanie zachowana.\\n\\nCzy na pewno chcesz kontynuować?');
        if(!confirmed)return;
        try{
            const r=await fetch(`/produkt/${id}`,{method:'DELETE',headers});
            const data=await r.json();
            if(!r.ok) throw new Error(data.message||'Nie udało się usunąć produktu.');
            window.location.href=data.redirect || '{{ route('equipment.list') }}';
        }catch(e){alert(e.message);}
    });

    updateGalleryCount();
    loadRepairs();
    loadReservations();
})();
</script>
</body>
</html>
