@extends('layouts.app')

@section('title', $product['name'])

@section('content')

<style>

</style>
<link rel="stylesheet" href="{{ asset('style-prod.css') }}">
<div class="product-page">

    <div class="product-breadcrumb">
        <a href="{{ route('Katalog') }}">Katalog</a>
        <span>›</span>
        <span class="product-breadcrumb-active">{{ $product['name'] }}</span>
    </div>

    {{-- GALLERY FULL WIDTH --}}
    <div class="product-gallery-fullwidth">
        <div class="product-gallery">
            <div class="product-gallery-main">
                <img src="https://placehold.co/800x500/1a1a1a/444?text={{ urlencode($product['name']) }}" alt="{{ $product['name'] }}">
            </div>
            <div class="product-gallery-side">
                <img src="https://placehold.co/400x250/1a1a1a/555?text=Zdj%C4%99cie+2" alt="">
                <div class="product-gallery-side-last">
                    <img src="https://placehold.co/400x250/1a1a1a/555?text=Zdj%C4%99cie+3" alt="">
                    <button class="product-see-all-btn">⊞ ZOBACZ WSZYSTKIE ZDJĘCIA</button>
                </div>
            </div>
        </div>
    </div>

    <div class="product-layout">

        {{-- LEFT --}}
        <div class="product-left">

            <div class="product-badges">
               <span class="product-status-badge product-status-available">
    <span class="product-status-dot"></span>
    Dostępny
</span>
            </div>

            <h1 class="product-title">{{ $product['name'] }}</h1>
            <p class="product-description">{{ $product['description'] }}</p>

 <div class="product-specs-grid">
    <div class="product-specs-header">Specyfikacja techniczna</div>

    <div class="product-spec-item">
        <div class="product-spec-label">Napęd</div>
        <div class="product-spec-value"><strong>SRAM XX1</strong> (Eagle AXS)</div>
    </div>
    <div class="product-spec-item">
        <div class="product-spec-label">Masa operacyjna</div>
        <div class="product-spec-value">9.2 kg</div>
    </div>
    <div class="product-spec-item">
        <div class="product-spec-label">Skok zawieszenia</div>
        <div class="product-spec-value">100 mm</div>
    </div>
    <div class="product-spec-item">
        <div class="product-spec-label">Rozmiar kół</div>
        <div class="product-spec-value">29 cali</div>
    </div>
    <div class="product-spec-item">
        <div class="product-spec-label">Typ ramy</div>
        <div class="product-spec-value">Karbon FACT 12m</div>
    </div>
    <div class="product-spec-item">
        <div class="product-spec-label">Technologia</div>
        <div class="product-spec-value"><a href="#">Brain Intelligent Sus.</a></div>
    </div>
</div>
            <div class="product-section-title">Twoje Rezerwacje</div>
            <div class="product-sub-label">Aktywne rezerwacje</div>
           <div class="product-reservation-card">
    <div class="product-res-col">
        <div class="product-spec-label">Okres</div>
        <div class="product-res-id">02 Lis – 06 Lis 2025</div>
    </div>
    <div class="product-res-col">
        <div class="product-spec-label">Status</div>
        <span class="product-res-status">Potwierdzone</span>
    </div>
    <div class="product-res-col">
        <div class="product-spec-label">Suma opłacona</div>
        <div class="product-res-price">2 170,00 zł</div>
    </div>
    <button class="product-btn-cancel">Anuluj rezerwację</button>
