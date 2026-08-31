<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="stripe-key" content="{{ config('services.stripe.key') }}">
    <title>Finalizacja Wynajmu – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-head.css') }}">
    <link rel="stylesheet" href="{{ asset('style-foot.css') }}">
    <link rel="stylesheet" href="{{ asset('style-platnosc.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('E.png') }}">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="pay-page">

@include('partials.header')

<main class="pay-wrapper">

    {{-- Powrót + tytuł --}}
    <a href="{{ route('catalog') }}" class="pay-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        Powrót do inwentarza
    </a>
    <h1 class="pay-title">Finalizacja Wynajmu</h1>

    {{-- Banner statusu płatności (ukryty domyślnie, pokazywany przez JS) --}}
    <div class="pay-banner" id="pay-banner">
        <span id="pay-banner-text"></span>
        <button type="button" class="pay-banner-redirect" id="pay-banner-redirect-btn">Przejdź teraz →</button>
    </div>

    <div class="pay-grid">

        {{-- ===== LEWA KOLUMNA - FORMULARZ ===== --}}
        <div class="pay-card">
            <div class="pay-card-header">
                <span class="pay-card-title">Metoda Płatności</span>
                <span class="pay-ssl">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Bezpieczne połączenie SSL
                </span>
            </div>

            <form id="pay-form" onsubmit="event.preventDefault();">

                {{-- Zapisane metody płatności + opcja nowej karty - wypełniane przez JS --}}
                <div class="pay-methods-list" id="pay-methods-list">
                    <div style="font-size:12px;color:#9aa5ad;">Ładowanie zapisanych metod płatności…</div>
                </div>

                <div id="pay-methods-limit-note" class="pay-methods-limit-note" style="display:none;">
                    Osiągnięto limit 3 zapisanych metod płatności — usuń jedną z kart powyżej (✕), aby zapisać nową.
                </div>

                {{-- Formularz nowej karty (Stripe Card Element) --}}
                <div id="pay-new-card-block">
                    <div class="pay-form-group">
                        <label class="pay-label" for="pay-name">Imię i nazwisko posiadacza karty *</label>
                        <input type="text" id="pay-name" class="pay-input" placeholder="Jan Kowalski" autocomplete="cc-name">
                    </div>

                    <div class="pay-form-group">
                        <label class="pay-label">Dane karty (Stripe — tryb testowy)</label>
                        <div class="pay-card-element-wrap show" id="pay-card-element-wrap">
                            <div id="pay-card-element"><!-- Stripe Card Element montowany tutaj --></div>
                        </div>
                        <div class="pay-card-error" id="pay-card-error"></div>
                    </div>

                    <label class="pay-save-row" id="pay-save-row">
                        <input type="checkbox" id="pay-save-card" checked>
                        Zapisz kartę do przyszłych płatności (maks. 3 karty)
                    </label>
                </div>

                <button type="submit" class="pay-submit" id="pay-submit-btn">
                    <span id="pay-submit-label">Zapłać teraz</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </form>

            <div class="pay-card-footer">
                <div class="pay-pci-logo"></div>
                <span class="pay-pci-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Zgodność PCI
                </span>
            </div>
        </div>

        {{-- ===== PRAWA KOLUMNA - PODSUMOWANIE ===== --}}
        <div>
            <div class="pay-summary">

                {{-- Hero z tytułem produktu --}}
                <div class="pay-hero">
                    <div class="pay-hero-overlay"></div>
                    <span class="pay-hero-cat">Sprzęt sportowy</span>
                    <div class="pay-hero-title" id="pay-hero-title">Ładowanie…</div>
                </div>

                {{-- Daty --}}
                <div class="pay-dates">
                    <div class="pay-date-col">
                        <div class="pay-date-label">Odbiór</div>
                        <div class="pay-date-value" id="pay-date-start">—</div>
                    </div>
                    <div class="pay-date-arrow">→</div>
                    <div class="pay-date-col end">
                        <div class="pay-date-label">Zwrot</div>
                        <div class="pay-date-value" id="pay-date-end">—</div>
                    </div>
                </div>

                {{-- Pozycje rachunku --}}
                <div class="pay-items">
                    <div class="pay-item">
                        <span class="pay-item-label" id="pay-days-label">Wynajem</span>
                        <span class="pay-item-amount" id="pay-days-amount">—</span>
                    </div>
                </div>

                {{-- Suma --}}
                <div class="pay-total">
                    <span class="pay-total-label">Do zapłaty</span>
                    <span class="pay-total-amount" id="pay-total-amount">—</span>
                </div>

                {{-- Timer --}}
                <div class="pay-timer">
                    <span class="pay-timer-dot"></span>
                    Sprzęt zarezerwowany przez <span id="pay-timer-text">14:59</span> minut
                </div>
            </div>

            {{-- Pomoc --}}
            <div class="pay-help">
                <div class="pay-help-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                </div>
                <div>
                    <div class="pay-help-title">Potrzebujesz pomocy z rezerwacją?</div>
                    <div class="pay-help-text">Nasz zespół logistyczny jest dostępny 24/7, aby pomóc w transporcie i specyfikacji technicznej.</div>
                </div>
            </div>
        </div>

    </div>
