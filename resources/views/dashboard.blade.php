<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Główny – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-dashboard.css') }}">
</head>
<body>
<div class="adm-shell">
    @include('partials.admin-sidebar')

    <div class="adm-body">
        @include('partials.admin-topbar')

        <div class="adm-content">
            <div class="db-content">

                {{-- NAGŁÓWEK --}}
                <div class="le-breadcrumb">
                    <span>Zarządzanie</span>
                    <span>›</span>
                    <span class="active">Panel Główny</span>
                </div>
                <h1 class="le-title">Panel Główny</h1>

                {{-- ===== GÓRNE KAFLE ===== --}}
                <div class="db-stats">

                    {{-- Aktywne wynajmy --}}
                    <div class="db-stat">
                        <div class="db-stat-decor"></div>
                        <div class="db-stat-label">Aktywne wynajmy</div>
                        <div class="db-stat-value">
                            <span id="reservations-current">0</span>

                            <span class="db-stat-trend">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="17 6 23 6 23 12"/>
                                    <line x1="23" y1="6" x2="13" y2="16"/>
                                    <line x1="13" y1="16" x2="8" y2="11"/>
                                    <line x1="8" y1="11" x2="1" y2="18"/>
                                </svg>

                                <span id="reservations-change">0%</span>
                            </span>
                        </div>
                        <div class="db-stat-meta">
                            <span class="dot"></span>
                            <span id="reservations-previous">Poprzedni miesiąc: 0</span>
                        </div>
                    </div>

                    {{-- Przychód miesięczny --}}
                    <div class="db-stat">
                        <div class="db-stat-label">Przychód miesięczny</div>

                        <div class="db-stat-value">
                            <span id="revenue-current">0 zł</span>

                            <span class="db-stat-trend">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="17 6 23 6 23 12"/>
                                    <line x1="23" y1="6" x2="13" y2="16"/>
                                    <line x1="13" y1="16" x2="8" y2="11"/>
                                    <line x1="8" y1="11" x2="1" y2="18"/>
                                </svg>

                                <span id="revenue-change">0%</span>
                            </span>
                        </div>

                        <div class="db-progress">
                            <div class="db-progress-bar" style="width:82%"></div>
                        </div>

                        <div class="db-progress-label">
                            <span id="revenue-previous">Poprzedni miesiąc: 0 zł</span>
                        </div>
                    </div>

                    {{-- Zarobki w tym tygodniu --}}
                    <div class="db-stat dark">
                        <div class="db-stat-label">
                            Zarobki w twoim tygodniu

                            <svg viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                                <polyline points="17 6 23 6 23 12"/>
                            </svg>
                        </div>

                        <div class="db-dark-title" id="weekly-income-total">
                            0 zł
                        </div>

                        <div class="db-dark-text">
                            Dochód z ostatnich 7 dni
                        </div>

                        <div class="db-mini-chart">
                            <div class="bar" id="income-day-0" style="height:0%"></div>
                            <div class="bar" id="income-day-1" style="height:0%"></div>
                            <div class="bar" id="income-day-2" style="height:0%"></div>
                            <div class="bar" id="income-day-3" style="height:0%"></div>
                            <div class="bar" id="income-day-4" style="height:0%"></div>
                            <div class="bar" id="income-day-5" style="height:0%"></div>
                            <div class="bar" id="income-day-6" style="height:0%"></div>
                        </div>
                    </div>
                </div>

                {{-- ===== DWA PANELE - OSTATNIE REZERWACJE + POPULARNY SPRZĘT ===== --}}
                <div class="db-grid-2">

                    {{-- Ostatnie Rezerwacje --}}
                    <div>
                        <div class="db-section-header">
                            <div class="db-section-title">Ostatnie Rezerwacje</div>
                            <a href="{{ route('rentals.list') }}" class="db-section-link">Pokaż wszystkie</a>
                        </div>

                        <div class="db-card">
                            <table class="db-table">
                                <thead>
                                    <tr>
                                        <th>Klient</th>
                                        <th>Sprzęt</th>
                                        <th>Okres</th>
                                        <th>Status</th>
                                        <th>Akcja</th>
                                    </tr>
                                </thead>
                                <tbody id="latest-reservations">
                                </tbody>    
                            </table>
                        </div>
                    </div>

                    {{-- Popularny Sprzęt --}}
                    <div>
                        <div class="db-section-header">
                            <div class="db-section-title">Popularny Sprzęt</div>
                        </div>

                        <div class="db-pop-list" id="popular-products"></div>
                    </div>

                </div>

            </div>
        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}