</div>

            <div style="margin-top:28px;">
                <div class="product-sub-label">Historia wynajmu ostatnie 5</div>
                <table class="product-history-table">
                    <thead>
                        <tr>
                            <th>ID Rezerwacji</th><th>Daty</th><th>Suma</th><th>Akcja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SL-5001-01</td>
                            <td>13 Paz 2023 – 16 Paz 2023</td>
                            <td>1 020,00 zł</td>
                            <td><a href="#" class="product-action-link">Zarezerwuj ponownie</a></td>
                        </tr>
                        <tr>
                            <td>SL-0012-44</td>
                            <td>05 Wrz 2023 – 10 Wrz 2023</td>
                            <td>2 450,00 zł</td>
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
                                <div class="product-reviewer-name">Aleksander G. <span class="product-reviewer-tag">(71)</span></div>
                                <div class="product-review-date">15 Paz 2023</div>
                            </div>
                        </div>
                        <div class="product-stars">★★★★★</div>
                        <div class="product-review-text">Niesamowita maszyna. System Brain działa perfekcyjnie, rower sam dostosowuje kiedy zablokować zawieszenie. Bardzo chwytny i łatwy na podjazdach. Czysta przyjemność.</div>
                    </div>
                    <div class="product-review-card">
                        <div class="product-review-header">
                            <div>
                                <div class="product-reviewer-name">Klub Kolarski Góral</div>
                                <div class="product-review-date">26 Wrz 2023</div>
                            </div>
                        </div>
                        <div class="product-stars">★★★★★</div>
                        <div class="product-review-text">Świetny sprzęt na zawody. Serwis przy wynajmie był wydajny i profesjonalny. Rower idealnie utrzymany. Polecamy na trudne techniczne trasy.</div>
                    </div>
                </div>
                <div>
                    <div class="product-rating-summary">
                        <div class="product-add-review-title">Ocena ogólna</div>
                        <div class="product-rating-big">4.9</div>
                        <div class="product-rating-stars">★★★★★</div>
                        <div class="product-rating-count">112 opinii</div>
                        <div class="product-add-review-title">Twoja ocena</div>
                        <div class="product-star-input">☆☆☆☆☆</div>
                        <div class="product-add-review-title">Komentarz</div>
                        <textarea class="product-review-textarea" placeholder="Podziel się swoją opinią..."></textarea>
                        <button class="product-btn-submit">Wyślij opinię</button>
                    </div>
                </div>
            </div>

        </div>

         {{-- RIGHT — BOOKING PANEL --}}
        <div class="product-booking-panel">
 
            <div class="product-booking-price">{{ $product['price'] }} ZŁ <span>/ 24h doba</span></div>
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
                <div class="product-cal-grid" id="product-cal-grid">
                    {{-- wypełniany przez JS --}}
                </div>
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
@push('scripts')
<script>
(function() {
    const PRICE_PER_DAY = {{ $product['price'] }};
    const SERVICE_FEE   = 120;
    const LOGISTICS_FEE = 250;
    const STRIPE_URL    = 'https://stripe.com';

    // --- STATE ---
    let startDate = null;
    let endDate   = null;
    let currentYear  = new Date().getFullYear();
    let currentMonth = new Date().getMonth();

    // --- BLOCKED DATES (przykładowe — docelowo z bazy) ---
    const blockedDates = [
        '2025-12-10', '2025-12-11', '2025-12-12',
    ];

    function dateKey(y, m, d) {
        return `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    }

    function isBlocked(y, m, d) {
        return blockedDates.includes(dateKey(y, m, d));
    }

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

    function daysBetween(a, b) {
        return Math.round((b - a) / (1000 * 60 * 60 * 24));
    }

    function formatDate(date) {
        if (!date) return '—';
        return date.toLocaleDateString('pl-PL', { day:'2-digit', month:'short', year:'numeric' });
    }

    // --- RENDER CALENDAR ---
    function renderCalendar() {
        const grid    = document.getElementById('product-cal-grid');
        const monthEl = document.getElementById('product-calendar-month');
        if (!grid || !monthEl) return;

        const monthNames = ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec',
                            'Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'];
        monthEl.textContent = `${monthNames[currentMonth]} ${currentYear}`;

        grid.innerHTML = '';

        // Nagłówki dni
        ['Pn','Wt','Śr','Cz','Pt','Sb','Nd'].forEach(d => {
            const el = document.createElement('div');
            el.className = 'product-cal-day-label';
            el.textContent = d;
            grid.appendChild(el);
        });

        // Pierwszy dzień tygodnia (0=nd → przesunięcie)
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
            // reset — nowy wybór
            startDate = clicked;
            endDate   = null;
        } else {
            if (clicked <= startDate) {
                startDate = clicked;
                endDate   = null;
            } else {
                endDate = clicked;
            }
        }

        renderCalendar();
        updatePriceBreakdown();
    }

    // --- PRICE BREAKDOWN ---
    function updatePriceBreakdown() {
        const daysEl    = document.getElementById('pb-days-label');
        const daysPrice = document.getElementById('pb-days-price');
        const serviceEl = document.getElementById('pb-service');
        const logistEl  = document.getElementById('pb-logistics');
        const totalEl   = document.getElementById('pb-total');
        const rangeEl   = document.getElementById('product-selected-range');
        const btnEl     = document.getElementById('product-btn-reserve');

        if (!startDate || !endDate) {
            const days = 0;
            if (daysEl)    daysEl.textContent    = 'Wybierz daty';
            if (daysPrice) daysPrice.textContent = '—';
            if (serviceEl) serviceEl.textContent = '—';
            if (logistEl)  logistEl.textContent  = '—';
            if (totalEl)   totalEl.textContent   = '—';
            if (rangeEl)   rangeEl.textContent   = 'Wybierz daty przyjazdu i odjazdu';
            if (btnEl)     btnEl.classList.add('product-btn-disabled');
            return;
        }

        const days     = daysBetween(startDate, endDate);
        const rental   = days * PRICE_PER_DAY;
        const total    = rental + SERVICE_FEE + LOGISTICS_FEE;

        if (daysEl)    daysEl.textContent    = `${days} ${days === 1 ? 'dzień' : 'dni'} wynajmu`;
        if (daysPrice) daysPrice.textContent = `${rental.toFixed(2).replace('.',',')} zł`;
        if (serviceEl) serviceEl.textContent = `${SERVICE_FEE.toFixed(2).replace('.',',')} zł`;
        if (logistEl)  logistEl.textContent  = `${LOGISTICS_FEE.toFixed(2).replace('.',',')} zł`;
        if (totalEl)   totalEl.textContent   = `${total.toFixed(2).replace('.',',')} zł`;
        if (rangeEl)   rangeEl.textContent   = `${formatDate(startDate)} → ${formatDate(endDate)}`;
        if (btnEl)     btnEl.classList.remove('product-btn-disabled');
    }

    // --- NAWIGACJA MIESIĄCA ---
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

    // --- PRZYCISK REZERWACJI ---
    document.getElementById('product-btn-reserve')?.addEventListener('click', () => {
        if (!startDate || !endDate) return;
        window.location.href = STRIPE_URL;
    });

    // --- INIT ---
    renderCalendar();
    updatePriceBreakdown();
})();
</script>
@endpush
@endsection