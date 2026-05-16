<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->title }} — EquipRent Pro</title>
   <link rel="stylesheet" href="{{ asset('style-head.css') }}">
    <link rel="stylesheet" href="{{ asset('style-foot.css') }}">
    <link rel="stylesheet" href="{{ asset('style-prod.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('E.png') }}">
    <style>
       
    </style>
  
</head>
<body>


@include('partials.header')


<main>
<div class="product-page">

    <div class="product-breadcrumb">
        <a href="{{ route('catalog') }}">Katalog</a>
        <span>›</span>
        <span class="product-breadcrumb-active">{{ $product->name }}</span>
    </div>

    {{-- galeria jest java vvvv --}}
    <div class="product-gallery-fullwidth">
        <div class="product-gallery">
            <div class="product-gallery-main">
                <div class="placeholder animate-pulse" style="width:100%;height:100%;"></div>
            </div>
            <div class="product-gallery-side">
                <div class="placeholder animate-pulse" style="width:100%;height:100%;"></div>
                <div class="product-gallery-side-last">
                    <div class="placeholder animate-pulse" style="width:100%;height:100%;"></div>
                    <button type="button" class="product-see-all-btn" id="gallery-open-btn">⊞ ZOBACZ WSZYSTKIE ZDJĘCIA</button>
                </div>
            </div>
        </div>
    </div>

    <div class="product-layout">

        {{-- LEFT --}}
        <div class="product-left">
            {{-- tymczasowo --}}
            <div class="product-badges">
                @if('dostepny' === 'dostepny')
                    <span class="product-status-badge product-status-available">
                        <span class="product-status-dot"></span>
                        Dostępny
                    </span>
                @elseif($product['status'] === 'wypozyczony')
                    <span class="product-status-badge product-status-rented">
                        <span class="product-status-dot"></span>
                        Wypożyczony
                    </span>
                @else
                    <span class="product-status-badge product-status-service">
                        <span class="product-status-dot"></span>
                        Serwis
                    </span>
                @endif
            </div>

            <h1 class="product-title">
                {{ $product->title }}
            </h1>
            <p class="product-description">
                <span style="width:90%;height:14px;border-radius:3px;display:block;margin-bottom:6px;">{{$product->serialNumber}}</span>
                <span style="width:60%;height:14px;border-radius:3px;display:block;">{{$product->body}}</span>
            </p>

  

            <div class="product-section-title">Twoje Rezerwacje</div>
            <div class="product-sub-label">Aktywne rezerwacje</div>
            <div class="product-reservation-card">
                <div class="product-res-col">
                    <div class="product-spec-label">Okres</div>
                    <div class="product-res-id"><span class="placeholder animate-pulse" style="width:120px;height:16px;border-radius:3px;display:inline-block;"></span></div>
                </div>
                <div class="product-res-col">
                    <div class="product-spec-label">Status</div>
                    <span class="placeholder animate-pulse" style="width:90px;height:24px;border-radius:3px;display:inline-block;"></span>
                </div>
                <div class="product-res-col">
                    <div class="product-spec-label">Suma opłacona</div>
                    <div class="product-res-price"><span class="placeholder animate-pulse" style="width:80px;height:16px;border-radius:3px;display:inline-block;"></span> zł</div>
                </div>
                <button class="product-btn-cancel">Anuluj rezerwację</button>
            </div>

            <div style="margin-top:28px;">
                <div class="product-sub-label">Historia wynajmu — ostatnie 5</div>
                <table class="product-history-table">
                    <thead>
                        <tr>
                            <th>ID Rezerwacji</th><th>Daty</th><th>Suma</th><th>Akcja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="placeholder animate-pulse" style="width:80px;height:14px;border-radius:3px;display:inline-block;"></span></td>
                            <td><span class="placeholder animate-pulse" style="width:140px;height:14px;border-radius:3px;display:inline-block;"></span></td>
                            <td><span class="placeholder animate-pulse" style="width:60px;height:14px;border-radius:3px;display:inline-block;"></span> zł</td>
                            <td><a href="#" class="product-action-link">Zarezerwuj ponownie</a></td>
                        </tr>
                        <tr>
                            <td><span class="placeholder animate-pulse" style="width:80px;height:14px;border-radius:3px;display:inline-block;"></span></td>
                            <td><span class="placeholder animate-pulse" style="width:140px;height:14px;border-radius:3px;display:inline-block;"></span></td>
                            <td><span class="placeholder animate-pulse" style="width:60px;height:14px;border-radius:3px;display:inline-block;"></span> zł</td>
                            <td><a href="#" class="product-action-link">Zarezerwuj ponownie</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="product-section-title">Recenzje i Oceny</div>
            <div class="product-reviews-layout">
                <div>
                    <div class="product-review-card">
                        <div class="product-review-header">
                            <div>
                                <div class="product-reviewer-name"><span class="placeholder animate-pulse" style="width:110px;height:13px;border-radius:3px;display:inline-block;"></span></div>
                                <div class="product-review-date"><span class="placeholder animate-pulse" style="width:70px;height:11px;border-radius:3px;display:inline-block;margin-top:4px;"></span></div>
                            </div>
                        </div>
                        <div class="product-stars">★★★★★</div>
                        <div class="product-review-text">
                            <span class="placeholder animate-pulse" style="width:100%;height:12px;border-radius:3px;display:block;margin-bottom:6px;"></span>
                            <span class="placeholder animate-pulse" style="width:85%;height:12px;border-radius:3px;display:block;margin-bottom:6px;"></span>
                            <span class="placeholder animate-pulse" style="width:60%;height:12px;border-radius:3px;display:block;"></span>
                        </div>
                    </div>
                    <div class="product-review-card">
                        <div class="product-review-header">
                            <div>
                                <div class="product-reviewer-name"><span class="placeholder animate-pulse" style="width:130px;height:13px;border-radius:3px;display:inline-block;"></span></div>
                                <div class="product-review-date"><span class="placeholder animate-pulse" style="width:70px;height:11px;border-radius:3px;display:inline-block;margin-top:4px;"></span></div>
                            </div>
                        </div>
                        <div class="product-stars">★★★★★</div>
                        <div class="product-review-text">
                            <span class="placeholder animate-pulse" style="width:100%;height:12px;border-radius:3px;display:block;margin-bottom:6px;"></span>
                            <span class="placeholder animate-pulse" style="width:90%;height:12px;border-radius:3px;display:block;margin-bottom:6px;"></span>
                            <span class="placeholder animate-pulse" style="width:55%;height:12px;border-radius:3px;display:block;"></span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="product-rating-summary">
                        <div class="product-add-review-title">Ocena ogólna</div>
                        <div class="product-rating-big"><span class="placeholder animate-pulse" style="width:60px;height:52px;border-radius:4px;display:inline-block;"></span></div>
                        <div class="product-rating-stars">★★★★★</div>
                        <div class="product-rating-count"><span class="placeholder animate-pulse" style="width:60px;height:12px;border-radius:3px;display:inline-block;"></span></div>
                        <div class="product-add-review-title">Twoja ocena</div>
                        <div class="product-star-input" id="star-input">☆☆☆☆☆</div>
                        <div class="product-add-review-title">Komentarz</div>
                        <textarea class="product-review-textarea" placeholder="Podziel się swoją opinią..."></textarea>
                        <button class="product-btn-submit">Wyślij opinię</button>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT — BOOKING PANEL --}}
        <div class="product-booking-panel">

            <div class="product-booking-price">
                <span class="placeholder animate-pulse" style="width:80px;height:44px;border-radius:4px;display:inline-block;vertical-align:middle;"></span> ZŁ <span>/ 24h doba</span>
            </div>
            <div class="product-booking-rating">
                <span class="product-stars">★</span>
                <small>4.9 wskaźnik niezawodności</small>
            </div>

            <div class="product-range-display" id="product-selected-range">
                Wybierz daty przyjazdu i odjazdu
            </div>

            <div class="product-calendar-label">Wybierz okres wynajmu</div>

            <div class="product-calendar">
                <div class="product-calendar-header">
                    <button class="product-cal-nav" id="product-cal-prev">‹</button>
                    <span class="product-calendar-month" id="product-calendar-month"></span>
                    <button class="product-cal-nav" id="product-cal-next">›</button>
                </div>
                <div class="product-cal-grid" id="product-cal-grid"></div>
            </div>

            <div class="product-price-breakdown">
                <div class="product-price-row">
                    <span id="pb-days-label">Wybierz daty</span>
                    <span id="pb-days-price">—</span>
                </div>
                <div class="product-price-row">
                    <span>Opłata serwisowa</span>
                    <span id="pb-service">—</span>
                </div>
                <div class="product-price-row">
                    <span>Logistyka (estymacja)</span>
                    <span id="pb-logistics">—</span>
                </div>
                <div class="product-price-row product-total">
                    <span>Suma</span>
                    <span id="pb-total">—</span>
                </div>
            </div>

            <button class="product-btn-reserve product-btn-disabled" id="product-btn-reserve">
                Potwierdź i kontynuuj →
            </button>
            <p class="product-booking-note">
                Bezpieczne zamówienia sportowe, zweryfikowane przez gridchain. Darmowe anulowanie do 48h przed odbiorem.
            </p>

        </div>
    </div>

