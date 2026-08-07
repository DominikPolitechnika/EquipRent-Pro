<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Moje rezerwacje – EquipRent Pro</title>

    <link rel="stylesheet" href="{{ asset('style-rezerwacje.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- placeholdery css --}}
    <style>
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.45; } }
        .placeholder { background: #e2e8f0; display: inline-block; border-radius: 3px; }
        .placeholder-block { background: #e2e8f0; display: block; border-radius: 3px; }
        .animate-pulse { animation: pulse 1.6s ease-in-out infinite; }
        .rez-card-img.placeholder { display: block; }
        .rez-empty { padding: 24px; color: #6b7280; font-size: 13px; text-align: center; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('E.png') }}">
</head>
<body class="rez-page">
@include('partials.header')

<div class="rez-page-header">
    <h1>Moje rezerwacje</h1>
    <p>Zarządzaj aktywnym sprzętem sportowym, śledź statystyki i pobieraj dokumentację.</p>
</div>

<div class="rez-wrapper">

    {{-- ===== LEWA KOLUMNA ===== --}}
    <aside class="rez-sidebar">

        {{-- Terminy zwrotów --}}
        <div class="rez-sidebar-card-a">
            <div class="rez-sidebar-title">
                Terminy zwrotów
                <i class="fa-solid fa-circle-info" style="color:#1a6fa8; margin-left:6px;"></i>
            </div>

            <div class="rez-alert urgent" id="rez-alert-urgent">
                <i class="fa-solid fa-circle-exclamation rez-alert-icon"></i>
                <div style="flex:1; min-width:0;">
                    <div class="rez-alert-label" id="rez-alert-urgent-label">
                        <span class="placeholder-block animate-pulse" style="width:70%;height:14px;"></span>
                    </div>
                    <div class="rez-alert-sub" id="rez-alert-urgent-sub">
                        <span class="placeholder-block animate-pulse" style="width:90%;height:12px;margin-top:6px;"></span>
                    </div>
                </div>
            </div>

            <div class="rez-alert upcoming" id="rez-alert-upcoming">
                <i class="fa-regular fa-calendar rez-alert-icon"></i>
                <div style="flex:1; min-width:0;">
                    <div class="rez-alert-label" id="rez-alert-upcoming-label">
                        <span class="placeholder-block animate-pulse" style="width:55%;height:14px;"></span>
                    </div>
                    <div class="rez-alert-sub" id="rez-alert-upcoming-sub">
                        <span class="placeholder-block animate-pulse" style="width:80%;height:12px;margin-top:6px;"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Przegląd konta --}}
        <div class="rez-sidebar-card">
            <div class="rez-sidebar-title">Przegląd konta</div>
            <div class="rez-stats-grid">
                <div class="rez-stat-item">
                    <div class="rez-stat-label">Łącznie wydano</div>
                    <div class="rez-stat-value" id="rez-stat-spent">
                        <span class="placeholder-block animate-pulse" style="width:80%;height:22px;"></span>
                    </div>
                </div>
                <div class="rez-stat-item">
                    <div class="rez-stat-label">Wypożyczony sprzęt</div>
                    <div class="rez-stat-value" id="rez-stat-count">
                        <span class="placeholder-block animate-pulse" style="width:40%;height:22px;"></span>
                    </div>
                </div>
            </div>
        </div>

    </aside>

    {{-- ===== PRAWA KOLUMNA ===== --}}
    <main class="rez-content">

        {{-- AKTYWNE --}}
        <div class="rez-section-title">Aktywne wypożyczenia</div>
        <div id="rez-active-list">
            {{-- Skeleton karta (widoczna tylko do fetch-a) --}}
            <div class="rez-card rez-skeleton" data-skeleton>
                <div class="rez-card-img placeholder animate-pulse"></div>
                <div class="rez-card-body">
                    <div class="rez-card-top">
                        <span class="rez-card-name" style="flex:1; min-width:0;">
                            <span class="placeholder-block animate-pulse" style="width:75%;height:18px;"></span>
                        </span>
                        <span class="rez-badge wypozyczone">
                            <span class="placeholder animate-pulse" style="width:80px;height:12px;background:#ffffff55;"></span>
                        </span>
                    </div>
                    <div class="rez-card-meta">
                        <div class="rez-card-meta-row">
                            <span><span class="placeholder animate-pulse" style="width:90px;height:13px;"></span></span>
                            <span><span class="placeholder animate-pulse" style="width:120px;height:13px;"></span></span>
                        </div>
                    </div>
                </div>
                <div class="rez-card-actions">
                    <button class="rez-btn-cancel" disabled>Anuluj rezerwację</button>
                </div>
            </div>
        </div>

        {{-- ZAKOŃCZONE --}}
        <div class="rez-section-title done" style="margin-top: 32px;">Zakończone wypożyczenia</div>
        <div id="rez-done-list">
            <div class="rez-card-done rez-skeleton" data-skeleton>
                <div class="rez-card-img placeholder animate-pulse"></div>
                <div class="rez-card-body">
                    <div class="rez-card-name" style="margin-bottom:4px;">
                        <span class="placeholder-block animate-pulse" style="width:65%;max-width:240px;height:16px;"></span>
                    </div>
                    <div class="rez-done-meta">
                        <span class="placeholder-block animate-pulse" style="width:80%;max-width:260px;height:12px;"></span>
                    </div>
                    <div class="rez-done-cost">
                        <span class="placeholder-block animate-pulse" style="width:50%;max-width:180px;height:14px;"></span>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

{{-- Modal anulowania --}}
<div class="rez-modal-backdrop" id="cancel-modal">
    <div class="rez-modal">
        <h3>Anuluj rezerwację</h3>
        <p id="modal-text">Czy na pewno chcesz anulować rezerwację?<br>Ta operacja jest nieodwracalna.</p>
        <div class="rez-modal-actions">
            <button class="rez-btn-cancel-review" type="button" data-action="close-modal">Wróć</button>
            <button class="rez-btn-cancel" type="button" data-action="confirm-cancel">Tak, anuluj</button>
        </div>
    </div>
</div>

@include('partials.footer')

<script>
(function () {
    'use strict';

    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => root.querySelectorAll(sel);

    // ==============================================================
    // API - wszystkie endpointy z harmonogramu
    // ==============================================================
    const api = {
        active:     () => fetch('/api/reservations/active',    { headers: { 'Accept': 'application/json' } }),
        completed:  () => fetch('/api/reservations/completed', { headers: { 'Accept': 'application/json' } }),
        income:     () => fetch('/api/reservations/summary/income', { headers: { 'Accept': 'application/json' } }),
        count:      () => fetch('/api/reservations/summary/count',  { headers: { 'Accept': 'application/json' } }),
        upcoming:   (from, to) => {
            const params = new URLSearchParams({ from, to });
            return fetch(`/api/reservations/upcoming?${params}`, { headers: { 'Accept': 'application/json' } });
        },
        cancel:     (id) => fetch(`/api/reservations/${id}/cancel`, {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
        }),
        addOpinion: (productId, payload) => fetch(`/api/products/${productId}/opinions`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify(payload),
        }),
    };

    // Bezpieczne wyciągnięcie tablicy z odpowiedzi API - różne kształty (data:[], items:[], albo []).
    async function extractList(response) {
        if (!response.ok) return [];
        const data = await response.json().catch(() => null);
        if (!data) return [];
        if (Array.isArray(data)) return data;
        if (Array.isArray(data.data)) return data.data;
        if (Array.isArray(data.items)) return data.items;
        return [];
    }

    async function extractValue(response, key) {
        if (!response.ok) return null;
        const data = await response.json().catch(() => null);
        if (!data) return null;
        if (typeof data === 'number' || typeof data === 'string') return data;
        if (data.data !== undefined) return typeof data.data === 'object' ? (data.data[key] ?? null) : data.data;
        return data[key] ?? null;
    }

    // ==============================================================
    // Utils - odczyt pól z ewentualnie różnymi nazwami
    // ==============================================================
    function get(obj, ...keys) {
        for (const k of keys) {
            if (obj && obj[k] !== undefined && obj[k] !== null) return obj[k];
        }
        return null;
    }

    function formatDate(iso) {
        if (!iso) return '—';
        const d = new Date(iso);
        if (isNaN(d)) return String(iso);
        return d.toLocaleDateString('pl-PL', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    function formatMoney(v) {
        if (v === null || v === undefined) return '—';
        const num = Number(v);
        if (isNaN(num)) return String(v);
        return num.toLocaleString('pl-PL', { minimumFractionDigits: 0, maximumFractionDigits: 2 }) + ' zł';
    }

    function daysUntil(iso) {
        if (!iso) return null;
        const target = new Date(iso);
        const today = new Date();
        target.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);
        return Math.round((target - today) / 86400000);
    }

    function escapeHtml(s) {
        if (s === null || s === undefined) return '';
        return String(s)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    // ==============================================================
    // Renderery kart
    // ==============================================================
    const svgMoney = `
        <svg class="rez-meta-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.5 5h-1v1h-1c-.55 0-1 .45-1 1v3c0 .55.45 1 1 1h3v1h-4v1h2v1h1v-1h1c.55 0 1-.45 1-1v-3c0-.55-.45-1-1-1h-3V9h4V8h-2V7z" fill="#1a6fa8"/>
        </svg>`;

    function statusBadge(status) {
        const s = String(status || '').toLowerCase();
        if (s === 'active' || s === 'confirmed' || s === 'wypozyczone' || s === 'in_progress') {
            return '<span class="rez-badge wypozyczone">Wypożyczone</span>';
        }
        if (s === 'pending' || s === 'oczekuje' || s === 'oczekujaca') {
            return '<span class="rez-badge oczekuje">Oczekuje</span>';
        }
        return `<span class="rez-badge oczekuje">${escapeHtml(status || 'Aktywna')}</span>`;
    }

    function renderActiveCard(r) {
        const id         = get(r, 'id', 'reservationId', 'reservationID');
        const productId  = get(r, 'productId', 'product_id');
        const title      = get(r, 'productTitle', 'product_name', 'product_title', 'title') || 'Sprzęt';
        const start      = get(r, 'startDate', 'start_date', 'from');
        const end        = get(r, 'endDate', 'end_date', 'to');
        const status     = get(r, 'statusOfReservation', 'status');
        const total      = get(r, 'totalPrice', 'total_price', 'total');

        return `
        <div class="rez-card" data-reservation-id="${escapeHtml(id)}">
            <div class="rez-card-img placeholder"></div>
            <div class="rez-card-body">
                <div class="rez-card-top">
                    <span class="rez-card-name" style="flex:1; min-width:0;">${escapeHtml(title)}</span>
                    ${statusBadge(status)}
                </div>
                <div class="rez-card-meta">
                    <div class="rez-card-meta-row">
                        <span>Od: <strong>${formatDate(start)}</strong></span>
                        <span>Do: <strong>${formatDate(end)}</strong></span>
                    </div>
                    <div class="rez-card-meta-row">
                        <span>${svgMoney}${formatMoney(total)}</span>
                        ${productId ? `<a href="/produkt/${escapeHtml(productId)}" style="color:#1a6fa8;text-decoration:none;">Zobacz produkt →</a>` : ''}
                    </div>
                </div>
            </div>
            <div class="rez-card-actions">
                <button class="rez-btn-cancel" type="button" data-action="open-cancel" data-id="${escapeHtml(id)}" data-title="${escapeHtml(title)}">
                    Anuluj rezerwację
                </button>
            </div>
        </div>`;
    }

    function renderDoneCard(r, index) {
        const id         = get(r, 'id', 'reservationId', 'reservationID');
        const productId  = get(r, 'productId', 'product_id');
        const title      = get(r, 'productTitle', 'product_name', 'product_title', 'title') || 'Sprzęt';
        const start      = get(r, 'startDate', 'start_date', 'from');
        const end        = get(r, 'endDate', 'end_date', 'to');
        const total      = get(r, 'totalPrice', 'total_price', 'total');
        const hasReview  = get(r, 'hasReview', 'has_review', 'reviewed');

        const reviewPanelId = `review-${id}`;

        return `
        <div class="rez-card-done" data-reservation-id="${escapeHtml(id)}">
            <div class="rez-card-img placeholder"></div>
            <div class="rez-card-body">
                <div class="rez-card-name" style="margin-bottom:4px;">${escapeHtml(title)}</div>
                <div class="rez-done-meta">Okres: ${formatDate(start)} — ${formatDate(end)}</div>
                <div class="rez-done-cost">Zapłacono: <strong>${formatMoney(total)}</strong></div>
                ${hasReview
                    ? `<div style="color:#16a34a;font-size:12px;margin-top:6px;">✓ Opinia dodana</div>`
                    : `<button class="rez-add-review-toggle" type="button" data-action="toggle-review" data-target="${reviewPanelId}">
                            <i class="fa-solid fa-plus"></i> Dodaj opinię
                        </button>`}
            </div>
            <button class="rez-btn-rebook" type="button" data-action="rebook" data-product-id="${escapeHtml(productId)}">
                Wypożycz ponownie
            </button>
        </div>
        ${!hasReview ? `
        <div class="rez-review-panel" id="${reviewPanelId}" data-product-id="${escapeHtml(productId)}" data-reservation-id="${escapeHtml(id)}">
            <div class="rez-review-panel-title">Podziel się swoją opinią</div>
            <div class="rez-star-input" data-selected="0">
                <span data-v="1">★</span>
                <span data-v="2">★</span>
                <span data-v="3">★</span>
                <span data-v="4">★</span>
                <span data-v="5">★</span>
            </div>
            <textarea class="rez-review-textarea" placeholder="Opisz swoje wrażenia..." maxlength="2000"></textarea>
            <div class="rez-review-actions">
                <button class="rez-btn-cancel-review" type="button" data-action="toggle-review" data-target="${reviewPanelId}">Anuluj</button>
                <button class="rez-btn-submit-review" type="button" data-action="submit-review" data-target="${reviewPanelId}">Wyślij opinię</button>
            </div>
        </div>` : ''}`;
    }

    // ==============================================================
    // Alerty w sidebarze - z listy nadchodzących zwrotów
    // ==============================================================
    function renderAlerts(list) {
        // Sortuję po dacie zwrotu ASC
        const sorted = [...list].sort((a, b) => {
            const da = new Date(get(a, 'endDate', 'end_date') || 0);
            const db = new Date(get(b, 'endDate', 'end_date') || 0);
            return da - db;
        });

        const urgent   = sorted[0];  // najbliższy
        const upcoming = sorted[1];  // drugi w kolejce

        function setAlert(labelId, subId, item, urgentBool) {
            const labelEl = $('#' + labelId);
            const subEl   = $('#' + subId);
            if (!labelEl || !subEl) return;

            if (!item) {
                labelEl.textContent = urgentBool ? 'Brak pilnych zwrotów' : 'Brak nadchodzących';
                subEl.textContent   = 'Nic w najbliższych 30 dniach.';
                return;
            }

            const title = get(item, 'productTitle', 'product_name', 'title') || 'Sprzęt';
            const end   = get(item, 'endDate', 'end_date');
            const days  = daysUntil(end);
            let sub;
            if (days === null)      sub = `Termin: ${formatDate(end)}`;
            else if (days < 0)      sub = `Zwrot ${Math.abs(days)} dni po terminie (${formatDate(end)})`;
            else if (days === 0)    sub = `Zwrot dziś (${formatDate(end)})`;
            else if (days === 1)    sub = `Zwrot jutro (${formatDate(end)})`;
            else                    sub = `Za ${days} dni (${formatDate(end)})`;

            labelEl.textContent = title;
            subEl.textContent   = sub;
        }

        setAlert('rez-alert-urgent-label',   'rez-alert-urgent-sub',   urgent,   true);
        setAlert('rez-alert-upcoming-label', 'rez-alert-upcoming-sub', upcoming, false);
    }

    // ==============================================================
    // Modal anulowania
    // ==============================================================
    let cancelTargetId = null;

    function openCancelModal(id, title) {
        cancelTargetId = id;
        const modalText = $('#modal-text');
        if (modalText) {
            modalText.innerHTML = `Czy na pewno chcesz anulować rezerwację<br>
                <strong>${escapeHtml(title || 'tego sprzętu')}</strong>?<br>
                Ta operacja jest nieodwracalna.`;
        }
        $('#cancel-modal').classList.add('open');
    }

    function closeCancelModal() {
        cancelTargetId = null;
        $('#cancel-modal').classList.remove('open');
    }

    async function confirmCancel() {
        if (!cancelTargetId) { closeCancelModal(); return; }
        const id = cancelTargetId;
        try {
            const res = await api.cancel(id);
            if (res.status === 404) {
                alert('Rezerwacja nie istnieje lub nie należy do Ciebie.');
                closeCancelModal();
                return;
            }
            if (res.status === 409) {
                alert('Ta rezerwacja jest już anulowana lub zakończona.');
                closeCancelModal();
                return;
            }
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            // Usuń kartę z listy
            const card = $(`.rez-card[data-reservation-id="${CSS.escape(id)}"]`);
            if (card) card.remove();
            closeCancelModal();
            // Odśwież statystyki i alerty
            loadStatsAndAlerts();
        } catch (e) {
            alert('Nie udało się anulować rezerwacji. Spróbuj później.');
            closeCancelModal();
        }
    }

    // ==============================================================
    // Panel opinii - toggle
    // ==============================================================
    function toggleReview(id) {
        const panel = document.getElementById(id);
        if (panel) panel.classList.toggle('open');
    }

    // Gwiazdki w panelach opinii (delegacja - działa też dla dynamicznie dodanych)
    document.addEventListener('mouseover', (e) => {
        const star = e.target.closest('.rez-star-input span');
        if (!star) return;
        const container = star.closest('.rez-star-input');
        const v = parseInt(star.dataset.v, 10);
        container.querySelectorAll('span').forEach((s, i) => {
            s.classList.toggle('active', i < v);
        });
    });
    document.addEventListener('mouseout', (e) => {
        const container = e.target.closest?.('.rez-star-input');
        if (!container) return;
        const selected = parseInt(container.dataset.selected || '0', 10);
        container.querySelectorAll('span').forEach((s, i) => {
            s.classList.toggle('active', i < selected);
        });
    });
    document.addEventListener('click', (e) => {
        const star = e.target.closest('.rez-star-input span');
        if (!star) return;
        const container = star.closest('.rez-star-input');
        const v = parseInt(star.dataset.v, 10);
        container.dataset.selected = String(v);
    });

    // ==============================================================
    // Wysyłka opinii
    // ==============================================================
    async function submitReview(panelId) {
        const panel = document.getElementById(panelId);
        if (!panel) return;
        const productId = panel.dataset.productId;
        const starInput = panel.querySelector('.rez-star-input');
        const textarea  = panel.querySelector('.rez-review-textarea');

        const scaleValue = parseInt(starInput.dataset.selected || '0', 10);
        const description = (textarea.value || '').trim();

        if (scaleValue < 1) { alert('Wybierz ocenę (kliknij gwiazdki).'); return; }
        if (description.length < 3) { alert('Napisz choć kilka słów opinii.'); return; }

        try {
            const res = await api.addOpinion(productId, {
                scaleValue,
                description,
            });

            if (res.status === 403) {
                alert('Opinię możesz dodać dopiero po zakończonym wypożyczeniu.');
                return;
            }
            if (res.status === 409) {
                alert('Dodałeś już opinię dla tego produktu.');
                panel.classList.remove('open');
                // Ukryj przycisk "Dodaj opinię" bo już opinia jest
                const card = panel.previousElementSibling;
                if (card) {
                    const btn = card.querySelector('[data-action="toggle-review"]');
                    if (btn) btn.outerHTML = `<div style="color:#16a34a;font-size:12px;margin-top:6px;">✓ Opinia dodana</div>`;
                }
                return;
            }
            if (!res.ok) {
                const err = await res.json().catch(() => null);
                throw new Error(err?.message || `HTTP ${res.status}`);
            }

            panel.classList.remove('open');
            // Podmień przycisk "Dodaj opinię" na potwierdzenie
            const card = panel.previousElementSibling; // rez-card-done bezpośrednio nad panelem
            if (card) {
                const btn = card.querySelector('[data-action="toggle-review"]');
                if (btn) {
                    btn.outerHTML = `<div style="color:#16a34a;font-size:12px;margin-top:6px;">✓ Opinia dodana</div>`;
                }
            }
        } catch (e) {
            alert('Nie udało się dodać opinii: ' + e.message);
        }
    }

    // ==============================================================
    // Delegacja kliknięć - jeden listener na body
    // ==============================================================
    document.body.addEventListener('click', (e) => {
        const target = e.target.closest('[data-action]');
        if (!target) return;

        const action = target.dataset.action;

        switch (action) {
            case 'open-cancel':
                openCancelModal(target.dataset.id, target.dataset.title);
                break;
            case 'close-modal':
                closeCancelModal();
                break;
            case 'confirm-cancel':
                confirmCancel();
                break;
            case 'toggle-review':
                toggleReview(target.dataset.target);
                break;
            case 'submit-review':
                submitReview(target.dataset.target);
                break;
            case 'rebook':
                const pid = target.dataset.productId;
                if (pid && pid !== 'null') window.location.href = `/produkt/${pid}`;
                break;
        }
    });

    // Klik w tło modala zamyka go
    $('#cancel-modal').addEventListener('click', function (e) {
        if (e.target === this) closeCancelModal();
    });

    // ==============================================================
    // Fetche - główny load
    // ==============================================================
    async function loadReservations() {
        const activeList = $('#rez-active-list');
        const doneList   = $('#rez-done-list');

        try {
            const [activeRes, doneRes] = await Promise.all([
                api.active(),
                api.completed(),
            ]);

            const active    = await extractList(activeRes);
            const completed = await extractList(doneRes);

            // Aktywne
            activeList.innerHTML = active.length
                ? active.map(renderActiveCard).join('')
                : `<div class="rez-empty">Nie masz aktywnych wypożyczeń.</div>`;

            // Zakończone (z panelami opinii)
            doneList.innerHTML = completed.length
                ? completed.map((r, i) => renderDoneCard(r, i)).join('')
                : `<div class="rez-empty">Brak historii wypożyczeń.</div>`;

        } catch (e) {
            activeList.innerHTML = `<div class="rez-empty">Nie udało się pobrać rezerwacji.</div>`;
            doneList.innerHTML   = `<div class="rez-empty">Nie udało się pobrać historii.</div>`;
            console.error(e);
        }
    }

    async function loadStatsAndAlerts() {
        // Statystyki - łącznie wydano
        try {
            const incomeRes = await api.income();
            const income    = await extractValue(incomeRes, 'totalIncome');
            $('#rez-stat-spent').textContent = income !== null ? formatMoney(income) : '—';
        } catch { $('#rez-stat-spent').textContent = '—'; }

        // Statystyki - ile wypożyczono
        try {
            const countRes = await api.count();
            const count    = await extractValue(countRes, 'totalRentedItems');
            $('#rez-stat-count').textContent = count !== null ? String(count) : '—';
        } catch { $('#rez-stat-count').textContent = '—'; }

        // Alerty terminów zwrotu - z upcoming
        try {
            const today = new Date();
            const in30  = new Date(today.getTime() + 30 * 86400000);
            const from  = today.toISOString().slice(0, 10);
            const to    = in30.toISOString().slice(0, 10);

            const upcomingRes = await api.upcoming(from, to);
            const list        = await extractList(upcomingRes);
            renderAlerts(list);
        } catch (e) {
            renderAlerts([]);
            console.error(e);
        }
    }

    // Start
    document.addEventListener('DOMContentLoaded', () => {
        loadReservations();
        loadStatsAndAlerts();
    });
})();
</script>

</body>
</html>