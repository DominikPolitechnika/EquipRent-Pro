<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Szczegóły klienta – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-user-details.css') }}">
    <style>
        /* ===== Modal blokady konta ===== */
        .ud-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .ud-modal-backdrop.open { display: flex; }

        .ud-modal {
            background: #fff;
            border-radius: 12px;
            width: 100%;
            max-width: 440px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .ud-modal-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #fee2e2;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            color: #dc2626;
        }
        .ud-modal-icon svg { width: 24px; height: 24px; }
        .ud-modal-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #2a3439;
            text-align: center;
            margin: 0 0 8px;
        }
        .ud-modal-text {
            font-size: 13px;
            color: #777;
            text-align: center;
            margin: 0 0 24px;
            line-height: 1.5;
        }
        .ud-modal-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .ud-modal-btn {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: 11px 22px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            min-width: 120px;
        }
        .ud-modal-btn-cancel {
            background: #fff;
            border: 1px solid #e8ebee;
            color: #555;
        }
        .ud-modal-btn-cancel:hover { border-color: #aaa; color: #2a3439; }
        .ud-modal-btn-confirm { background: #dc2626; color: #fff; }
        .ud-modal-btn-confirm:hover { background: #b91c1c; }

        /* ===== Modal edycji danych ===== */
        .ud-edit-row {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
        }
        .ud-edit-field {
            flex: 1;
            min-width: 0;
            margin-bottom: 12px;
        }
        .ud-edit-row .ud-edit-field { margin-bottom: 0; }
        .ud-edit-label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .ud-edit-input {
            box-sizing: border-box;
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
        }
        .ud-edit-input:focus {
            outline: none;
            border-color: #075071;
        }
        .ud-modal-btn-save { background: #075071; color: #fff; }
        .ud-modal-btn-save:hover { background: #0a638b; }
    </style>
</head>
<body>
<div class="adm-shell">
    @include('partials.admin-sidebar')

    <div class="adm-body">
        @include('partials.admin-topbar')

        <div class="adm-content">
            <div class="ud-content">

                {{-- ===== KARTA PROFILU ===== --}}
                <div class="ud-profile-card">
                    <div class="ud-profile-top">
                        <div class="ud-profile-main">
                            <div class="ud-avatar" id="ud-avatar">
                                <span class="ud-skel" style="width:36px;height:24px;background:rgba(26,111,168,.25);"></span>
                            </div>
                            <div class="ud-profile-info">
                                <h2 class="ud-profile-name" id="ud-name"><span class="ud-skel" style="width:200px;height:24px;"></span></h2>
                                <div class="ud-profile-row">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                    <span id="ud-email"><span class="ud-skel" style="width:180px;height:13px;"></span></span>
                                </div>
                                <div class="ud-profile-row">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    <span id="ud-phone"><span class="ud-skel" style="width:160px;height:13px;"></span></span>
                                </div>
                            </div>
                            <div class="ud-profile-actions">
                                <button type="button" class="ud-btn ud-btn-edit" id="ud-edit-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edytuj dane
                                </button>
                                <button type="button" class="ud-btn ud-btn-block" id="ud-block-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                                    </svg>
                                    <span id="ud-block-btn-label">Zablokuj konto</span>
                                </button>
                            </div>
                        </div>

                        {{-- Karta finansowa --}}
                        <div class="ud-finance">
                            <div class="ud-finance-title">Podsumowanie Finansowe</div>
                            <div class="ud-finance-label">Łączna wartość wynajmów</div>
                            <div class="ud-finance-value" id="ud-total-spent"><span class="ud-skel ud-skel-light" style="width:130px;height:26px;"></span></div>
                            <div class="ud-finance-label">Liczba wypożyczeń</div>
                            <div class="ud-finance-value" id="ud-reservations-count"><span class="ud-skel ud-skel-light" style="width:100px;height:26px;"></span></div>
                            <svg class="ud-finance-icon" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5">
                                <rect x="1" y="4" width="22" height="16" rx="2"/>
                                <line x1="1" y1="10" x2="23" y2="10"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Metryki --}}
                    <div class="ud-metrics">
                        <div>
                            <div class="ud-metric-label">Status konta</div>
                            <span class="ud-metric-badge" id="ud-status-badge">
                                <span class="ud-skel" style="width:50px;height:11px;background:rgba(22,163,74,.25);"></span>
                            </span>
                        </div>
                        <div>
                            <div class="ud-metric-label">Data dołączenia</div>
                            <div class="ud-metric-value" id="ud-created-at"><span class="ud-skel" style="width:90px;height:13px;"></span></div>
                        </div>
                        <div>
                            <div class="ud-metric-label">Weryfikacja</div>
                            <div class="ud-metric-verified" id="ud-verified">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                                <span id="ud-verified-text"><span class="ud-skel" style="width:80px;height:13px;"></span></span>
                            </div>
                        </div>
                        <div>
                            <div class="ud-metric-label">Ostatnie logowanie</div>
                            <div class="ud-metric-value" id="ud-last-login"><span class="ud-skel" style="width:100px;height:13px;"></span></div>
                        </div>
                    </div>
                </div>

                {{-- ===== HISTORIA WYPOŻYCZEŃ ===== --}}
                <div class="ud-section">
                    <div class="ud-section-header">
                        <div class="ud-section-title">Pełna historia wypożyczeń</div>
                        <div class="ud-section-actions">
                            <button type="button" class="ud-icon-btn" aria-label="Filtruj">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                                </svg>
                            </button>
                            <button type="button" class="ud-icon-btn" aria-label="Pobierz">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <table class="ud-table">
                        <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Data</th>
                                <th class="center">Status</th>
                                <th class="right">Kwota</th>
                            </tr>
                        </thead>
                        <tbody id="ud-history-tbody">
                            <tr>
                                <td>
                                    <div class="ud-product">
                                        <div class="ud-product-img"></div>
                                        <span class="ud-product-name"><span class="ud-skel" style="width:170px;height:13px;"></span></span>
                                    </div>
                                </td>
                                <td><span class="ud-skel" style="width:150px;height:13px;"></span></td>
                                <td class="center"><span class="ud-badge active"><span class="ud-skel" style="width:60px;height:10px;background:rgba(26,111,168,.25);"></span></span></td>
                                <td class="right"><span class="ud-skel" style="width:70px;height:13px;"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- ===== INCYDENTY ===== --}}
                <div class="ud-section">
                    <div class="ud-section-header">
                        <div class="ud-section-title ud-section-title-warn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            Uszkodzone zwroty / Incydenty
                        </div>
                    </div>

                    <div class="ud-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <div class="ud-empty-text">Brak zgłoszonych incydentów</div>
                    </div>
                </div>

            </div>
        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}
{{-- ========================= MODAL BLOKADY KONTA ========================= --}}
<div class="ud-modal-backdrop" id="ud-block-modal">
    <div class="ud-modal">
        <div class="ud-modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>
        <h3 class="ud-modal-title" id="ud-modal-title">Zablokować konto?</h3>
        <p class="ud-modal-text" id="ud-modal-text">
            Czy na pewno chcesz zablokować to konto?<br>
            Użytkownik straci dostęp do platformy do momentu odblokowania.
        </p>
        <div class="ud-modal-actions">
            <button type="button" class="ud-modal-btn ud-modal-btn-cancel" id="ud-modal-cancel">Wróć</button>
            <button type="button" class="ud-modal-btn ud-modal-btn-confirm" id="ud-modal-confirm">Tak, zablokuj</button>
        </div>
    </div>