</div>
</main>

{{-- galeria --}}
<div class="gallery-backdrop" id="gallery-backdrop">
    <div class="gallery-modal">
        <div class="gallery-header">
            <span class="gallery-title">Galeria zdjęć</span>
            <button type="button" class="gallery-close" id="gallery-close-btn" aria-label="Zamknij">✕</button>
        </div>
        <div class="gallery-body">
            <div class="gallery-grid">
                <div class="gallery-thumb animate-pulse" data-idx="1"></div>
                <div class="gallery-thumb animate-pulse" data-idx="2"></div>
                <div class="gallery-thumb animate-pulse" data-idx="3"></div>
                <div class="gallery-thumb animate-pulse" data-idx="4"></div>
                <div class="gallery-thumb animate-pulse" data-idx="5"></div>
                <div class="gallery-thumb animate-pulse" data-idx="6"></div>
            </div>
        </div>

        {{-- Powiększony widok pojedynczego zdjęcia --}}
        <div class="gallery-zoom" id="gallery-zoom">
            <button type="button" class="gallery-zoom-back" id="gallery-zoom-back">← Powrót do galerii</button>
            <div class="gallery-zoom-img animate-pulse" id="gallery-zoom-img"></div>
        </div>
    </div>
</div>



@include('partials.footer')

