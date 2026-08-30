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
        <span class="product-breadcrumb-active">{{ $product->title }}</span>
    </div>

    {{-- galeria jest java vvvv --}}
    <div class="product-gallery-fullwidth">
        <div class="product-gallery">
            <div class="product-gallery-main">
                @if($product->getNImageUrl(1,TRUE) === "")
                    <div class="placeholder animate-pulse" style="width:100%;height:100%;"></div>
                @else
                    <img src={{ $product->getNImageUrl(1) }} style="border-radius:0;">
                @endif
            </div>
            <div class="product-gallery-side">
                @if($product->getNImageUrl(2,TRUE) === "")
                    <div class="placeholder animate-pulse"></div>
                @else
                    <img src={{ $product->getNImageUrl(2) }} style="height:207px;border-radius:0;">
                @endif
                <div class="product-gallery-side-last">
                    @if($product->getNImageUrl(3,TRUE) === "")
                        <div class="placeholder animate-pulse" style="width:100%;height:100%;"></div>
                    @else
                        <img src={{ $product->getNImageUrl(3) }} style="height:207px;border-radius:0;">
                    @endif
                    <button type="button" class="product-see-all-btn" id="gallery-open-btn">⊞ ZOBACZ WSZYSTKIE ZDJĘCIA</button>
                </div>
            </div>
        </div>
    </div>

    <div class="product-layout">

        {{-- LEFT --}}
        <div class="product-left">
            <div class="product-badges">
                @php
                    $productStatus = $product->getStatus();
                @endphp
                @if($productStatus === 'Dostępny')
                    <span class="product-status-badge product-status-available">
                        <span class="product-status-dot"></span>
                        Dostępny
                    </span>
                @elseif($productStatus === 'Wypożyczony')
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
                <span style="width:90%;height:14px;border-radius:3px;display:block;margin-bottom:6px;">{{$product->serial_number}}</span>
                <span style="width:60%;height:14px;border-radius:3px;display:block;">{{$product->body}}</span>
            </p>

  

            <div class="product-section-title">Twoje Rezerwacje</div>
            <div class="product-sub-label">Aktywne rezerwacje</div>
            <div id="my-active-reservations">
                {{-- Wypełniane przez JS z GET /api/products/{id}/reservations/my --}}
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
                    <button class="product-btn-cancel" disabled>Anuluj rezerwację</button>
                </div>
            </div>

            <div style="margin-top:28px;">
                <div class="product-sub-label">Historia wynajmu — ostatnie 5</div>
                <table class="product-history-table">
                    <thead>
                        <tr>
                            <th>ID Rezerwacji</th><th>Daty</th><th>Suma</th><th>Akcja</th>
                        </tr>
                    </thead>
                    <tbody id="my-history-reservations">
                        {{-- Wypełniane przez JS --}}
                        <tr>
                            <td><span class="placeholder animate-pulse" style="width:80px;height:14px;border-radius:3px;display:inline-block;"></span></td>
                            <td><span class="placeholder animate-pulse" style="width:140px;height:14px;border-radius:3px;display:inline-block;"></span></td>
                            <td><span class="placeholder animate-pulse" style="width:60px;height:14px;border-radius:3px;display:inline-block;"></span> zł</td>
                            <td>—</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="product-section-title">Recenzje i Oceny</div>
            <div class="product-reviews-layout">
                <div id="product-opinions-list">
                    {{-- Wypełniane przez JS z GET /api/products/{id}/opinions --}}
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
                </div>
                <div>
                    <div class="product-rating-summary">
                        <div class="product-add-review-title">Ocena ogólna</div>
                        <div class="product-rating-big" id="rating-avg">
                            <span class="placeholder animate-pulse" style="width:60px;height:52px;border-radius:4px;display:inline-block;"></span>
                        </div>
                        <div class="product-rating-stars" id="rating-stars">★★★★★</div>
                        <div class="product-rating-count" id="rating-count">
                            <span class="placeholder animate-pulse" style="width:60px;height:12px;border-radius:3px;display:inline-block;"></span>
                        </div>

                        {{-- Formularz opinii - pokazywany tylko jeśli canReview=true --}}
                        <div id="opinion-form-wrapper" style="display:none;">
                            <div class="product-add-review-title">Twoja ocena</div>
                            <div class="product-star-input" id="star-input" data-selected="0">
                                <span data-v="1" style="cursor:pointer;padding:0 3px;">☆</span>
                                <span data-v="2" style="cursor:pointer;padding:0 3px;">☆</span>
                                <span data-v="3" style="cursor:pointer;padding:0 3px;">☆</span>
                                <span data-v="4" style="cursor:pointer;padding:0 3px;">☆</span>
                                <span data-v="5" style="cursor:pointer;padding:0 3px;">☆</span>
                            </div>
                            <div class="product-add-review-title">Komentarz</div>
                            <textarea id="opinion-description" class="product-review-textarea" placeholder="Podziel się swoją opinią..." maxlength="2000"></textarea>
                            <button id="opinion-submit-btn" class="product-btn-submit" type="button">Wyślij opinię</button>
                        </div>

                        {{-- Komunikat gdy nie może dodać opinii --}}
                        <div id="opinion-blocked-message" style="display:none;font-size:12px;color:#6b7280;margin-top:12px;padding:10px 12px;background:#f7f7f8;border-radius:6px;"></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT — BOOKING PANEL --}}
        <div class="product-booking-panel">

            <div class="product-booking-price">
                {{ $product->one_day_price }} ZŁ <span>/ 24h doba</span>
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
                @php
                    $imgUrls = $product->getImagesUrls();
                @endphp
                @if(count($imgUrls) > 0)
                    @foreach ($imgUrls as $index => $imgUrl )
                        <img class="gallery-thumb" 
                         src="{{ $imgUrl }}" data-idx="{{ $index }}">
                    @endforeach
                @else
                    <p style="font-family:Poppins;font-size:14px;color:rgb(75,85,99);">Brak obrazków do wyświetlenia</p>
                @endif
            </div>
        </div>

        {{-- Powiększony widok pojedynczego zdjęcia --}}
        <div class="gallery-zoom" id="gallery-zoom">
            <button type="button" class="gallery-zoom-back" id="gallery-zoom-back">← Powrót do galerii</button>
            <img class="gallery-zoom-img" id="gallery-zoom-img"/>
        </div>
    </div>
