<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inwentarz – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-list-equipment.css') }}">
    <style>
        .le-date-range{display:grid;grid-template-columns:1fr 1fr;gap:8px}
        .le-input-date{min-width:0}
        .le-page.disabled{opacity:.45;pointer-events:none}
        .le-page.active{background:#075071;border-color:#075071;color:#fff}
        .le-action-btn:disabled{opacity:.5;cursor:not-allowed}
        .le-status-toggle-btn{border:0;background:transparent;cursor:pointer;padding:0}
    </style>
</head>
<body>
<div class="adm-shell">
    @include('partials.admin-sidebar')
    <div class="adm-body">
        @include('partials.admin-topbar')
        <div class="adm-content">
            <div class="le-content">
                <div class="le-breadcrumb"><span>Zarządzanie</span><span>›</span><span class="active">Inwentarz</span></div>
                <h1 class="le-title">Inwentarz</h1>

                <div class="le-filters">
                    <div class="le-filters-row">
                        <div class="le-filter-group">
                            <label class="le-filter-label">Kategoria</label>
                            <select class="le-select" id="le-category-select">
                                <option value="">Cały sprzęt</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="le-filter-group le-price-group">
                            <label class="le-filter-label">Cena za dzień</label>
                            <button type="button" class="le-price-toggle" id="le-price-toggle" aria-haspopup="true" aria-expanded="false">
                                <span id="le-price-label">Dowolna</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="le-price-popover" id="le-price-popover">
                                <div class="le-price-values"><span><span id="le-price-min-val">0</span> zł</span><span><span id="le-price-max-val">2000</span> zł</span></div>
                                <div class="le-price-slider">
                                    <div class="le-price-track"></div><div class="le-price-range" id="le-price-range"></div>
                                    <input type="range" min="0" max="2000" value="0" step="10" id="le-price-min">
                                    <input type="range" min="0" max="2000" value="2000" step="10" id="le-price-max">
                                </div>
                                <div class="le-price-bounds"><span>0 zł</span><span>2000 zł</span></div>
                            </div>
                        </div>
                        <div class="le-filter-group">
                            <label class="le-filter-label">Dostępność w terminie</label>
                            <div class="le-date-range">
                                <input type="date" class="le-input-date" id="le-date-from" aria-label="Data od">
                                <input type="date" class="le-input-date" id="le-date-to" aria-label="Data do">
                            </div>
                        </div>
                        <div class="le-filter-group">
                            <label class="le-filter-label">Konserwacja</label>
                            <div class="le-toggle-group" id="le-status-toggle">
                                <button type="button" class="le-toggle-btn active" data-value="sprawny">Sprawny</button>
                                <button type="button" class="le-toggle-btn" data-value="serwis">Serwis</button>
                            </div>
                        </div>
                        <div class="le-filter-group">
                            <label class="le-filter-label">Sortuj według</label>
                            <select class="le-select" id="le-sort-select">
                                <option value="name_asc">Nazwa (A-Z)</option>
                                <option value="name_desc">Nazwa (Z-A)</option>
                                <option value="price_asc">Cena rosnąco</option>
                                <option value="price_desc">Cena malejąco</option>
                            </select>
                        </div>
                    </div>
                    <div class="le-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="le-search-input" placeholder="Szukaj roweru, sprzętu, numeru seryjnego...">
                    </div>
                </div>

                <div class="le-card">
                    <table class="le-table">
                        <thead><tr><th>Sprzęt i identyfikacja</th><th>Kategoria</th><th>Stawka dobowa</th><th>Status</th><th class="right">Akcje</th></tr></thead>
                        <tbody id="le-tbody"><tr><td colspan="5" style="text-align:center;padding:40px;color:#6b7280">Ładowanie produktów…</td></tr></tbody>
                    </table>
                </div>

                <div class="le-footer">
                    <div class="le-footer-count" id="le-footer-count">Wyświetlono — sztuk sprzętu</div>
                    <div class="le-pagination" id="le-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    'use strict';
    const url='/inwentarz';
    const csrf=document.querySelector('meta[name="csrf-token"]').content;
    const tbody=document.getElementById('le-tbody');
    const footer=document.getElementById('le-footer-count');
    const pager=document.getElementById('le-pagination');
    const search=document.getElementById('le-search-input');
    const category=document.getElementById('le-category-select');
    const sort=document.getElementById('le-sort-select');
    const dateFrom=document.getElementById('le-date-from');
    const dateTo=document.getElementById('le-date-to');
    const priceMin=document.getElementById('le-price-min');
    const priceMax=document.getElementById('le-price-max');
    let currentPage=1, debounce=null;

    function esc(v){return String(v??'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'","&#039;");}
    function badge(status){
        const s=String(status||'').toLowerCase();
        if(s.includes('serw'))return '<span class="le-badge service">Serwis</span>';
        if(s.includes('wyp'))return '<span class="le-badge rented">Wypożyczony</span>';
        return '<span class="le-badge available">Dostępny</span>';
    }
    function row(p){
        const image=p.image?`background:url('${esc(p.image)}') center/cover;`:'';
        return `<tr data-id="${p.id}">
            <td><div class="le-product"><div class="le-product-img" style="${image}"></div><div><div class="le-product-name">${esc(p.title)}</div><div class="le-product-sn"># ${esc(p.sn||'—')}</div></div></div></td>
            <td>${esc(p.category||'—')}</td>
            <td class="le-price">${Number(p.price||0).toLocaleString('pl-PL')} zł <span>/ dzień</span></td>
            <td>${badge(p.status)}</td>
            <td class="right"><div class="le-actions">
                <a href="/produkt/${p.id}/edytuj" class="le-action-btn" aria-label="Edytuj" title="Edytuj">✎</a>
                <a href="/produkt/${p.id}" class="le-action-btn" aria-label="Podgląd" title="Podgląd">⌕</a>
                <button type="button" class="le-action-btn danger le-delete" data-id="${p.id}" aria-label="Usuń" title="Usuń">×</button>
            </div></td>
        </tr>`;
    }

    function params(page){
        const p=new URLSearchParams();
        if(search.value.trim())p.set('search',search.value.trim());
        if(category.value)p.set('category',category.value);
        if(sort.value)p.set('sort',sort.value);
        if(priceMin.value!=='0')p.set('price_min',priceMin.value);
        if(priceMax.value!=='2000')p.set('price_max',priceMax.value);
        if(dateFrom.value)p.set('date_from',dateFrom.value);
        if(dateTo.value)p.set('date_to',dateTo.value);
        p.set('status',document.querySelector('#le-status-toggle .le-toggle-btn.active').dataset.value);
        p.set('page',page);
        p.set('per_page',10);
        return p;
    }

    async function load(page=1){
        currentPage=page;
        tbody.innerHTML='<tr><td colspan="5" style="text-align:center;padding:40px;color:#6b7280">Ładowanie produktów…</td></tr>';
        try{
            const r=await fetch(`${url}?${params(page)}`,{headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},credentials:'same-origin'});
            const data=await r.json();
            if(!r.ok)throw new Error(data.message||'Błąd pobierania');
            tbody.innerHTML=data.data.length?data.data.map(row).join(''):'<tr><td colspan="5" style="text-align:center;padding:40px;color:#6b7280">Brak produktów spełniających kryteria.</td></tr>';
            const m=data.meta;
            footer.innerHTML=`Wyświetlono <strong>${m.from??0}–${m.to??0}</strong> z <strong>${m.total}</strong> sztuk sprzętu`;
            renderPager(m);
        }catch(e){
            console.error(e);
            tbody.innerHTML='<tr><td colspan="5" style="text-align:center;padding:40px;color:#dc2626">Nie udało się pobrać produktów.</td></tr>';
            pager.innerHTML='';
        }
    }

    function renderPager(m){
        if(m.last_page<=1){pager.innerHTML='';return;}
        const items=[];
        items.push({page:1,label:'«',disabled:m.current_page===1});
        items.push({page:m.current_page-1,label:'‹',disabled:m.current_page===1});
        const start=Math.max(1,m.current_page-2), end=Math.min(m.last_page,m.current_page+2);
        for(let i=start;i<=end;i++)items.push({page:i,label:String(i),active:i===m.current_page});
        items.push({page:m.current_page+1,label:'›',disabled:m.current_page===m.last_page});
        items.push({page:m.last_page,label:'»',disabled:m.current_page===m.last_page});
        pager.innerHTML=items.map(x=>`<a href="#" class="le-page ${x.active?'active':''} ${x.disabled?'disabled':''}" data-page="${x.page}">${x.label}</a>`).join('');
    }

    pager.addEventListener('click',e=>{
        const a=e.target.closest('[data-page]');if(!a||a.classList.contains('disabled'))return;
        e.preventDefault();load(Number(a.dataset.page));
    });

    tbody.addEventListener('click',async e=>{
        const btn=e.target.closest('.le-delete');if(!btn)return;
        const id=btn.dataset.id;
        const name=btn.closest('tr').querySelector('.le-product-name')?.textContent||'produkt';
        const ok=confirm(`UWAGA — USUNIĘCIE PRODUKTU\n\nProdukt „${name}” zostanie trwale wyłączony z inwentarza. Tej operacji nie można cofnąć z poziomu panelu. Historia rezerwacji i napraw pozostanie zachowana.\n\nCzy na pewno chcesz usunąć produkt?`);
        if(!ok)return;
        btn.disabled=true;
        try{
            const r=await fetch(`/produkt/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin'});
            const data=await r.json();
            if(!r.ok)throw new Error(data.message||'Nie udało się usunąć produktu.');
            load(currentPage);
        }catch(err){alert(err.message);btn.disabled=false;}
    });

    function schedule(){clearTimeout(debounce);debounce=setTimeout(()=>load(1),350);}
    search.addEventListener('input',schedule);
    [category,sort,dateFrom,dateTo].forEach(x=>x.addEventListener('change',()=>load(1)));

    document.querySelectorAll('#le-status-toggle .le-toggle-btn').forEach(btn=>{
        btn.addEventListener('click',()=>{
            document.querySelectorAll('#le-status-toggle .le-toggle-btn').forEach(x=>x.classList.remove('active'));
            btn.classList.add('active');load(1);
        });
    });

    const toggle=document.getElementById('le-price-toggle'), pop=document.getElementById('le-price-popover');
    toggle.addEventListener('click',e=>{e.stopPropagation();pop.classList.toggle('open');toggle.setAttribute('aria-expanded',pop.classList.contains('open')?'true':'false');});
    document.addEventListener('click',e=>{if(pop.classList.contains('open')&&!pop.contains(e.target)&&!toggle.contains(e.target)){pop.classList.remove('open');toggle.setAttribute('aria-expanded','false');}});
    function priceUI(){
        let lo=+priceMin.value,hi=+priceMax.value;
        if(lo>=hi){if(document.activeElement===priceMin)lo=hi-10;else hi=lo+10;priceMin.value=lo;priceMax.value=hi;}
        document.getElementById('le-price-min-val').textContent=lo;
        document.getElementById('le-price-max-val').textContent=hi;
        document.getElementById('le-price-label').textContent=(lo===0&&hi===2000)?'Dowolna':`${lo} - ${hi} zł`;
        document.getElementById('le-price-range').style.left=(lo/2000*100)+'%';
        document.getElementById('le-price-range').style.right=(100-hi/2000*100)+'%';
    }
    priceMin.addEventListener('input',priceUI);priceMax.addEventListener('input',priceUI);
    priceMin.addEventListener('change',()=>load(1));priceMax.addEventListener('change',()=>load(1));
    priceUI();load(1);
})();
</script>
</body>
</html>