</main>

{{-- ===== MODAL 3D SECURE (ZAŚLEPKA) --}}
<div class="pay-tds-backdrop" id="pay-tds-backdrop">
    <div class="pay-tds-modal">
        <div class="pay-tds-bank-header">
            <span class="pay-tds-bank-dot"></span>
            <span class="pay-tds-bank-name">Bank Testowy — Weryfikacja 3D Secure</span>
        </div>
        <span class="pay-tds-badge">Symulacja — tryb testowy</span>
        <h3 class="pay-tds-title">Potwierdź płatność</h3>
        <p class="pay-tds-text">
            Twój bank wymaga dodatkowej weryfikacji tej transakcji.
            To jest <strong>zaślepka demonstracyjna</strong> — żadna prawdziwa
            autoryzacja nie jest tu przeprowadzana. Wybierz, jak ma zakończyć
            się symulacja.
        </p>
        <div class="pay-tds-actions">
            <button type="button" class="pay-tds-btn" id="pay-tds-decline">Odrzuć</button>
            <button type="button" class="pay-tds-btn primary" id="pay-tds-approve">Zatwierdź</button>
        </div>
    </div>
</div>

@include('partials.footer')

<script>
(function() {
    'use strict';

    // ==============================================================
    // Konfiguracja
    // ==============================================================
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const STRIPE_KEY = document.querySelector('meta[name="stripe-key"]').content;
    const RESERVATION_ID = new URLSearchParams(window.location.search).get('reservation');
    const MAX_SAVED_METHODS = 3;

    const $ = (sel) => document.querySelector(sel);

    function apiGet(url) {
        return fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
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
            body: body !== undefined ? JSON.stringify(body) : undefined,
        });
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
        return d.toLocaleDateString('pl-PL', { day: '2-digit', month: 'short', year: 'numeric' });
    }
    function formatMoney(v) {
        if (v === null || v === undefined) return '—';
        const num = Number(v);
        if (isNaN(num)) return String(v);
        return num.toLocaleString('pl-PL', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' PLN';
    }
    function daysBetween(a, b) {
        return Math.max(1, Math.round((new Date(b) - new Date(a)) / 86400000) + 1);
    }
    function uuidv4() {
        if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // ==============================================================
    // Brak reservation w URL — nie ma czego płacić
    // ==============================================================
    if (!RESERVATION_ID) {
        showBanner('error', 'Nie wskazano rezerwacji do opłacenia. Przechodzę do listy rezerwacji…');
        setTimeout(() => window.location.href = '/rezerwacje', 2000);
        return;
    }

    // ==============================================================
    // Stripe.js
    // ==============================================================
    const stripe = (window.Stripe && STRIPE_KEY) ? Stripe(STRIPE_KEY) : null;
    const elements = stripe ? stripe.elements() : null;
    let cardElement = null;
    if (elements) {
        cardElement = elements.create('card', {
            disableLink: true,
            style: {
                base: {
                    fontFamily: "'Poppins', sans-serif",
                    fontSize: '14px',
                    color: '#111827',
                    '::placeholder': { color: '#9aa5ad' },
                },
                invalid: { color: '#dc2626' },
            },
        });
        cardElement.mount('#pay-card-element');
        cardElement.on('change', (event) => {
            $('#pay-card-error').textContent = event.error ? event.error.message : '';
        });
    } else {
        $('#pay-card-error').textContent = 'Nie udało się załadować Stripe.js — sprawdź klucz publiczny (STRIPE_KEY) w konfiguracji.';
    }

    // ==============================================================
    // Banner statusu płatności
    // ==============================================================
    function showBanner(type, text, { withRedirectButton = false } = {}) {
        const banner = $('#pay-banner');
        banner.className = 'pay-banner show ' + type;
        $('#pay-banner-text').textContent = text;
        $('#pay-banner-redirect-btn').style.display = withRedirectButton ? '' : 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    $('#pay-banner-redirect-btn').addEventListener('click', () => {
        window.location.href = '/rezerwacje';
    });

    function finishAndRedirect(type, text, delayMs = 2500) {
        showBanner(type, text, { withRedirectButton: true });
        setTimeout(() => window.location.href = '/rezerwacje', delayMs);
    }

    // ==============================================================
    // Ładowanie danych rezerwacji - GET /api/reservations/{id}
    // ==============================================================
    let reservation = null;

    async function loadReservation() {
        try {
            const res = await apiGet(`/api/reservations/${encodeURIComponent(RESERVATION_ID)}`);

            if (res.status === 403 || res.status === 404) {
                showBanner('error', 'Ta rezerwacja nie istnieje lub nie należy do Ciebie.');
                setTimeout(() => window.location.href = '/rezerwacje', 2000);
                return;
            }
            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const payload = await res.json();
            reservation = payload.data || payload;

            if (reservation.statusOfReservation !== 'awaiting_payment') {
                const info = reservation.statusOfReservation === 'active'
                    ? 'Ta rezerwacja została już opłacona.'
                    : 'Ta rezerwacja nie oczekuje już na płatność.';
                showBanner('info', info + ' Przechodzę do Twoich rezerwacji…');
                setTimeout(() => window.location.href = '/rezerwacje', 2000);
                return;
            }

            renderReservationSummary(reservation);
        } catch (e) {
            console.error('reservations/{id}:', e);
            showBanner('error', 'Nie udało się pobrać danych rezerwacji. Spróbuj ponownie później.');
        }
    }

    function renderReservationSummary(r) {
        $('#pay-hero-title').textContent = r.productTitle || 'Wynajem sprzętu';
        $('#pay-date-start').textContent = formatDate(r.startDate);
        $('#pay-date-end').textContent = formatDate(r.endDate);

        const days = daysBetween(r.startDate, r.endDate);
        $('#pay-days-label').innerHTML = `Wynajem <small>(${days} ${days === 1 ? 'dzień' : 'dni'})</small>`;
        $('#pay-days-amount').textContent = formatMoney(r.totalPrice);
        $('#pay-total-amount').textContent = formatMoney(r.totalPrice);
        $('#pay-submit-label').textContent = `Zapłać ${formatMoney(r.totalPrice)} teraz`;

        const hero = document.querySelector('.pay-hero');
        if (r.productThumbnailUrl && hero) {
            hero.style.backgroundImage =
                `linear-gradient(to bottom, rgba(0,0,0,.15) 30%, rgba(0,0,0,.65) 100%), url('${r.productThumbnailUrl}')`;
            hero.style.backgroundSize = 'cover';
            hero.style.backgroundPosition = 'center';
        }
    }

    // ==============================================================
    // Zapisane metody płatności - GET /api/payments/payment-methods
    // ==============================================================
    let savedMethods = [];
    let selectedMethodId = 'new';

    async function loadPaymentMethods() {
        try {
            const res = await apiGet('/api/payments/payment-methods');
            savedMethods = res.ok ? await res.json() : [];
        } catch (e) {
            console.warn('payment-methods:', e);
            savedMethods = [];
        }
        renderPaymentMethods();
    }

    function renderPaymentMethods() {
        const list = $('#pay-methods-list');
        const atLimit = savedMethods.length >= MAX_SAVED_METHODS;

        const savedHtml = savedMethods.map(pm => `
            <label class="pay-method-option" data-value="${pm.id}">
                <input type="radio" name="pay-method" value="${pm.id}">
                <span class="pay-method-label">
                    ${escapeHtml((pm.brand || 'Karta').toUpperCase())} •••• ${escapeHtml(pm.last4 || '----')}
                    <div class="pay-method-sub">
                        ${pm.cardholder_name ? escapeHtml(pm.cardholder_name) + ' · ' : ''}Ważna do ${String(pm.exp_month).padStart(2, '0')}/${pm.exp_year} · zapisana
                    </div>
                </span>
                <button type="button" class="pay-method-remove" data-remove-id="${pm.id}" title="Usuń kartę">✕</button>
            </label>
        `).join('');

        const newCardHtml = `
            <label class="pay-method-option" data-value="new">
                <input type="radio" name="pay-method" value="new">
                <span class="pay-method-label">
                    Nowa karta
                    <div class="pay-method-sub">Wpisz dane karty poniżej (Stripe, tryb testowy)</div>
                </span>
            </label>
        `;

        list.innerHTML = savedHtml + newCardHtml;

        selectedMethodId = savedMethods.length > 0 ? String(savedMethods[0].id) : 'new';
        applySelection();

        $('#pay-methods-limit-note').style.display = atLimit ? '' : 'none';
        const saveCheckbox = $('#pay-save-card');
        const saveRow = $('#pay-save-row');
        if (atLimit) {
            saveCheckbox.checked = false;
            saveCheckbox.disabled = true;
            saveRow.classList.add('disabled');
        } else {
            saveCheckbox.disabled = false;
            saveRow.classList.remove('disabled');
        }

        list.querySelectorAll('input[name="pay-method"]').forEach(input => {
            input.addEventListener('change', () => {
                selectedMethodId = input.value;
                applySelection();
            });
        });

        list.querySelectorAll('[data-remove-id]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                if (!confirm('Usunąć tę zapisaną kartę?')) return;
                try {
                    const res = await apiJson('DELETE', `/api/payments/payment-methods/${btn.dataset.removeId}`);
                    if (res.ok || res.status === 204) {
                        await loadPaymentMethods();
                    } else {
                        alert('Nie udało się usunąć karty.');
                    }
                } catch (err) {
                    alert('Błąd sieci przy usuwaniu karty.');
                }
            });
        });
    }

    function applySelection() {
        document.querySelectorAll('.pay-method-option').forEach(el => {
            const isSelected = el.dataset.value === selectedMethodId;
            el.classList.toggle('selected', isSelected);
            const radio = el.querySelector('input[type="radio"]');
            if (radio) radio.checked = isSelected;
        });

        const showNewCardBlock = selectedMethodId === 'new';
        $('#pay-new-card-block').style.display = showNewCardBlock ? '' : 'none';
    }

    // ==============================================================
    // Modal 3DS (zaślepka)
    // ==============================================================
    let pendingPaymentId = null;

    function openTdsModal(paymentId) {
        pendingPaymentId = paymentId;
        $('#pay-tds-backdrop').classList.add('open');
    }
    function closeTdsModal() {
        $('#pay-tds-backdrop').classList.remove('open');
        pendingPaymentId = null;
    }

    async function resolveTdsStub(approve) {
        if (!pendingPaymentId) return;
        const paymentId = pendingPaymentId;
        closeTdsModal();
        setSubmitting(true, 'Finalizowanie…');

        try {
            const res = await apiJson('POST', `/api/payments/${paymentId}/confirm-3ds-stub`, { approve });
            const payment = await res.json().catch(() => ({}));

            if (approve && payment.status === 'succeeded') {
                finishAndRedirect('success', 'Płatność zakończona sukcesem! Przenoszę Cię do Twoich rezerwacji…');
            } else {
                finishAndRedirect('error', 'Płatność nie powiodła się (odrzucona podczas weryfikacji 3D Secure). Możesz spróbować ponownie z widoku "Moje rezerwacje".');
            }
        } catch (e) {
            console.error('confirm-3ds-stub:', e);
            finishAndRedirect('error', 'Błąd podczas finalizowania płatności. Spróbuj ponownie z widoku "Moje rezerwacje".');
        }
    }

    $('#pay-tds-approve').addEventListener('click', () => resolveTdsStub(true));
    $('#pay-tds-decline').addEventListener('click', () => resolveTdsStub(false));

    // ==============================================================
    // Stan przycisku "Zapłać"
    // ==============================================================
    function setSubmitting(isSubmitting, label) {
        const btn = $('#pay-submit-btn');
        btn.disabled = isSubmitting;
        btn.classList.toggle('disabled', isSubmitting);
        if (label) $('#pay-submit-label').textContent = label;
        else if (reservation) $('#pay-submit-label').textContent = `Zapłać ${formatMoney(reservation.totalPrice)} teraz`;
    }

    async function resolvePaymentMethodId() {
        if (selectedMethodId !== 'new') {
            return parseInt(selectedMethodId, 10);
        }

        if (!stripe || !cardElement) {
            throw new Error('Stripe.js nie jest dostępne — nie można dodać nowej karty.');
        }

        const cardName = $('#pay-name').value.trim();
        if (!cardName) {
            $('#pay-card-error').textContent = 'Podaj imię i nazwisko posiadacza karty.';
            $('#pay-name').focus();
            throw new Error('Brak imienia i nazwiska posiadacza karty.');
        }

        const siRes = await apiJson('POST', '/api/payments/setup-intent');
        if (!siRes.ok) throw new Error('Nie udało się zainicjować dodania karty.');
        const { client_secret } = await siRes.json();

        const { setupIntent, error } = await stripe.confirmCardSetup(client_secret, {
            payment_method: {
                card: cardElement,
                billing_details: { name: cardName },
            },
        });

        if (error) {
            $('#pay-card-error').textContent = error.message || 'Nieprawidłowe dane karty.';
            throw new Error(error.message || 'Błąd karty.');
        }

        const save = $('#pay-save-card').checked && !$('#pay-save-card').disabled;
        const pmRes = await apiJson('POST', '/api/payments/payment-methods', {
            payment_method_id: setupIntent.payment_method,
            save,
        });

        if (pmRes.status === 409) {
            const err = await pmRes.json().catch(() => ({}));
            throw new Error(err.message || 'Osiągnięto limit zapisanych metod płatności.');
        }
        if (!pmRes.ok) throw new Error('Nie udało się zapisać karty.');

        const savedPm = await pmRes.json();
        return savedPm.id;
    }

    // ==============================================================
    // Wysłanie płatności - POST /api/payments/charge
    // ==============================================================
    async function submitPayment() {
        if (!reservation) return;

        setSubmitting(true, 'Przetwarzanie…');
        $('#pay-card-error').textContent = '';

        let paymentMethodId;
        try {
            paymentMethodId = await resolvePaymentMethodId();
        } catch (e) {
            setSubmitting(false);
            if (e.message) $('#pay-card-error').textContent = e.message;
            return;
        }

        try {
            const res = await apiJson('POST', '/api/payments/charge', {
                reservation_id: parseInt(RESERVATION_ID, 10),
                amount: Math.round(reservation.totalPrice * 100),
                currency: 'pln',
                description: `Wynajem: ${reservation.productTitle} (rezerwacja #${RESERVATION_ID}, ${formatDate(reservation.startDate)} – ${formatDate(reservation.endDate)})`,
                payment_method_id: paymentMethodId,
                idempotency_key: uuidv4(),
            });

            const payment = await res.json().catch(() => ({}));

            if (res.status === 201 && payment.status === 'succeeded') {
                finishAndRedirect('success', 'Płatność zakończona sukcesem! Przenoszę Cię do Twoich rezerwacji…');
                return;
            }

            if (res.status === 402 && payment.status === 'requires_action') {
                setSubmitting(false);
                openTdsModal(payment.id);
                return;
            }

            if (res.status === 402) {
                setSubmitting(false);
                finishAndRedirect('error', 'Płatność nie powiodła się. Za chwilę przeniesiemy Cię do Twoich rezerwacji, skąd możesz spróbować ponownie.');
                return;
            }

            if (res.status === 409) {
                setSubmitting(false);
                showBanner('info', payment.message || 'Ta rezerwacja nie oczekuje już na płatność.', { withRedirectButton: true });
                setTimeout(() => window.location.href = '/rezerwacje', 2500);
                return;
            }

            setSubmitting(false);
            showBanner('error', payment.message || 'Nie udało się przetworzyć płatności. Spróbuj ponownie.');
        } catch (e) {
            console.error('payments/charge:', e);
            setSubmitting(false);
            showBanner('error', 'Błąd sieci przy przetwarzaniu płatności.');
        }
    }

    $('#pay-form').addEventListener('submit', submitPayment);

    // ==============================================================
    // Timer 15 min (kosmetyczny - blokada terminu jest egzekwowana
    // przez backend
    // ==============================================================
    const timerEl = $('#pay-timer-text');
    if (timerEl) {
        let seconds = 15 * 60 - 1;
        const tick = () => {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            timerEl.textContent = m + ':' + (s < 10 ? '0' + s : s);
            if (seconds > 0) seconds--;
        };
        tick();
        setInterval(tick, 1000);
    }

    // ==============================================================
    // START
    // ==============================================================
    loadReservation();
    loadPaymentMethods();
})();
</script>

</body>
</html>