<script>
document.addEventListener('DOMContentLoaded', () => {

    // =====================================================
    // LICZBA REZERWACJI
    // =====================================================

    fetch('/api/statistics/reservations-count', {
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Błąd liczby rezerwacji: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        const current = document.getElementById('reservations-current');
        const previous = document.getElementById('reservations-previous');
        const change = document.getElementById('reservations-change');

        if (current) {
            current.textContent = data.currentMonth;
        }

        if (previous) {
            previous.textContent = data.previousMonth;
        }

        if (change) {
            change.textContent = `${data.percentageChange}%`;
        }
    })
    .catch(error => {
        console.error('Reservations count API:', error);
    });


    // =====================================================
    // PRZYCHÓD MIESIĘCZNY
    // =====================================================

    fetch('/api/statistics/monthly-revenue', {
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Błąd przychodu miesięcznego: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        const current = document.getElementById('revenue-current');
        const previous = document.getElementById('revenue-previous');
        const change = document.getElementById('revenue-change');

        if (current) {
            current.textContent = `${data.currentMonth} zł`;
        }

        if (previous) {
            previous.textContent = `${data.previousMonth} zł`;
        }

        if (change) {
            change.textContent = `${data.percentageChange}%`;
        }
    })
    .catch(error => {
        console.error('Monthly revenue API:', error);
    });


    // =====================================================
    // DOCHÓD Z OSTATNICH 7 DNI
    // =====================================================

    fetch('/api/statistics/weekly-income', {
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Błąd tygodniowego dochodu: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
    const total = document.getElementById('weekly-income-total');

        if (total) {
            total.textContent = `${data.totalIncome} zł`;
        }

        if (!data.days || data.days.length === 0) {
            return;
        }

        const maxIncome = Math.max(
            ...data.days.map(day => Number(day.income ?? 0))
        );

        data.days.forEach((day, index) => {
            const bar = document.getElementById(`income-day-${index}`);

            if (!bar) {
                return;
            }

            const income = Number(day.income ?? 0);

            let height = 0;

            if (maxIncome > 0) {
                height = (income / maxIncome) * 100;
            }

            bar.style.height = `${height}%`;
        });
    })
    .catch(error => {
        console.error('Weekly income API:', error);
    });


    // =====================================================
    // POPULARNY SPRZĘT
    // =====================================================

    fetch('/api/statistics/top-products', {
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Błąd popularnych produktów: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        const productsContainer =
            document.getElementById('popular-products');

        if (!productsContainer) {
            return;
        }

        productsContainer.innerHTML = '';

        if (!data.products || data.products.length === 0) {
            productsContainer.innerHTML = `
                <div style="
                    width: 100%;
                    text-align: center;
                    padding: 30px;
                    font-size: 12px;
                ">
                    Brak popularnych produktów w tym miesiącu
                </div>
            `;

            return;
        }

        data.products.forEach(product => {

            const statusClass =
                product.status === 'Dostępny'
                    ? 'available'
                    : 'reserved';

            const card = document.createElement('div');
            card.className = 'db-pop-card';

            card.innerHTML = `
                <div class="db-pop-img">
                    ${
                        product.thumbnailUrl
                            ? `
                                <img
                                    src="${product.thumbnailUrl}"
                                    alt="${product.title}"
                                    style="
                                        width:100%;
                                        height:100%;
                                        object-fit:cover;
                                    "
                                >
                              `
                            : ''
                    }
                </div>

                <div class="db-pop-body">

                    <div class="db-pop-name">
                        ${product.title}
                    </div>

                    <div class="db-pop-meta">
                        ${product.rentalsCount} rezerwacji
                    </div>

                    <div class="db-pop-footer">

                        <span class="db-pop-price">
                            ${product.oneDayPrice} zł / dzień
                        </span>

                        <span class="db-pop-status ${statusClass}">
                            ${product.status}
                        </span>

                    </div>

                </div>
            `;

            productsContainer.appendChild(card);
        });
    })
    .catch(error => {
        console.error('Top products API:', error);
    });


    // =====================================================
    // OSTATNIE REZERWACJE
    // =====================================================

    fetch('/api/statistics/latest-reservations', {
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Błąd ostatnich rezerwacji: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        const reservationsContainer =
            document.getElementById('latest-reservations');

        if (!reservationsContainer) {
            return;
        }

        reservationsContainer.innerHTML = '';

        if (!data.reservations || data.reservations.length === 0) {
            reservationsContainer.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align:center; padding:30px;">
                        Brak ostatnich rezerwacji
                    </td>
                </tr>
            `;

            return;
        }

        data.reservations.forEach(reservation => {

            const initials =
                `${reservation.name?.charAt(0) ?? ''}${reservation.surname?.charAt(0) ?? ''}`
                    .toUpperCase();

            let statusClass = 'waiting';
            let statusLabel = reservation.status;

            if (
                reservation.status === 'active' ||
                reservation.status === 'confirmed'
            ) {
                statusClass = 'rented';
                statusLabel = 'Aktywna';
            }

            if (
                reservation.status === 'pending' ||
                reservation.status === 'awaiting_payment'
            ) {
                statusClass = 'waiting';
                statusLabel = 'Oczekująca';
            }

            if (
                reservation.status === 'completed' ||
                reservation.status === 'returned'
            ) {
                statusClass = 'returned';
                statusLabel = 'Zakończona';
            }

            if (reservation.status === 'cancelled') {
                statusClass = 'returned';
                statusLabel = 'Anulowana';
            }

            const row = document.createElement('tr');

            row.innerHTML = `
                <td>
                    <div class="db-client">

                        ${
                            reservation.avatarUrl
                                ? `
                                    <div class="db-mono">
                                        <img
                                            src="${reservation.avatarUrl}"
                                            alt="${reservation.name} ${reservation.surname}"
                                            style="
                                                width:100%;
                                                height:100%;
                                                object-fit:cover;
                                                border-radius:50%;
                                            "
                                        >
                                    </div>
                                  `
                                : `
                                    <div class="db-mono">
                                        ${initials}
                                    </div>
                                  `
                        }

                        <div>

                            <div class="db-client-name">
                                ${reservation.name ?? ''} ${reservation.surname ?? ''}
                            </div>

                            <div class="db-client-tag">
                                ${reservation.productCategory ?? ''}
                            </div>

                        </div>

                    </div>
                </td>

                <td>

                    <div class="db-equip-name">
                        ${reservation.productTitle ?? ''}
                    </div>

                    <div class="db-equip-cat">
                        ${reservation.productCategory ?? ''}
                    </div>

                </td>

                <td>
                    <div class="db-period">
                        ${reservation.startDate ?? ''}<br>
                        ${reservation.endDate ?? ''}
                    </div>
                </td>

                <td>
                    <span class="db-badge ${statusClass}">
                        ${statusLabel}
                    </span>
                </td>

                <td>
                    <button
                        type="button"
                        class="db-dots"
                        aria-label="Więcej"
                    >
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="5" cy="12" r="1.6"/>
                            <circle cx="12" cy="12" r="1.6"/>
                            <circle cx="19" cy="12" r="1.6"/>
                        </svg>
                    </button>
                </td>
            `;

            reservationsContainer.appendChild(row);
        });
    })
    .catch(error => {
        console.error('Latest reservations API:', error);
    });

});
</script>

</body>
</html>