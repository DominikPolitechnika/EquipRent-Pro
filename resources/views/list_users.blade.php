<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Zarejestrowani Klienci – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-list-users.css') }}">
</head>
<body>
<div class="adm-shell">
    @include('partials.admin-sidebar')

    <div class="adm-body">
        @include('partials.admin-topbar')

        <div class="adm-content">
            <div class="lu-content">

                {{-- BREADCRUMB --}}
           <div class="le-breadcrumb">
                    <span>Zarządzanie</span>
                    <span>›</span>
                    <span class="active">użytkownicy</span>
                </div>
                <h1 class="le-title">Użytkownicy</h1>

                {{-- NAGŁÓWEK --}}
                <div class="lu-header">
                    <div class="adm-search" style="max-width:380px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="lu-search" placeholder="Szukaj po imieniu, nazwisku, e-mailu lub telefonie...">
                    </div>
                </div>

                {{-- TABELA --}}
                <div class="lu-card">
                    <table class="lu-table">
                        <thead>
                            <tr>
                                <th>Profil Klienta</th>
                                <th>Komunikacja</th>
                                <th class="center">Aktywność</th>
                                <th>Rejestracja</th>
                                <th class="right">Administracja</th>
                            </tr>
                        </thead>
                        <tbody id="lu-tbody">
                            {{-- Skeleton wiersz - widoczny tylko do czasu fetch-a --}}
                            <tr data-skeleton>
                                <td>
                                    <div class="lu-profile">
                                        <div class="lu-avatar"><span class="lu-avatar-dot"></span></div>
                                        <div>
                                            <div class="lu-profile-name"><span class="lu-skel" style="width:130px;height:14px;"></span></div>
                                            <div class="lu-profile-role"><span class="lu-skel" style="width:100px;height:11px;"></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="lu-email"><span class="lu-skel" style="width:160px;height:13px;"></span></div>
                                    <div class="lu-tag"><span class="lu-skel" style="width:170px;height:9px;"></span></div>
                                </td>
                                <td>
                                    <div class="lu-activity">
                                        <div class="lu-activity-num"><span class="lu-skel" style="width:34px;height:20px;"></span></div>
                                        <div class="lu-activity-label"><span class="lu-skel" style="width:70px;height:9px;"></span></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="lu-reg-date"><span class="lu-skel" style="width:90px;height:13px;"></span></div>
                                    <div class="lu-reg-sub"><span class="lu-skel" style="width:110px;height:11px;"></span></div>
                                </td>
                                <td>
                                    <div class="lu-admin">
                                        <span class="lu-skel" style="width:70px;height:26px;border-radius:6px;"></span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- STOPKA + PAGINACJA --}}
                <div class="lu-footer">
                    <div class="lu-footer-count" id="lu-footer-count">
                        Ładowanie…
                    </div>
                    <div class="lu-pagination" id="lu-pagination"></div>
                </div>

            </div>
        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}

