<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rezerwacja #{{ $id }} – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-list-rentals.css') }}">
    <link rel="stylesheet" href="{{ asset('style-product-edit.css') }}">
    <style>
        .pe-empty{padding:24px;text-align:center;color:#9aa5ad;font-size:13px}
        .pe-delete-repair{border:0;background:transparent;color:#dc2626;cursor:pointer;font-size:12px}
        .pe-row-error{font-size:11px;color:#dc2626;margin-top:4px}
        .rd-content{padding:28px 40px 60px;max-width:1000px;display:flex;flex-direction:column;gap:20px;}
        .rd-overview{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
        .rd-status-row{display:flex;align-items:end;gap:14px;}
        .rd-status-row .pe-form-group{flex:1;}
        .rd-alert{padding:12px 16px;border-radius:8px;margin-bottom:0;font-size:13px}
        .rd-alert.success{background:#dcfce7;color:#166534}
        .rd-alert.error{background:#fee2e2;color:#991b1b}
    </style>
</head>
<body class="pe-page">
<div class="adm-shell">
    @include('partials.admin-sidebar')

    <div class="adm-body">
        @include('partials.admin-topbar')

        <div class="adm-content">
            <div class="rd-content">

                {{-- BREADCRUMB --}}
                <div class="le-breadcrumb">
                    <span>Zarządzanie</span>
                    <span>›</span>
                    <a href="{{ route('rentals.list') }}">Rezerwacje</a>
                    <span>›</span>
                    <span class="active">Rezerwacja #{{ $id }}</span>
                </div>
                <h1 class="le-title" id="rd-title">Rezerwacja #{{ $id }}</h1>

                {{-- PODSUMOWANIE --}}
                <div class="pe-section">
                    <div class="pe-section-header">
                        <div class="pe-section-title">
                            <div class="pe-section-title-icon-box">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            Podsumowanie
                        </div>
                        <span class="lr-badge" id="rd-status-badge"></span>
                    </div>

                    <div class="rd-overview">
                        <div style="display:flex; gap:14px; align-items:center;">
                            <div class="lr-thumb" id="rd-product-thumb" style="width:56px;height:56px;flex-shrink:0;"></div>
                            <div style="min-width:0;">
                                <div class="pe-form-label">Sprzęt</div>
                                <div class="lr-col-val" id="rd-product-title">-</div>
                                <div class="lr-col-sub" id="rd-product-serial"></div>
                            </div>
                        </div>

                        <div style="display:flex; gap:14px; align-items:center;">
                            <div class="lr-thumb" id="rd-client-avatar" style="width:56px;height:56px;flex-shrink:0;border-radius:50%;"></div>
                            <div style="min-width:0;">
                                <div class="pe-form-label">Klient</div>
                                <div class="lr-col-val" id="rd-client-name">-</div>
                                <div class="lr-col-sub" id="rd-client-email"></div>
                                <div class="lr-col-sub" id="rd-client-phone"></div>
                            </div>
                        </div>

                        <div>
                            <div class="pe-form-label">Okres wynajmu</div>
                            <div class="lr-col-val" id="rd-period">-</div>
                            <div class="lr-col-sub" id="rd-days"></div>
                        </div>

                        <div>
                            <div class="pe-form-label">Wartość całkowita</div>
                            <div class="lr-value-main" id="rd-price">-</div>
                            <div class="lr-col-sub" id="rd-created-at"></div>
                        </div>
                    </div>
                </div>

                {{-- ZMIANA STATUSU --}}
                <div class="pe-section">
                    <div class="pe-section-header">
                        <div class="pe-section-title">
                            <div class="pe-section-title-icon-box">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            Status rezerwacji
                        </div>
                    </div>

                    <div class="rd-status-row">
                        <div class="pe-form-group">
                            <label class="pe-form-label" for="rd-status-select">Status</label>
                            <select id="rd-status-select" class="pe-form-select">
                                <option value="pending">Oczekująca</option>
                                <option value="confirmed">Zarezerwowana</option>
                                <option value="active">Aktywna</option>
                                <option value="completed">Oddane</option>
                                <option value="repair">Naprawa</option>
                                <option value="cancelled">Anulowana</option>
                            </select>
                        </div>
                        <button type="button" class="pe-maint-add-btn" id="rd-status-save">Zapisz status</button>
                    </div>
                    <div id="rd-status-message" style="margin-top:12px;"></div>
                </div>

                {{-- INCYDENTY / NAPRAWY --}}
                <div class="pe-section">
                    <div class="pe-section-header">
                        <div class="pe-section-title">
                            <div class="pe-section-title-icon-box">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </div>
                            Incydenty / uszkodzenia sprzętu
                        </div>
                    </div>

                    <div class="pe-maint-form">
                        <div class="pe-form-group">
                            <label class="pe-form-label">Opis</label>
                            <input type="text" id="incident-description" class="pe-form-input" placeholder="Opis uszkodzenia/incydentu...">
                        </div>
                        <div class="pe-form-group">
                            <label class="pe-form-label">Zgłaszający</label>
                            <input type="text" id="incident-serviceman" class="pe-form-input" maxlength="255" placeholder="Imię i nazwisko">
                        </div>
                        <div class="pe-form-group">
                            <label class="pe-form-label">Koszt naprawy</label>
                            <input type="number" id="incident-cost" class="pe-form-input" min="0" step="1" placeholder="PLN">
                        </div>
                        <div class="pe-form-group">
                            <label class="pe-form-label">Data</label>
                            <input type="date" id="incident-date" class="pe-form-input" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <button type="button" class="pe-maint-add-btn" id="incident-add">Dodaj</button>
                    </div>
                    <div id="incident-error" class="pe-row-error"></div>

                    <table class="pe-table" style="margin-top:20px;">
                        <thead><tr><th>Data</th><th>Opis</th><th>Zgłaszający</th><th class="right">Koszt</th><th class="right"></th></tr></thead>
                        <tbody id="rd-incidents-body"><tr><td colspan="5" class="pe-empty">Ładowanie…</td></tr></tbody>
                    </table>
                </div>

            </div>
        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}

<script>
(function () {
    'use strict';

    const RESERVATION_ID = {{ (int) $id }};
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
    };

    let currentReservation = null;

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
        return d.toLocaleDateString('pl-PL', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    function formatPrice(price) {
        return `${Number(price ?? 0).toLocaleString('pl-PL')} zł`;
    }

    function statusClass(status) {
        if (status === 'active' || status === 'rented') return 'rented';
        if (['late', 'overdue', 'repair', 'cancelled'].includes(status)) return 'late';
        return 'confirmed';
    }

    function renderReservation(reservation) {
        currentReservation = reservation;

        document.getElementById('rd-title').textContent = `Rezerwacja #${reservation.id}`;

        const statusBadge = document.getElementById('rd-status-badge');
        statusBadge.className = `lr-badge ${statusClass(reservation.statusOfReservation)}`;
        statusBadge.textContent = reservation.statusLabel ?? reservation.statusOfReservation;

        const thumb = document.getElementById('rd-product-thumb');
        thumb.innerHTML = reservation.product?.thumbnailUrl
            ? `<img src="${escapeHtml(reservation.product.thumbnailUrl)}" alt="">`
            : '';
        document.getElementById('rd-product-title').textContent = reservation.product?.title ?? 'Brak danych';
        document.getElementById('rd-product-serial').textContent = reservation.product?.serialNumber ?? '';

        const avatar = document.getElementById('rd-client-avatar');
        avatar.innerHTML = reservation.client?.avatar
            ? `<img src="${escapeHtml(reservation.client.avatar)}" alt="" style="border-radius:50%;">`
            : '';
        document.getElementById('rd-client-name').textContent = reservation.client?.name || 'Brak danych';
        document.getElementById('rd-client-email').textContent = reservation.client?.email ?? '';
        document.getElementById('rd-client-phone').textContent = reservation.client?.telephoneNumber ?? 'Brak numeru telefonu';

        document.getElementById('rd-period').textContent =
            `${formatDate(reservation.rentalPeriod?.startDate)} → ${formatDate(reservation.rentalPeriod?.endDate)}`;
        document.getElementById('rd-days').textContent = `${reservation.rentalPeriod?.days ?? 0} dni`;

        document.getElementById('rd-price').textContent = formatPrice(reservation.totalPrice);
        document.getElementById('rd-created-at').textContent = `Utworzono: ${formatDate(reservation.createdAt)}`;

        document.getElementById('rd-status-select').value = reservation.statusOfReservation;
    }

    async function loadReservation() {
        try {
            const res = await fetch(`/api/admin/reservations/${RESERVATION_ID}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const json = await res.json();
            renderReservation(json.data);
        } catch (e) {
            console.error('Reservation details API:', e);
            document.getElementById('rd-title').textContent = 'Nie udało się wczytać rezerwacji.';
        }
    }

    // ===== Zmiana statusu =====
    document.getElementById('rd-status-save').addEventListener('click', async () => {
        const select = document.getElementById('rd-status-select');
        const messageEl = document.getElementById('rd-status-message');
        const btn = document.getElementById('rd-status-save');

        btn.disabled = true;
        messageEl.innerHTML = '';

        try {
            const res = await fetch(`/api/admin/reservations/${RESERVATION_ID}`, {
                method: 'PATCH',
                headers,
                credentials: 'same-origin',
                body: JSON.stringify({ statusOfReservation: select.value }),
            });
            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                messageEl.innerHTML = `<div class="rd-alert error">${escapeHtml(data.message || 'Nie udało się zmienić statusu.')}</div>`;
                return;
            }

            renderReservation(data.data);
            messageEl.innerHTML = `<div class="rd-alert success">Status został zaktualizowany.</div>`;
        } catch (e) {
            console.error('Status update:', e);
            messageEl.innerHTML = `<div class="rd-alert error">Błąd sieci.</div>`;
        } finally {
            btn.disabled = false;
        }
    });

    // ===== Incydenty =====
    async function loadIncidents() {
        const body = document.getElementById('rd-incidents-body');
        try {
            const res = await fetch(`/api/admin/reservations/${RESERVATION_ID}/incidents`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Błąd');

            body.innerHTML = data.data.length
                ? data.data.map(x => `
                    <tr>
                        <td>${formatDate(x.createdAt)}</td>
                        <td>${escapeHtml(x.description)}</td>
                        <td>${escapeHtml(x.serviceman_name || '—')}</td>
                        <td class="right">${Number(x.repairCost || 0).toLocaleString('pl-PL')} zł</td>
                        <td class="right"><button type="button" class="pe-delete-repair" data-repair-id="${x.id}">Usuń</button></td>
                    </tr>`).join('')
                : '<tr><td colspan="5" class="pe-empty">Brak zgłoszonych incydentów dla tej rezerwacji.</td></tr>';
        } catch (e) {
            body.innerHTML = '<tr><td colspan="5" class="pe-empty">Nie udało się pobrać incydentów.</td></tr>';
            console.error(e);
        }
    }

    document.getElementById('incident-add').addEventListener('click', async () => {
        const description = document.getElementById('incident-description').value.trim();
        const serviceman = document.getElementById('incident-serviceman').value.trim();
        const cost = document.getElementById('incident-cost').value;
        const date = document.getElementById('incident-date').value;
        const errorEl = document.getElementById('incident-error');

        errorEl.textContent = '';

        if (!description || !serviceman || cost === '' || !date) {
            errorEl.textContent = 'Uzupełnij wszystkie pola.';
            return;
        }

        if (!currentReservation?.product?.id) {
            errorEl.textContent = 'Nie udało się ustalić produktu dla tej rezerwacji.';
            return;
        }

        try {
            const res = await fetch(`/produkt/${currentReservation.product.id}/naprawy`, {
                method: 'POST',
                headers,
                credentials: 'same-origin',
                body: JSON.stringify({
                    description,
                    serviceman_name: serviceman,
                    repairCost: Number(cost),
                    date,
                    reservationId: RESERVATION_ID,
                }),
            });
            const data = await res.json().catch(() => ({}));

            if (!res.ok) throw new Error(data.message || 'Nie udało się dodać incydentu.');

            document.getElementById('incident-description').value = '';
            document.getElementById('incident-serviceman').value = '';
            document.getElementById('incident-cost').value = '';

            loadIncidents();
        } catch (e) {
            errorEl.textContent = e.message;
        }
    });

    document.getElementById('rd-incidents-body').addEventListener('click', async (e) => {
        const btn = e.target.closest('.pe-delete-repair');
        if (!btn) return;

        if (!confirm('Czy na pewno usunąć ten wpis?')) return;
        if (!currentReservation?.product?.id) return;

        const res = await fetch(`/produkt/${currentReservation.product.id}/naprawy/${btn.dataset.repairId}`, {
            method: 'DELETE',
            headers,
            credentials: 'same-origin',
        });

        if (res.ok) {
            loadIncidents();
        } else {
            alert('Nie udało się usunąć wpisu.');
        }
    });

    // START
    loadReservation().then(loadIncidents);
})();
</script>
</body>
</html>