</div>

{{-- ========================= MODAL EDYCJI DANYCH ========================= --}}
<div class="ud-modal-backdrop" id="ud-edit-modal">
    <div class="ud-modal" style="max-width:480px;">
        <h3 class="ud-modal-title" style="margin-bottom:18px;">Edytuj dane użytkownika</h3>

        <form id="ud-edit-form">
            <div class="ud-edit-row">
                <div class="ud-edit-field">
                    <label class="ud-edit-label" for="ud-edit-name">Imię</label>
                    <input type="text" id="ud-edit-name" name="name" class="ud-edit-input" required>
                </div>
                <div class="ud-edit-field">
                    <label class="ud-edit-label" for="ud-edit-surname">Nazwisko</label>
                    <input type="text" id="ud-edit-surname" name="surname" class="ud-edit-input" required>
                </div>
            </div>
            <div class="ud-edit-field">
                <label class="ud-edit-label" for="ud-edit-email">Adres e-mail</label>
                <input type="email" id="ud-edit-email" name="email" class="ud-edit-input" required>
            </div>
            <div class="ud-edit-field">
                <label class="ud-edit-label" for="ud-edit-phone">Telefon</label>
                <input type="text" id="ud-edit-phone" name="telephone_number" class="ud-edit-input">
            </div>
            <div class="ud-edit-field" style="margin-bottom:18px;">
                <label class="ud-edit-label" for="ud-edit-klub">Klub sportowy</label>
                <input type="text" id="ud-edit-klub" name="klub" class="ud-edit-input">
            </div>

            <p id="ud-edit-error" style="display:none; color:#dc2626; font-size:12px; margin:0 0 14px;"></p>

            <div class="ud-modal-actions">
                <button type="button" class="ud-modal-btn ud-modal-btn-cancel" id="ud-edit-cancel">Anuluj</button>
                <button type="submit" class="ud-modal-btn ud-modal-btn-save" id="ud-edit-submit">Zapisz zmiany</button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    'use strict';

    const USER_ID = {{ (int) $id }};
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    function apiGet(url) {
        return fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
    }
    function apiJson(method, url, body) {
        return fetch(url, {
            method,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            credentials: 'same-origin',
            body: body ? JSON.stringify(body) : undefined,
        });
    }

    function escapeHtml(s) {
        if (s === null || s === undefined) return '';
        return String(s)
            .replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;').replaceAll("'", '&#039;');
    }

    function formatDate(iso, opts) {
        if (!iso) return '—';
        const d = new Date(iso);
        if (isNaN(d)) return String(iso);
        return d.toLocaleDateString('pl-PL', opts || { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function formatMoney(v) {
        const num = Number(v ?? 0);
        return num.toLocaleString('pl-PL') + ' zł';
    }

    function initials(name, surname) {
        return ((name || '').charAt(0) + (surname || '').charAt(0)).toUpperCase() || '?';
    }

    let currentUser = null;

    // ===== Historia wypożyczeń =====
    function statusBadge(status, endDate) {
        const s = String(status || '').toLowerCase();
        const cancelled = ['cancelled', 'canceled', 'anulowana'].includes(s);
        const completed = ['completed', 'finished', 'zakończona', 'returned', 'zwrócona'].includes(s);
        const late = !completed && !cancelled && endDate && new Date(endDate) < new Date();

        if (cancelled) return '<span class="ud-badge late">Anulowana</span>';
        if (completed) return '<span class="ud-badge returned">Zwrócona</span>';
        if (late) return '<span class="ud-badge late">Po terminie</span>';
        return '<span class="ud-badge active">Aktywna</span>';
    }

    function renderHistoryRow(r) {
        return `
        <tr>
            <td>
                <div class="ud-product">
                    <div class="ud-product-img" style="${r.productThumbnailUrl ? `background-image:url('${escapeHtml(r.productThumbnailUrl)}');background-size:cover;background-position:center;` : ''}"></div>
                    <span class="ud-product-name">${escapeHtml(r.productTitle)}</span>
                </div>
            </td>
            <td>${formatDate(r.startDate, { day: '2-digit', month: '2-digit', year: 'numeric' })} — ${formatDate(r.endDate, { day: '2-digit', month: '2-digit', year: 'numeric' })}</td>
            <td class="center">${statusBadge(r.statusOfReservation, r.endDate)}</td>
            <td class="right">${formatMoney(r.totalPrice)}</td>
        </tr>`;
    }

    // ===== Render profilu =====
    function renderProfile(u) {
        currentUser = u;

        const avatar = document.getElementById('ud-avatar');
        if (u.avatarUrl) {
            avatar.innerHTML = `<img src="${escapeHtml(u.avatarUrl)}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">`;
        } else {
            avatar.textContent = initials(u.name, u.surname);
        }

        document.getElementById('ud-name').textContent = u.fullName || u.name;
        document.getElementById('ud-email').textContent = u.email;
        document.getElementById('ud-phone').textContent = u.telephoneNumber || 'Brak numeru telefonu';

        document.getElementById('ud-total-spent').textContent = formatMoney(u.totalSpent);
        document.getElementById('ud-reservations-count').textContent = String((u.reservations || []).length);

        const statusBadgeEl = document.getElementById('ud-status-badge');
        statusBadgeEl.textContent = u.isBlocked ? 'Zablokowany' : 'Aktywny';
        statusBadgeEl.style.background = u.isBlocked ? '#fee2e2' : '#dcfce7';
        statusBadgeEl.style.color = u.isBlocked ? '#dc2626' : '#16a34a';

        document.getElementById('ud-created-at').textContent = formatDate(u.createdAt);
        document.getElementById('ud-verified-text').textContent = u.emailVerifiedAt ? 'Zweryfikowany' : 'Niezweryfikowany';
        document.getElementById('ud-last-login').textContent = u.lastLogin ? formatDate(u.lastLogin) : 'Nigdy się nie zalogował';

        const tbody = document.getElementById('ud-history-tbody');
        const reservations = u.reservations || [];
        tbody.innerHTML = reservations.length
            ? reservations.map(renderHistoryRow).join('')
            : '<tr><td colspan="4" style="text-align:center;color:#9aa5ad;padding:20px;">Brak historii wypożyczeń.</td></tr>';

        const blockLabel = document.getElementById('ud-block-btn-label');
        blockLabel.textContent = u.isBlocked ? 'Odblokuj konto' : 'Zablokuj konto';

        document.getElementById('ud-modal-title').textContent = u.isBlocked ? 'Odblokować konto?' : 'Zablokować konto?';
        document.getElementById('ud-modal-text').innerHTML = u.isBlocked
            ? 'Czy na pewno chcesz odblokować to konto?<br>Użytkownik odzyska dostęp do platformy.'
            : 'Czy na pewno chcesz zablokować to konto?<br>Użytkownik straci dostęp do platformy do momentu odblokowania.';
        document.getElementById('ud-modal-confirm').textContent = u.isBlocked ? 'Tak, odblokuj' : 'Tak, zablokuj';
    }

    async function loadUser() {
        try {
            const res = await apiGet(`/api/users/${USER_ID}`);
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const json = await res.json();
            renderProfile(json.data);
        } catch (e) {
            document.getElementById('ud-name').textContent = 'Nie udało się wczytać użytkownika.';
            console.error(e);
        }
    }

    // ===== Modal blokady =====
    const blockModal = document.getElementById('ud-block-modal');
    const blockTrigger = document.getElementById('ud-block-btn');
    const blockCancel = document.getElementById('ud-modal-cancel');
    const blockConfirm = document.getElementById('ud-modal-confirm');

    function openBlockModal() { blockModal.classList.add('open'); }
    function closeBlockModal() { blockModal.classList.remove('open'); }

    blockTrigger.addEventListener('click', openBlockModal);
    blockCancel.addEventListener('click', closeBlockModal);
    blockModal.addEventListener('click', (e) => { if (e.target === blockModal) closeBlockModal(); });

    blockConfirm.addEventListener('click', async () => {
        blockConfirm.disabled = true;
        try {
            const res = await apiJson('PATCH', `/api/users/${USER_ID}/toggle-block`);
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                alert(data.message || 'Nie udało się zmienić statusu konta.');
                return;
            }
            closeBlockModal();
            loadUser();
        } catch (e) {
            alert('Błąd sieci.');
            console.error(e);
        } finally {
            blockConfirm.disabled = false;
        }
    });

    // ===== Modal edycji =====
    const editModal = document.getElementById('ud-edit-modal');
    const editTrigger = document.getElementById('ud-edit-btn');
    const editCancel = document.getElementById('ud-edit-cancel');
    const editForm = document.getElementById('ud-edit-form');
    const editError = document.getElementById('ud-edit-error');

    function openEditModal() {
        if (!currentUser) return;
        document.getElementById('ud-edit-name').value = currentUser.name || '';
        document.getElementById('ud-edit-surname').value = currentUser.surname || '';
        document.getElementById('ud-edit-email').value = currentUser.email || '';
        document.getElementById('ud-edit-phone').value = currentUser.telephoneNumber || '';
        document.getElementById('ud-edit-klub').value = currentUser.klub || '';
        editError.style.display = 'none';
        editModal.classList.add('open');
    }
    function closeEditModal() { editModal.classList.remove('open'); }

    editTrigger.addEventListener('click', openEditModal);
    editCancel.addEventListener('click', closeEditModal);
    editModal.addEventListener('click', (e) => { if (e.target === editModal) closeEditModal(); });

    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        editError.style.display = 'none';

        const payload = {
            name: document.getElementById('ud-edit-name').value.trim(),
            surname: document.getElementById('ud-edit-surname').value.trim(),
            email: document.getElementById('ud-edit-email').value.trim(),
            telephone_number: document.getElementById('ud-edit-phone').value.trim() || null,
            klub: document.getElementById('ud-edit-klub').value.trim() || null,
        };

        const submitBtn = document.getElementById('ud-edit-submit');
        submitBtn.disabled = true;
        try {
            const res = await apiJson('PATCH', `/api/users/${USER_ID}`, payload);
            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : null;
                editError.textContent = firstError || data.message || 'Nie udało się zapisać zmian.';
                editError.style.display = 'block';
                return;
            }

            closeEditModal();
            loadUser();
        } catch (err) {
            editError.textContent = 'Błąd sieci.';
            editError.style.display = 'block';
            console.error(err);
        } finally {
            submitBtn.disabled = false;
        }
    });

    // Escape zamyka dowolny otwarty modal
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        if (blockModal.classList.contains('open')) closeBlockModal();
        if (editModal.classList.contains('open')) closeEditModal();
    });

    document.addEventListener('DOMContentLoaded', loadUser);
})();
</script>
</body>
</html>