<script> //kalendarz z przeniesieniem do "stripe" i nizej są giwazdki 
(function() {
   const PRICE_PER_DAY = {{ $product->oneDayPrice }};
    const SERVICE_FEE   = 120;
    const LOGISTICS_FEE = 250;
    const STRIPE_URL    = 'https://stripe.com';

    let startDate = null;
    let endDate   = null;
    let currentYear  = new Date().getFullYear();
    let currentMonth = new Date().getMonth();

    const blockedDates = [
        '2025-12-10', '2025-12-11', '2025-12-12',
    ];

    function dateKey(y, m, d) {
        return `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    }
    function isBlocked(y, m, d) { return blockedDates.includes(dateKey(y, m, d)); }
    function isPast(y, m, d) {
        const today = new Date(); today.setHours(0,0,0,0);
        return new Date(y, m, d) < today;
    }
    function inRange(y, m, d) {
        if (!startDate || !endDate) return false;
        const date = new Date(y, m, d);
        return date > startDate && date < endDate;
    }
    function isStart(y, m, d) {
        if (!startDate) return false;
        return startDate.getFullYear()===y && startDate.getMonth()===m && startDate.getDate()===d;
    }
    function isEnd(y, m, d) {
        if (!endDate) return false;
        return endDate.getFullYear()===y && endDate.getMonth()===m && endDate.getDate()===d;
    }
    function daysBetween(a, b) { return Math.round((b - a) / (1000 * 60 * 60 * 24)); }
    function formatDate(date) {
        if (!date) return '—';
        return date.toLocaleDateString('pl-PL', { day:'2-digit', month:'short', year:'numeric' });
    }

    function renderCalendar() {
        const grid    = document.getElementById('product-cal-grid');
        const monthEl = document.getElementById('product-calendar-month');
        if (!grid || !monthEl) return;

        const monthNames = ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec',
                            'Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'];
        monthEl.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        grid.innerHTML = '';

        ['Pn','Wt','Śr','Cz','Pt','Sb','Nd'].forEach(d => {
            const el = document.createElement('div');
            el.className = 'product-cal-day-label';
            el.textContent = d;
            grid.appendChild(el);
        });

        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const offset   = (firstDay === 0) ? 6 : firstDay - 1;
        for (let i = 0; i < offset; i++) {
            const el = document.createElement('div');
            el.className = 'product-cal-day';
            grid.appendChild(el);
        }

        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        for (let d = 1; d <= daysInMonth; d++) {
            const el = document.createElement('div');
            el.className = 'product-cal-day';
            el.textContent = d;

            if (isPast(currentYear, currentMonth, d)) {
                el.classList.add('product-disabled');
            } else if (isBlocked(currentYear, currentMonth, d)) {
                el.classList.add('product-blocked');
                el.title = 'Termin niedostępny';
            } else {
                if (isStart(currentYear, currentMonth, d)) el.classList.add('product-selected', 'product-range-start');
                if (isEnd(currentYear, currentMonth, d))   el.classList.add('product-selected', 'product-range-end');
                if (inRange(currentYear, currentMonth, d)) el.classList.add('product-in-range');

                const y = currentYear, m = currentMonth, day = d;
                el.addEventListener('click', () => handleDayClick(y, m, day));
            }
            grid.appendChild(el);
        }
    }

    function handleDayClick(y, m, d) {
        const clicked = new Date(y, m, d);
        if (!startDate || (startDate && endDate)) {
            startDate = clicked;
            endDate   = null;
        } else {
            if (clicked <= startDate) { startDate = clicked; endDate = null; }
            else { endDate = clicked; }
        }
        renderCalendar();
        updatePriceBreakdown();
    }

    function updatePriceBreakdown() {
        const daysEl    = document.getElementById('pb-days-label');
        const daysPrice = document.getElementById('pb-days-price');
        const serviceEl = document.getElementById('pb-service');
        const logistEl  = document.getElementById('pb-logistics');
        const totalEl   = document.getElementById('pb-total');
        const rangeEl   = document.getElementById('product-selected-range');
        const btnEl     = document.getElementById('product-btn-reserve');

        if (!startDate || !endDate) {
            if (daysEl)    daysEl.textContent    = 'Wybierz daty';
            if (daysPrice) daysPrice.textContent = '—';
            if (serviceEl) serviceEl.textContent = '—';
            if (logistEl)  logistEl.textContent  = '—';
            if (totalEl)   totalEl.textContent   = '—';
            if (rangeEl)   rangeEl.textContent   = 'Wybierz daty przyjazdu i odjazdu';
            if (btnEl)     btnEl.classList.add('product-btn-disabled');
            return;
        }

        const days   = daysBetween(startDate, endDate);
        const rental = days * PRICE_PER_DAY;
        const total  = rental + SERVICE_FEE + LOGISTICS_FEE;

        if (daysEl)    daysEl.textContent    = `${days} ${days === 1 ? 'dzień' : 'dni'} wynajmu`;
        if (daysPrice) daysPrice.textContent = `${rental.toFixed(2).replace('.',',')} zł`;
        if (serviceEl) serviceEl.textContent = `${SERVICE_FEE.toFixed(2).replace('.',',')} zł`;
        if (logistEl)  logistEl.textContent  = `${LOGISTICS_FEE.toFixed(2).replace('.',',')} zł`;
        if (totalEl)   totalEl.textContent   = `${total.toFixed(2).replace('.',',')} zł`;
        if (rangeEl)   rangeEl.textContent   = `${formatDate(startDate)} → ${formatDate(endDate)}`;
        if (btnEl)     btnEl.classList.remove('product-btn-disabled');
    }

    document.getElementById('product-cal-prev')?.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        renderCalendar();
    });

    document.getElementById('product-cal-next')?.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        renderCalendar();
    });

    document.getElementById('product-btn-reserve')?.addEventListener('click', () => {
        if (!startDate || !endDate) return;
        window.location.href = STRIPE_URL;
    });

  
    const starInput = document.getElementById('star-input');
    if (starInput) {
        let selected = 0;
        starInput.addEventListener('mousemove', (e) => {
            const rect = starInput.getBoundingClientRect();
            const idx = Math.ceil((e.clientX - rect.left) / (rect.width / 5));
            starInput.textContent = '★'.repeat(idx) + '☆'.repeat(5 - idx);
        });
        starInput.addEventListener('mouseleave', () => {
            starInput.textContent = '★'.repeat(selected) + '☆'.repeat(5 - selected);
        });
        starInput.addEventListener('click', (e) => {
            const rect = starInput.getBoundingClientRect();
            selected = Math.ceil((e.clientX - rect.left) / (rect.width / 5));
            starInput.textContent = '★'.repeat(selected) + '☆'.repeat(5 - selected);
        });
    }

    renderCalendar();
    updatePriceBreakdown();
})();
</script>

<script> //galeria po otwoerzeniu 
(function() {
    const backdrop  = document.getElementById('gallery-backdrop');
    const openBtn   = document.getElementById('gallery-open-btn');
    const closeBtn  = document.getElementById('gallery-close-btn');
    const zoom      = document.getElementById('gallery-zoom');
    const zoomImg   = document.getElementById('gallery-zoom-img');
    const zoomBack  = document.getElementById('gallery-zoom-back');
    const thumbs    = document.querySelectorAll('.gallery-thumb');

    if (!backdrop || !openBtn) return;

    function openGallery() {
        backdrop.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeGallery() {
        backdrop.classList.remove('open');
        zoom.classList.remove('open');
        document.body.style.overflow = '';
    }
    function openZoom(idx) { //w tej drugiej
    
        zoomImg.setAttribute('data-current', idx);
        zoom.classList.add('open');
    }
    function closeZoom() {
        zoom.classList.remove('open');
    }

    openBtn.addEventListener('click', openGallery);
    closeBtn.addEventListener('click', closeGallery);
    zoomBack.addEventListener('click', closeZoom);


    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) closeGallery();
    }); //zamykanie po nacisnieciu obok

    // powiekszenie po klik
    thumbs.forEach(t => {
        t.addEventListener('click', () => openZoom(t.dataset.idx));
    });

    
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        if (zoom.classList.contains('open')) closeZoom();
        else if (backdrop.classList.contains('open')) closeGallery();
    });
})();
</script>

</body>
</html>