<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rejestr Wypożyczeń – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('style-list-rentals.css') }}">
    <style>
        /* ===== Modal anulowania ===== */
        .lr-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .lr-modal-backdrop.open { display: flex; }

        .lr-modal {
            background: #fff;
            border-radius: 12px;
            width: 100%;
            max-width: 440px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .lr-modal-icon {
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
        .lr-modal-icon svg { width: 24px; height: 24px; }
        .lr-modal-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #2a3439;
            text-align: center;
            margin: 0 0 8px;
        }
        .lr-modal-text {
            font-size: 13px;
            color: #777;
            text-align: center;
            margin: 0 0 24px;
            line-height: 1.5;
        }
        .lr-modal-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .lr-modal-btn {
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
        .lr-modal-btn-cancel {
            background: #fff;
            border: 1px solid #e8ebee;
            color: #555;
        }
        .lr-modal-btn-cancel:hover { border-color: #aaa; color: #2a3439; }
        .lr-modal-btn-confirm { background: #dc2626; color: #fff; }
        .lr-modal-btn-confirm:hover { background: #b91c1c; }
    </style>
</head>
<body>
<div class="adm-shell">
    @include('partials.admin-sidebar')

    <div class="adm-body">
        @include('partials.admin-topbar')

        <div class="adm-content">
            <div class="lr-content">

                {{-- NAGŁÓWEK --}}
                 <div class="le-breadcrumb">
                    <span>Zarządzanie</span>
                    <span>›</span>
                    <span class="active">Rezerwacje</span>
                </div>
                <h1 class="le-title">Rezerwacje</h1>


                {{-- PASEK NARZĘDZI --}}
                <div class="lr-toolbar">
                    <div class="lr-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" id="reservation-search" placeholder="Szukaj klienta, ID faktury lub numeru seryjnego...">
                    </div>
                    <button type="button" class="lr-tool-btn">
                        Najnowsze
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <select id="status-filter" class="lr-tool-btn muted">
                        <option value="">Wszystkie statusy</option>
                        <option value="active">Aktywne</option>
                        <option value="completed">Oddane</option>
                        <option value="repair">Naprawa</option>
                        <option value="cancelled">Anulowane</option>
                    </select>
                    <button type="button" class="lr-tool-btn muted" id="export-csv-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Eksportuj CSV
                    </button>
                </div>

                <div id="reservations-list"></div>

                {{-- STOPKA --}}
                <div class="lr-footer">
                    <div
                        class="lr-footer-count"
                        id="reservations-count"
                    >
                        Wyświetlono 0 z 0 rezerwacji
                    </div>
                    <div
                        class="lr-pagination"
                        id="reservations-pagination"
                    ></div>
                </div>

            </div>
        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}
{{-- ========================= MODAL ANULOWANIA ========================= --}}
<div class="lr-modal-backdrop" id="lr-cancel-modal">
    <div class="lr-modal">
        <div class="lr-modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <h3 class="lr-modal-title">Anulować zamówienie?</h3>
        <p class="lr-modal-text">
            Czy na pewno chcesz anulować to zamówienie użytkownikowi?<br>
            Tej operacji nie można cofnąć.
        </p>
        <div class="lr-modal-actions">
            <button type="button" class="lr-modal-btn lr-modal-btn-cancel" id="lr-modal-cancel">Wróć</button>
            <button type="button" class="lr-modal-btn lr-modal-btn-confirm" id="lr-modal-confirm">Tak, anuluj</button>
        </div>
    </div>
</div>

<div class="lr-modal-backdrop" id="lr-edit-modal">
    <div class="lr-modal">
        <h3 class="lr-modal-title">Edytuj rezerwację</h3>

        <div class="lr-edit-form">

            <div class="lr-edit-info">
                <div>Klient: <strong id="edit-client">-</strong></div>
                <div>Sprzęt: <strong id="edit-product">-</strong></div>
            </div>

            <div class="lr-edit-field">
                <label for="edit-start-date">Data rozpoczęcia</label>
                <input type="date" id="edit-start-date">
            </div>

            <div class="lr-edit-field">
                <label for="edit-end-date">Data zakończenia</label>
                <input type="date" id="edit-end-date">
            </div>

            <div class="lr-edit-field">
                <label for="edit-status">Status</label>

                <select id="edit-status">
                    <option value="pending">Oczekująca</option>
                    <option value="confirmed">Zarezerwowana</option>
                    <option value="active">Aktywna</option>
                    <option value="completed">Zakończona</option>
                    <option value="cancelled">Anulowana</option>
                    <option value="repair">Naprawa</option>
                </select>
            </div>

        </div>

        <div class="lr-modal-actions" style="margin-top:24px;">
            <button
                type="button"
                class="lr-modal-btn lr-modal-btn-cancel"
                id="lr-edit-cancel"
            >
                Wróć
            </button>

            <button
                type="button"
                class="lr-modal-btn lr-modal-btn-confirm"
                id="lr-edit-save"
            >
                Zapisz
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    let allReservations = [];
    let currentPage = 1;

    const reservationsPerPage = 5;
    const reservationsCount = document.getElementById('reservations-count');

    const pagination = document.getElementById('reservations-pagination');

    const reservationsList = document.getElementById('reservations-list');
    const reservationSearch = document.getElementById('reservation-search');
    const statusFilter = document.getElementById('status-filter');

    const modal = document.getElementById('lr-cancel-modal');
    const btnCancel = document.getElementById('lr-modal-cancel');
    const btnConfirm = document.getElementById('lr-modal-confirm');

    const editModal = document.getElementById('lr-edit-modal');
    const editStartDate = document.getElementById('edit-start-date');
    const editEndDate = document.getElementById('edit-end-date');
    const editStatus = document.getElementById('edit-status');
    const editClient = document.getElementById('edit-client');
    const editProduct = document.getElementById('edit-product');
    const editCancel = document.getElementById('lr-edit-cancel');
    const editSave = document.getElementById('lr-edit-save');

    const exportCsvBtn = document.getElementById('export-csv-btn');

    let editedReservationId = null;


    // WCZYTANIE WSZYSTKICH REZERWACJI 

    function loadReservations() {

        const params = new URLSearchParams();

        const status = statusFilter.value;
        const search = reservationSearch.value.trim();

        if (status) {
            params.append('status', status);
        }

        if (search) {
            params.append('search', search);
        }

        let url = '/api/admin/reservations';

        if (params.toString()) {
            url += `?${params.toString()}`;
        }

        fetch(url, {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Błąd pobierania rezerwacji: ${response.status}`);
            }

            return response.json();
        })
        .then(response => {
            allReservations = response.data ?? [];

            currentPage = 1;

            renderCurrentPage();
        })
        .catch(error => {
            console.error('Reservations API:', error);

            reservationsList.innerHTML = `
                <div style="
                    text-align:center;
                    padding:40px;
                    font-size:14px;
                ">
                    Nie udało się wczytać rezerwacji
                </div>
            `;
        });
    }

    function renderCurrentPage() {

        const totalReservations = allReservations.length;

        const totalPages = Math.ceil(
            totalReservations / reservationsPerPage
        );

        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }

        const startIndex =
            (currentPage - 1) * reservationsPerPage;

        const endIndex =
            startIndex + reservationsPerPage;

        const pageReservations =
            allReservations.slice(startIndex, endIndex);

        renderReservations(pageReservations);

        updatePagination(
            totalReservations,
            totalPages,
            startIndex,
            pageReservations.length
        );
    }

    function updatePagination(
        totalReservations,
        totalPages,
        startIndex,
        visibleCount
    ) {

        if (totalReservations === 0) {
            reservationsCount.textContent =
                'Wyświetlono 0 z 0 rezerwacji';

            pagination.innerHTML = '';

            return;
        }

        const firstVisible = startIndex + 1;
        const lastVisible = startIndex + visibleCount;

        reservationsCount.textContent =
            `Wyświetlono ${firstVisible}–${lastVisible} z ${totalReservations} rezerwacji`;

        pagination.innerHTML = '';

        const prevButton = document.createElement('button');

        prevButton.type = 'button';
        prevButton.className = 'lr-page nav';
        prevButton.textContent = '‹';

        prevButton.disabled = currentPage === 1;

        prevButton.addEventListener('click', () => {

            if (currentPage > 1) {

                currentPage--;

                renderCurrentPage();
            }
        });

        pagination.appendChild(prevButton);


        for (let page = 1; page <= totalPages; page++) {

            const pageButton =
                document.createElement('button');

            pageButton.type = 'button';

            pageButton.className =
                page === currentPage
                    ? 'lr-page active'
                    : 'lr-page';

            pageButton.textContent = page;

            pageButton.addEventListener('click', () => {

                currentPage = page;

                renderCurrentPage();
            });

            pagination.appendChild(pageButton);
        }


        const nextButton = document.createElement('button');

        nextButton.type = 'button';
        nextButton.className = 'lr-page nav';
        nextButton.textContent = '›';

        nextButton.disabled =
            currentPage === totalPages;

        nextButton.addEventListener('click', () => {

            if (currentPage < totalPages) {

                currentPage++;

                renderCurrentPage();
            }
        });

        pagination.appendChild(nextButton);
    }


    // RENDEROWANIE KART

    function renderReservations(reservations) {
        reservationsList.innerHTML = '';

        if (reservations.length === 0) {
            reservationsList.innerHTML = `
                <div style="
                    text-align:center;
                    padding:40px;
                    font-size:14px;
                ">
                    Brak rezerwacji
                </div>
            `;

            return;
        }

        reservations.forEach(reservation => {

            const card = document.createElement('div');
            card.className = 'lr-card';

            let statusClass = 'confirmed';

            if (
                reservation.statusOfReservation === 'active' ||
                reservation.statusOfReservation === 'rented'
            ) {
                statusClass = 'rented';
            }

            if (
                reservation.statusOfReservation === 'late' ||
                reservation.statusOfReservation === 'overdue' ||
                reservation.statusOfReservation === 'repair' ||
                reservation.statusOfReservation === 'cancelled'
            ) {
                statusClass = 'late';
            }

            card.innerHTML = `
                <div class="lr-thumb">
                    ${
                        reservation.client?.avatar
                            ? `<img src="${reservation.client.avatar}" alt="${reservation.client.name ?? 'Klient'}">`
                            : ''
                    }
                </div>

                <div class="lr-col lr-col-client">
                    <span class="lr-col-label">Klient</span>

                    <span class="lr-col-val">
                        ${reservation.client?.name ?? 'Brak danych'}
                    </span>
                </div>

                <div class="lr-col lr-col-equip">
                    <span class="lr-col-label">Sprzęt</span>

                    <span class="lr-col-val">
                        ${reservation.product?.title ?? 'Brak danych'}
                    </span>

                    <span class="lr-col-sub">
                        ${reservation.product?.serialNumber ?? ''}
                    </span>
                </div>

                <div class="lr-col lr-col-period">
                    <span class="lr-col-label">Okres wynajmu</span>

                    <div class="lr-period">
                        <span>
                            ${formatDate(reservation.rentalPeriod?.startDate)}
                        </span>

                        <span class="arrow">→</span>

                        <span>
                            ${formatDate(reservation.rentalPeriod?.endDate)}
                        </span>
                    </div>
                </div>

                <div class="lr-col lr-col-value">
                    <span class="lr-col-label">Wartość całkowita</span>

                    <span class="lr-value-main">
                        ${formatPrice(reservation.totalPrice)}
                    </span>

                    <span class="lr-value-daily">
                        ${reservation.rentalPeriod?.days ?? 0} dni
                    </span>
                </div>

                <span class="lr-badge ${statusClass}">
                    ${reservation.statusLabel ?? reservation.statusOfReservation}
                </span>

                <div class="lr-actions">

                    <button
                        type="button"
                        class="lr-icon-btn lr-edit-btn"
                        aria-label="Edytuj"
                        data-id="${reservation.id}"
                    >
                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>

                    <button
                        type="button"
                        class="lr-icon-btn lr-cancel-btn"
                        aria-label="Anuluj"
                        data-id="${reservation.id}"
                    >
                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </button>

                    <button
                        type="button"
                        class="lr-action-btn soft"
                        data-id="${reservation.id}"
                    >
                        Szczegóły
                    </button>

                </div>
            `;

            reservationsList.appendChild(card);
        });
    }


    // KLIKNIĘCIA W KARTY

    reservationsList.addEventListener('click', event => {

        // EDYCJA
        const editButton = event.target.closest('.lr-edit-btn');

        if (editButton) {
            const reservationId = editButton.dataset.id;

            fetch(`/api/admin/reservations/${reservationId}`, {
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Błąd pobierania rezerwacji: ${response.status}`);
                }

                return response.json();
            })
            .then(response => {
                const reservation = response.data;

                editedReservationId = reservation.id;

                editStartDate.value =
                    reservation.rentalPeriod?.startDate
                        ? reservation.rentalPeriod.startDate.substring(0, 10)
                        : '';

                editEndDate.value =
                    reservation.rentalPeriod?.endDate
                        ? reservation.rentalPeriod.endDate.substring(0, 10)
                        : '';

                editStatus.value =
                    reservation.statusOfReservation ?? 'pending';

                editClient.textContent =
                    reservation.client?.name ?? 'Brak danych';

                editProduct.textContent =
                    reservation.product?.title ?? 'Brak danych';

                editModal.classList.add('open');
            })
            .catch(error => {
                console.error('Reservation details API:', error);
            });

            return;
        }


        // ANULOWANIE

        const cancelButton = event.target.closest('.lr-cancel-btn');

        if (cancelButton) {
            console.log('Rezerwacja do anulowania:', cancelButton.dataset.id);

            modal.classList.add('open');
        }
    });


    // FORMATOWANIE DATY

    function formatDate(date) {
        if (!date) {
            return '-';
        }

        const parsedDate = new Date(date);

        return parsedDate.toLocaleDateString('pl-PL', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }


    // FORMATOWANIE CENY

    function formatPrice(price) {
        const value = Number(price ?? 0);

        return `${value.toLocaleString('pl-PL')} zł`;
    }


    // MODAL ANULOWANIA

    btnCancel.addEventListener('click', () => {
        modal.classList.remove('open');
    });

    btnConfirm.addEventListener('click', () => {
        // Na razie nic nie wysyłamy
        modal.classList.remove('open');
    });

    modal.addEventListener('click', event => {
        if (event.target === modal) {
            modal.classList.remove('open');
        }
    });


    // MODAL EDYCJI

    editCancel.addEventListener('click', () => {
        editModal.classList.remove('open');
        editedReservationId = null;
    });

    editSave.addEventListener('click', () => {

        if (!editedReservationId) {
            return;
        }

        const startDate = editStartDate.value;
        const endDate = editEndDate.value;
        const statusOfReservation = editStatus.value;

        if (!startDate || !endDate) {
            alert('Uzupełnij obie daty.');
            return;
        }

        if (endDate < startDate) {
            alert('Data zakończenia nie może być wcześniejsza niż data rozpoczęcia.');
            return;
        }

        fetch(`/api/admin/reservations/${editedReservationId}`, {
            method: 'PATCH',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },

            credentials: 'same-origin',

            body: JSON.stringify({
                startDate: `${startDate} 00:00:00`,
                endDate: `${endDate} 23:59:59`,
                statusOfReservation: statusOfReservation
            })
        })
        .then(async response => {

            const data = await response.json();

            if (!response.ok) {

                console.error('PATCH error:', data);

                if (response.status === 422) {
                    alert('Niepoprawne dane rezerwacji.');
                    return;
                }

                throw new Error(
                    `Błąd edycji rezerwacji: ${response.status}`
                );
            }

            return data;
        })
        .then(data => {

            if (!data) {
                return;
            }

            console.log('Rezerwacja zaktualizowana:', data);

            editModal.classList.remove('open');
            editedReservationId = null;

            loadReservations();
        })
        .catch(error => {

            console.error('Reservation PATCH API:', error);

            alert('Nie udało się zapisać zmian.');
        });
    });

    editModal.addEventListener('click', event => {
        if (event.target === editModal) {
            editModal.classList.remove('open');
            editedReservationId = null;
        }
    });


    // ESC

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') {
            return;
        }

        modal.classList.remove('open');
        editModal.classList.remove('open');
        editedReservationId = null;
    });

    exportCsvBtn.addEventListener('click', () => {

        if (allReservations.length === 0) {
            alert('Brak rezerwacji do eksportu.');
            return;
        }

        const rows = [
            [
                'ID',
                'Klient',
                'Email',
                'Sprzęt',
                'Numer seryjny',
                'Data rozpoczęcia',
                'Data zakończenia',
                'Liczba dni',
                'Cena',
                'Status'
            ]
        ];

        allReservations.forEach(reservation => {
            rows.push([
                reservation.id,
                reservation.client?.name ?? '',
                reservation.client?.email ?? '',
                reservation.product?.title ?? '',
                reservation.product?.serialNumber ?? '',
                reservation.rentalPeriod?.startDate ?? '',
                reservation.rentalPeriod?.endDate ?? '',
                reservation.rentalPeriod?.days ?? '',
                reservation.totalPrice ?? '',
                reservation.statusLabel ?? reservation.statusOfReservation ?? ''
            ]);
        });

        const csvContent = rows
            .map(row =>
                row
                    .map(value => `"${String(value).replace(/"/g, '""')}"`)
                    .join(';')
            )
            .join('\n');

        const blob = new Blob(
            ['\uFEFF' + csvContent],
            {
                type: 'text/csv;charset=utf-8;'
            }
        );

        const url = URL.createObjectURL(blob);

        const link = document.createElement('a');

        link.href = url;
        link.download = `rezerwacje-${new Date().toISOString().slice(0, 10)}.csv`;

        document.body.appendChild(link);

        link.click();

        document.body.removeChild(link);

        URL.revokeObjectURL(url);
    });

    // START

    statusFilter.addEventListener('change', () => {
        loadReservations();
    });

    let searchTimeout;

    reservationSearch.addEventListener('input', () => {
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            loadReservations();
        }, 300);
    });

    loadReservations();

});
</script>
</body>
</html>