<script>
(function () {
    'use strict';

    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const tbody = document.getElementById('lu-tbody');
    const footerCount = document.getElementById('lu-footer-count');
    const pagination = document.getElementById('lu-pagination');
    const searchInput = document.getElementById('lu-search');

    const PAGE_SIZE = 10;
    let allUsers = [];
    let currentPage = 1;

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

    const blockIconSvg = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
        </svg>`;
    const unblockIconSvg = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>`;

    function renderRow(u) {
        const blocked = !!u.isBlocked;
        const tagHtml = blocked
            ? `<div class="lu-tag blocked">Zablokowany</div>`
            : (u.telephoneNumber
                ? `<div class="lu-tag">${escapeHtml(u.telephoneNumber)}</div>`
                : `<div class="lu-tag muted">Brak numeru telefonu</div>`);

        return `
        <tr data-user-id="${escapeHtml(u.id)}">
            <td>
                <div class="lu-profile">
                    <div class="lu-avatar"><span class="lu-avatar-dot ${blocked ? 'off' : ''}"></span></div>
                    <div>
                        <div class="lu-profile-name">${escapeHtml(u.fullName || u.name)}</div>
                        <div class="lu-profile-role">${escapeHtml(u.roleName || '—')}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="lu-email">${escapeHtml(u.email)}</div>
                ${tagHtml}
            </td>
            <td>
                <div class="lu-activity">
                    <div class="lu-activity-num">${escapeHtml(u.activityCount ?? 0)}</div>
                    <div class="lu-activity-label">Rezerwacji</div>
                </div>
            </td>
            <td>
                <div class="lu-reg-date">${formatDate(u.createdAt)}</div>
                <div class="lu-reg-sub">${u.lastLogin ? 'Ostatnio: ' + formatDate(u.lastLogin) : 'Nigdy się nie zalogował'}</div>
            </td>
            <td>
                <div class="lu-admin">
                    <a href="/uzytkownik-szczegoly/${escapeHtml(u.id)}" class="lu-details-btn">Szczegóły</a>
                    <button type="button" class="lu-toggle-icon ${blocked ? 'on' : ''}" data-action="toggle-block" data-id="${escapeHtml(u.id)}" aria-label="${blocked ? 'Odblokuj' : 'Zablokuj'}" title="${blocked ? 'Odblokuj użytkownika' : 'Zablokuj użytkownika'}">
                        ${blocked ? unblockIconSvg : blockIconSvg}
                    </button>
                </div>
            </td>
        </tr>`;
    }

    function paginationHtml(totalPages) {
        if (totalPages <= 1) return '';

        let html = `<a href="#" class="lu-page nav" data-page="${currentPage - 1}"${currentPage <= 1 ? ' style="pointer-events:none;opacity:.4;"' : ''}>‹</a>`;
        for (let p = 1; p <= totalPages; p++) {
            html += `<a href="#" class="lu-page ${p === currentPage ? 'active' : ''}" data-page="${p}">${p}</a>`;
        }
        html += `<a href="#" class="lu-page nav" data-page="${currentPage + 1}"${currentPage >= totalPages ? ' style="pointer-events:none;opacity:.4;"' : ''}>›</a>`;
        return html;
    }

    function render() {
        const query = searchInput.value.trim().toLowerCase();

        const filtered = query
            ? allUsers.filter((u) => {
                const haystack = [u.fullName, u.name, u.surname, u.email, u.telephoneNumber]
                    .filter(Boolean)
                    .join(' ')
                    .toLowerCase();
                return haystack.includes(query);
            })
            : allUsers;

        const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * PAGE_SIZE;
        const pageItems = filtered.slice(start, start + PAGE_SIZE);

        const emptyMessage = query ? 'Brak użytkowników pasujących do wyszukiwania.' : 'Brak zarejestrowanych użytkowników.';

        tbody.innerHTML = pageItems.length
            ? pageItems.map(renderRow).join('')
            : `<tr><td colspan="5" style="text-align:center;color:#6b7280;padding:24px;">${emptyMessage}</td></tr>`;

        footerCount.textContent = filtered.length
            ? `Wyświetlono ${pageItems.length} z ${filtered.length} użytkowników`
            : '';

        pagination.innerHTML = paginationHtml(totalPages);
    }

    async function loadUsers() {
        try {
            const response = await fetch('/api/users', {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });

            if (!response.ok) throw new Error('HTTP ' + response.status);

            const json = await response.json();
            allUsers = Array.isArray(json.data) ? json.data : [];
            currentPage = 1;

            render();
        } catch (e) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#6b7280;padding:24px;">Nie udało się pobrać listy użytkowników.</td></tr>';
            footerCount.textContent = '';
            pagination.innerHTML = '';
            console.error(e);
        }
    }

    searchInput.addEventListener('input', () => {
        currentPage = 1;
        render();
    });

    pagination.addEventListener('click', (e) => {
        e.preventDefault();
        const link = e.target.closest('[data-page]');
        if (!link) return;

        const page = parseInt(link.dataset.page, 10);
        if (isNaN(page) || page < 1) return;

        currentPage = page;
        render();
    });

    tbody.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action="toggle-block"]');
        if (!btn) return;

        const id = btn.dataset.id;
        const willBlock = !btn.classList.contains('on');
        const label = willBlock ? 'zablokować' : 'odblokować';
        if (!confirm(`Czy na pewno chcesz ${label} tego użytkownika?`)) return;

        btn.disabled = true;
        try {
            const response = await fetch(`/api/users/${id}/toggle-block`, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                credentials: 'same-origin',
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                alert(data.message || 'Nie udało się zmienić statusu użytkownika.');
                return;
            }

            loadUsers();
        } catch (err) {
            alert('Błąd sieci.');
            console.error(err);
        } finally {
            btn.disabled = false;
        }
    });

    document.addEventListener('DOMContentLoaded', loadUsers);
})();
</script>
</body>
</html>