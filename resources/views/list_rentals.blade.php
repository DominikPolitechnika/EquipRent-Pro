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
                <div class="lr-breadcrumb">
                    <span>Zarządzanie</span>
                    <span>›</span>
                    <span class="active">Rezerwacje</span>
                </div>
                <h1 class="lr-title">Rejestr Wypożyczeń</h1>
                <p class="lr-subtitle">Monitoruj wypożyczenia sprzętu sportowego w czasie rzeczywistym, zarządzaj umowami i kontroluj zwroty aktywów premium.</p>

                {{-- PASEK NARZĘDZI --}}
                <div class="lr-toolbar">
                    <div class="lr-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" placeholder="Szukaj klienta, ID faktury lub numeru seryjnego...">
                    </div>
                    <button type="button" class="lr-tool-btn">
                        Najnowsze
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <button type="button" class="lr-tool-btn muted">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="10" y1="18" x2="14" y2="18"/></svg>
                        Filtruj Status
                    </button>
                    <button type="button" class="lr-tool-btn muted">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Eksportuj CSV
                    </button>
                </div>

                {{-- KARTA 1 - po terminie --}}
                <div class="lr-card">
                    <div class="lr-thumb"></div>
                    <div class="lr-col lr-col-client">
                        <span class="lr-col-label">Klient</span>
                        <span class="lr-col-val"><span class="lr-skel" style="width:110px;height:14px;"></span></span>
                    </div>
                    <div class="lr-col lr-col-equip">
                        <span class="lr-col-label">Sprzęt</span>
                        <span class="lr-col-val"><span class="lr-skel" style="width:130px;height:14px;"></span></span>
                        <span class="lr-col-sub"><span class="lr-skel" style="width:90px;height:11px;"></span></span>
                    </div>
                    <div class="lr-col lr-col-period">
                        <span class="lr-col-label">Okres wynajmu</span>
                        <div class="lr-period">
                            <span class="lr-skel" style="width:36px;height:14px;"></span>
                            <span class="arrow">→</span>
                            <span class="lr-skel" style="width:36px;height:14px;"></span>
                        </div>
                    </div>
                    <div class="lr-col lr-col-value">
                        <span class="lr-col-label">Wartość całkowita</span>
                        <span class="lr-value-main"><span class="lr-skel" style="width:110px;height:18px;"></span></span>
                        <span class="lr-value-penalty"><span class="lr-skel" style="width:90px;height:11px;"></span></span>
                    </div>
                    <span class="lr-badge late"><span class="lr-skel" style="width:80px;height:11px;"></span></span>
                    <div class="lr-actions">
                        <button type="button" class="lr-icon-btn" aria-label="Edytuj">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button type="button" class="lr-icon-btn lr-cancel-btn" aria-label="Anuluj">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </button>
                        <button type="button" class="lr-action-btn primary">Oznacz zwrot</button>
                    </div>
                </div>

                {{-- KARTA 2 - wypożyczone --}}
                <div class="lr-card">
                    <div class="lr-thumb"></div>
                    <div class="lr-col lr-col-client">
                        <span class="lr-col-label">Klient</span>
                        <span class="lr-col-val"><span class="lr-skel" style="width:120px;height:14px;"></span></span>
                    </div>
                    <div class="lr-col lr-col-equip">
                        <span class="lr-col-label">Sprzęt</span>
                        <span class="lr-col-val"><span class="lr-skel" style="width:140px;height:14px;"></span></span>
                        <span class="lr-col-sub"><span class="lr-skel" style="width:90px;height:11px;"></span></span>
                    </div>
                    <div class="lr-col lr-col-period">
                        <span class="lr-col-label">Okres wynajmu</span>
                        <div class="lr-period">
                            <span class="lr-skel" style="width:36px;height:14px;"></span>
                            <span class="arrow">→</span>
                            <span class="lr-skel" style="width:36px;height:14px;"></span>
                        </div>
                    </div>
                    <div class="lr-col lr-col-value">
                        <span class="lr-col-label">Wartość całkowita</span>
                        <span class="lr-value-main"><span class="lr-skel" style="width:100px;height:18px;"></span></span>
                        <span class="lr-value-daily"><span class="lr-skel" style="width:100px;height:11px;"></span></span>
                    </div>
                    <span class="lr-badge rented"><span class="lr-skel" style="width:80px;height:11px;"></span></span>
                    <div class="lr-actions">
                        <button type="button" class="lr-icon-btn" aria-label="Edytuj">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button type="button" class="lr-icon-btn lr-cancel-btn" aria-label="Anuluj">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </button>
                        <button type="button" class="lr-action-btn soft">Szczegóły</button>
                    </div>
                </div>

                {{-- KARTA 3 - potwierdzone --}}
                <div class="lr-card">
                    <div class="lr-thumb"></div>
                    <div class="lr-col lr-col-client">
                        <span class="lr-col-label">Klient</span>
                        <span class="lr-col-val"><span class="lr-skel" style="width:110px;height:14px;"></span></span>
                    </div>
                    <div class="lr-col lr-col-equip">
                        <span class="lr-col-label">Sprzęt</span>
                        <span class="lr-col-val"><span class="lr-skel" style="width:130px;height:14px;"></span></span>
                        <span class="lr-col-sub"><span class="lr-skel" style="width:90px;height:11px;"></span></span>
                    </div>
                    <div class="lr-col lr-col-period">
                        <span class="lr-col-label">Okres wynajmu</span>
                        <div class="lr-period">
                            <span class="lr-skel" style="width:36px;height:14px;"></span>
                            <span class="arrow">→</span>
                            <span class="lr-skel" style="width:36px;height:14px;"></span>
                        </div>
                    </div>
                    <div class="lr-col lr-col-value">
                        <span class="lr-col-label">Wartość całkowita</span>
                        <span class="lr-value-main"><span class="lr-skel" style="width:100px;height:18px;"></span></span>
                        <span class="lr-value-daily"><span class="lr-skel" style="width:100px;height:11px;"></span></span>
                    </div>
                    <span class="lr-badge confirmed"><span class="lr-skel" style="width:80px;height:11px;"></span></span>
                    <div class="lr-actions">
                        <button type="button" class="lr-icon-btn" aria-label="Edytuj">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button type="button" class="lr-icon-btn lr-cancel-btn" aria-label="Anuluj">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </button>
                        <button type="button" class="lr-action-btn soft">Wydaj sprzęt</button>
                    </div>
                </div>

                {{-- STOPKA --}}
                <div class="lr-footer">
                    <div class="lr-footer-count">
                        Wyświetlono <span class="lr-skel" style="width:14px;height:11px;"></span> z <span class="lr-skel" style="width:24px;height:11px;"></span> sztuk sprzętu
                    </div>
                    <div class="lr-pagination">
                        <a href="#" class="lr-page nav">‹</a>
                        <a href="#" class="lr-page active">1</a>
                        <a href="#" class="lr-page">2</a>
                        <a href="#" class="lr-page">3</a>
                        <a href="#" class="lr-page nav">›</a>
                    </div>
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

<script>
(function() {
    const modal      = document.getElementById('lr-cancel-modal');
    const btnCancel  = document.getElementById('lr-modal-cancel');
    const btnConfirm = document.getElementById('lr-modal-confirm');
    const triggers   = document.querySelectorAll('.lr-cancel-btn');

    if (!modal) return;

    function open()  { modal.classList.add('open'); }
    function close() { modal.classList.remove('open'); }

    // Każdy przycisk X otwiera ten sam modal
    triggers.forEach(t => t.addEventListener('click', open));

    // "Wróć" - zamyka bez akcji
    btnCancel.addEventListener('click', close);

    // "Tak, anuluj" - na razie tylko zamyka (czysty podgląd)
    btnConfirm.addEventListener('click', () => {
        // TODO: tutaj będzie wywołanie fetch/form na backend
        close();
    });

    // Klik w tło zamyka
    modal.addEventListener('click', (e) => {
        if (e.target === modal) close();
    });

    // Escape zamyka
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('open')) close();
    });
})();
</script>
</body>
</html>