</div>



@include('partials.footer')

<script>
(function () {
    'use strict';

    // ==============================================================
    // Konfiguracja
    // ==============================================================
    const PRODUCT_ID    = {{ $product->id }};
    const PRICE_PER_DAY = {{ $product->one_day_price }};
    const SERVICE_FEE   = 120;
    const LOGISTICS_FEE = 250;
    const CSRF          = document.querySelector('meta[name="csrf-token"]').content;

    // ==============================================================
    // Helper: fetch z CSRF i cookie
    // ==============================================================
    function apiGet(url) {
        return fetch(url, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin',
        });
    }
    function apiJson(method, url, body) {
        return fetch(url, {
            method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            credentials: 'same-origin',
            body: body ? JSON.stringify(body) : undefined,
        });
    }

    function extractList(payload) {
        if (!payload) return [];
        if (Array.isArray(payload)) return payload;
        if (Array.isArray(payload.data)) return payload.data;
        return [];
    }

    function escapeHtml(s) {
        if (s === null || s === undefined) return '';
        return String(s)
            .replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;').replaceAll("'", '&#039;');
    }

    function formatDate(iso) {
        if (!iso) return '—';
        const d = new Date(iso);
        if (isNaN(d)) return String(iso);
        return d.toLocaleDateString('pl-PL', { day:'2-digit', month:'2-digit', year:'numeric' });
    }
    function formatMoney(v) {
        if (v === null || v === undefined) return '—';
        const num = Number(v);
        if (isNaN(num)) return String(v);
        return num.toLocaleString('pl-PL') + ' zł';
    }
    function toISO(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth()+1).padStart(2,'0');
        const d = String(date.getDate()).padStart(2,'0');
        return `${y}-${m}-${d}`;
    }

    // ==============================================================
    // KALENDARZ REZERWACJI (blokady z API)
    // GET /api/products/{id}/reservations/booked-dates - Kacper
    // ==============================================================
    let startDate = null;
    let endDate   = null;
    let currentYear  = new Date().getFullYear();
    let currentMonth = new Date().getMonth();
    let blockedDates = [];  // wypełniane fetchem

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
    function formatDatePl(date) {
        if (!date) return '—';
        return date.toLocaleDateString('pl-PL', { day:'2-digit', month:'short', year:'numeric' });
    }
    function rangeContainsBlocked(start, end) {
        const cur = new Date(start);
        while (cur <= end) {
            if (isBlocked(cur.getFullYear(), cur.getMonth(), cur.getDate())) return true;
            cur.setDate(cur.getDate() + 1);
        }
        return false;
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
            if (clicked <= startDate) {
                startDate = clicked;
                endDate = null;
            } else {
                if (rangeContainsBlocked(startDate, clicked)) {
                    alert('Wybrany zakres zawiera termin już zarezerwowany. Wybierz inny okres.');
                    return;
                }
                endDate = clicked;
            }
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
        if (rangeEl)   rangeEl.textContent   = `${formatDatePl(startDate)} → ${formatDatePl(endDate)}`;
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

    // ==============================================================
    // Utworzenie rezerwacji - POST /api/products/{id}/reservations
    // ==============================================================
    async function createReservation() {
        if (!startDate || !endDate) return;

        const btn = document.getElementById('product-btn-reserve');
        btn.classList.add('product-btn-disabled');
        btn.textContent = 'Rezerwuję...';

        try {
            const res = await apiJson('POST', `/api/products/${PRODUCT_ID}/reservations`, {
                startDate: toISO(startDate),
                endDate:   toISO(endDate),
            });

            if (res.status === 201) {
                const data = await res.json().catch(() => ({}));
                if (data.reservationId) {
                    window.location.href = `/platnosc?reservation=${encodeURIComponent(data.reservationId)}`;
                } else {
                    window.location.href = '/rezerwacje';
                }
                return;
            }
            if (res.status === 409) {
                const err = await res.json().catch(() => ({}));
                alert(err.message || 'Ten termin jest już zajęty.');
            } else if (res.status === 422) {
                alert('Nieprawidłowe dane. Sprawdź wybrane daty.');
            } else if (res.status === 404) {
                alert('Produkt nie istnieje.');
            } else {
                alert('Nie udało się utworzyć rezerwacji. Spróbuj później.');
            }
        } catch (e) {
            alert('Błąd sieci przy tworzeniu rezerwacji.');
            console.error(e);
        } finally {
            btn.textContent = 'Potwierdź i kontynuuj →';
            btn.classList.remove('product-btn-disabled');
        }
    }

    document.getElementById('product-btn-reserve')?.addEventListener('click', createReservation);

    // ==============================================================
    // GWIAZDKI DO OCENY OPINII (5 osobnych spanów)
    // ==============================================================
    const starInput = document.getElementById('star-input');
    if (starInput) {
        const stars = starInput.querySelectorAll('span');
        let selected = 0;

        function paint(count) {
            stars.forEach((star, i) => {
                star.textContent = (i < count) ? '★' : '☆';
            });
        }
        stars.forEach(star => {
            const value = parseInt(star.dataset.v, 10);
            star.addEventListener('mouseenter', () => paint(value));
            star.addEventListener('click', () => {
                selected = value;
                starInput.dataset.selected = String(selected);
                paint(selected);
            });
        });
        starInput.addEventListener('mouseleave', () => paint(selected));
    }

    // ==============================================================
    // Wysyłanie opinii - POST /api/products/{id}/opinions
    // ==============================================================
    document.getElementById('opinion-submit-btn')?.addEventListener('click', async () => {
        const scaleValue  = parseInt(starInput?.dataset.selected || '0', 10);
        const description = document.getElementById('opinion-description').value.trim();

        if (scaleValue < 1) { alert('Wybierz ocenę (kliknij gwiazdki).'); return; }
        if (description.length < 3) { alert('Napisz choć kilka słów opinii.'); return; }

        try {
            const res = await apiJson('POST', `/api/products/${PRODUCT_ID}/opinions`, {
                scaleValue, description,
            });
            if (res.status === 201) {
                alert('Dziękujemy za opinię!');
                // Odśwież listę opinii i summary
                loadOpinions();
                loadOpinionsSummary();
                loadCanReview();
                document.getElementById('opinion-description').value = '';
                if (starInput) { starInput.dataset.selected = '0'; starInput.querySelectorAll('span').forEach(s => s.textContent = '☆'); }
                return;
            }
            if (res.status === 403) { alert('Opinię możesz dodać dopiero po zakończonym wypożyczeniu.'); return; }
            if (res.status === 409) { alert('Dodałeś już opinię dla tego produktu.'); return; }
            if (res.status === 422) { alert('Nieprawidłowe dane opinii.'); return; }
            alert('Nie udało się dodać opinii.');
        } catch (e) {
            alert('Błąd sieci przy dodawaniu opinii.');
            console.error(e);
        }
    });

    // ==============================================================
    // FETCHE - załadowanie danych z API
    // ==============================================================

    // Blokady kalendarza
    async function loadBookedDates() {
        try {
            const res = await apiGet(`/api/products/${PRODUCT_ID}/reservations/booked-dates`);
            if (!res.ok) return;
            const payload = await res.json();
            const list = extractList(payload);
            const set = new Set();
            for (const r of list) {
                const s = new Date(r.startDate);
                const e = new Date(r.endDate);
                for (let d = new Date(s); d <= e; d.setDate(d.getDate()+1)) {
                    set.add(dateKey(d.getFullYear(), d.getMonth(), d.getDate()));
                }
            }
            blockedDates = Array.from(set);
            renderCalendar();
        } catch (e) { console.warn('booked-dates:', e); }
    }

    // Moje rezerwacje tego produktu
    async function loadMyReservations() {
        const activeEl = document.getElementById('my-active-reservations');
        const historyEl = document.getElementById('my-history-reservations');

        try {
            const res = await apiGet(`/api/products/${PRODUCT_ID}/reservations/my`);
            if (!res.ok) {
                activeEl.innerHTML = '<p style="color:#6b7280;font-size:13px;">Brak aktywnych rezerwacji.</p>';
                historyEl.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#6b7280;">Brak historii.</td></tr>';
                return;
            }
            const payload = await res.json();
            const list = extractList(payload);

            const today = new Date(); today.setHours(0,0,0,0);
            const isActive = (r) => ['active','pending','confirmed','awaiting_payment'].includes(r.statusOfReservation) && new Date(r.endDate) >= today;

            const active = list.filter(isActive);
            const history = list.filter(r => !isActive(r)).slice(0, 5);

            // Aktywne
            if (active.length === 0) {
                activeEl.innerHTML = '<p style="color:#6b7280;font-size:13px;">Brak aktywnych rezerwacji tego produktu.</p>';
            } else {
                activeEl.innerHTML = active.map(r => {
                    const awaitingPayment = r.statusOfReservation === 'awaiting_payment';
                    return `
                    <div class="product-reservation-card" data-reservation-id="${escapeHtml(r.id)}">
                        <div class="product-res-col">
                            <div class="product-spec-label">Okres</div>
                            <div class="product-res-id">${formatDate(r.startDate)} → ${formatDate(r.endDate)}</div>
                        </div>
                        <div class="product-res-col">
                            <div class="product-spec-label">Status</div>
                            <span>${awaitingPayment ? 'Oczekuje na płatność' : escapeHtml(r.statusOfReservation)}</span>
                        </div>
                        <div class="product-res-col">
                            <div class="product-spec-label">Suma</div>
                            <div class="product-res-price">${formatMoney(r.totalPrice)}</div>
                        </div>
                        ${awaitingPayment
                            ? `<a class="product-btn-cancel" style="text-decoration:none;text-align:center;background:#075071;" href="/platnosc?reservation=${encodeURIComponent(r.id)}">Dokończ płatność</a>`
                            : ''}
                        <button class="product-btn-cancel" type="button" data-action="cancel" data-id="${escapeHtml(r.id)}">Anuluj rezerwację</button>
                    </div>
                `; }).join('');
            }

            // Historia
            if (history.length === 0) {
                historyEl.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#6b7280;">Brak historii wynajmu.</td></tr>';
            } else {
                historyEl.innerHTML = history.map(r => `
                    <tr>
                        <td>#${escapeHtml(r.id)}</td>
                        <td>${formatDate(r.startDate)} — ${formatDate(r.endDate)}</td>
                        <td>${formatMoney(r.totalPrice)}</td>
                        <td><span style="color:#6b7280;">${escapeHtml(r.statusOfReservation)}</span></td>
                    </tr>
                `).join('');
            }
        } catch (e) {
            console.warn('reservations/my:', e);
            activeEl.innerHTML = '<p style="color:#6b7280;font-size:13px;">Zaloguj się aby zobaczyć swoje rezerwacje.</p>';
        }
    }

    // Anulowanie rezerwacji (delegacja - działa na przycisku "Anuluj rezerwację" w karcie)
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action="cancel"]');
        if (!btn) return;

        if (!confirm('Czy na pewno chcesz anulować tę rezerwację?')) return;

        const id = btn.dataset.id;
        try {
            const res = await apiJson('PATCH', `/api/reservations/${id}/cancel`);
            if (res.ok) {
                alert('Rezerwacja anulowana.');
                loadMyReservations();
                loadBookedDates(); // termin się zwolnił
            } else if (res.status === 404) {
                alert('Rezerwacja nie istnieje lub nie należy do Ciebie.');
            } else if (res.status === 409) {
                alert('Ta rezerwacja jest już anulowana lub zakończona.');
            } else {
                alert('Nie udało się anulować.');
            }
        } catch (err) {
            alert('Błąd sieci.');
            console.error(err);
        }
    });

    // Lista opinii
    async function loadOpinions() {
        const container = document.getElementById('product-opinions-list');
        try {
            const res = await apiGet(`/api/products/${PRODUCT_ID}/opinions`);
            if (!res.ok) return;
            const payload = await res.json();
            const list = extractList(payload);

            if (list.length === 0) {
                container.innerHTML = '<div class="product-review-card"><p style="color:#6b7280;font-size:13px;text-align:center;">Brak opinii. Bądź pierwszy!</p></div>';
                return;
            }

            container.innerHTML = list.map(op => `
                <div class="product-review-card">
                    <div class="product-review-header">
                        <div>
                            <div class="product-reviewer-name">${escapeHtml(op.userName || 'Klient')}</div>
                            <div class="product-review-date">${formatDate(op.createdAt)}</div>
                        </div>
                    </div>
                    <div class="product-stars">${'★'.repeat(op.scaleValue || 0)}${'☆'.repeat(5 - (op.scaleValue || 0))}</div>
                    <div class="product-review-text">${escapeHtml(op.description || '')}</div>
                </div>
            `).join('');
        } catch (e) { console.warn('opinions:', e); }
    }

    // Ocena ogólna (summary)
    async function loadOpinionsSummary() {
        try {
            const res = await apiGet(`/api/products/${PRODUCT_ID}/opinions/summary`);
            if (!res.ok) return;
            const payload = await res.json();
            const data = payload.data || payload;

            const avg = Number(data.averageRating ?? 0);
            const count = Number(data.opinionsCount ?? 0);
            const rounded = Math.round(avg);

            document.getElementById('rating-avg').textContent = avg.toFixed(1);
            document.getElementById('rating-stars').textContent = '★'.repeat(rounded) + '☆'.repeat(5 - rounded);
            document.getElementById('rating-count').textContent =
                count + ' ' + (count === 1 ? 'opinia' : (count < 5 && count > 1 ? 'opinie' : 'opinii'));

            // Zaktualizuj też panel po prawej (booking) jeśli ma miejsce na ocenę
            const bookingRating = document.querySelector('.product-booking-rating small');
            if (bookingRating && count > 0) {
                bookingRating.textContent = `${avg.toFixed(1)} (${count} ${count === 1 ? 'opinia' : 'opinii'})`;
            } else if (bookingRating) {
                bookingRating.textContent = 'Brak opinii';
            }
        } catch (e) { console.warn('opinions/summary:', e); }
    }

    // Czy można dodać opinię
    async function loadCanReview() {
        try {
            const res = await apiGet(`/api/products/${PRODUCT_ID}/opinions/can-review`);
            if (!res.ok) {
                document.getElementById('opinion-form-wrapper').style.display = 'none';
                return;
            }
            const payload = await res.json();
            const data = payload.data || payload;

            const formWrap = document.getElementById('opinion-form-wrapper');
            const msgEl    = document.getElementById('opinion-blocked-message');

            if (data.canReview) {
                formWrap.style.display = '';
                msgEl.style.display = 'none';
            } else {
                formWrap.style.display = 'none';
                msgEl.style.display = '';
                msgEl.textContent = data.message || 'Nie możesz dodać opinii dla tego produktu.';
            }
        } catch (e) {
            console.warn('can-review:', e);
            document.getElementById('opinion-form-wrapper').style.display = 'none';
        }
    }

    // ==============================================================
    // START
    // ==============================================================
    renderCalendar();
    updatePriceBreakdown();

    loadBookedDates();
    loadMyReservations();
    loadOpinions();
    loadOpinionsSummary();
    loadCanReview();
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
    function openZoom(idx,src) { //w tej drugiej
    
        zoomImg.setAttribute('data-current', idx);
        zoomImg.src = src;
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
        t.addEventListener('click', () => openZoom(t.dataset.idx,t.src));
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