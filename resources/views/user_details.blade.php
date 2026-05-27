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
                            <div class="ud-avatar">
                                <span class="ud-skel" style="width:36px;height:24px;background:rgba(26,111,168,.25);"></span>
                            </div>
                            <div class="ud-profile-info">
                                <h2 class="ud-profile-name"><span class="ud-skel" style="width:200px;height:24px;"></span></h2>
                                <div class="ud-profile-row">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                    <span class="ud-skel" style="width:180px;height:13px;"></span>
                                </div>
                                <div class="ud-profile-row">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 21h18M3 7v14M21 7v14M6 11h.01M6 15h.01M10 11h.01M10 15h.01M14 11h.01M14 15h.01M18 11h.01M18 15h.01M9 21v-4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4"/>
                                    </svg>
                                    <span class="ud-skel" style="width:160px;height:13px;"></span>
                                </div>
                            </div>
                            <div class="ud-profile-actions">
                                <a href="#" class="ud-btn ud-btn-edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edytuj dane
                                </a>
                                <button type="button" class="ud-btn ud-btn-block" id="ud-block-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                                    </svg>
                                    Zablokuj konto
                                </button>
                            </div>
                        </div>

                        {{-- Karta finansowa --}}
                        <div class="ud-finance">
                            <div class="ud-finance-title">Podsumowanie Finansowe</div>
                            <div class="ud-finance-label">Łączna wartość wynajmów</div>
                            <div class="ud-finance-value"><span class="ud-skel ud-skel-light" style="width:130px;height:26px;"></span></div>
                            <div class="ud-finance-label">Naliczone kary i opłaty</div>
                            <div class="ud-finance-value"><span class="ud-skel ud-skel-light" style="width:100px;height:26px;"></span></div>
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
                            <span class="ud-metric-badge">
                                <span class="ud-skel" style="width:50px;height:11px;background:rgba(22,163,74,.25);"></span>
                            </span>
                        </div>
                        <div>
                            <div class="ud-metric-label">Data dołączenia</div>
                            <div class="ud-metric-value"><span class="ud-skel" style="width:90px;height:13px;"></span></div>
                        </div>
                        <div>
                            <div class="ud-metric-label">Weryfikacja</div>
                            <div class="ud-metric-verified">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                                <span class="ud-skel" style="width:80px;height:13px;"></span>
                            </div>
                        </div>
                        <div>
                            <div class="ud-metric-label">Ostatnie logowanie</div>
                            <div class="ud-metric-value"><span class="ud-skel" style="width:100px;height:13px;"></span></div>
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
                        <tbody>
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
                            <tr>
                                <td>
                                    <div class="ud-product">
                                        <div class="ud-product-img"></div>
                                        <span class="ud-product-name"><span class="ud-skel" style="width:180px;height:13px;"></span></span>
                                    </div>
                                </td>
                                <td><span class="ud-skel" style="width:150px;height:13px;"></span></td>
                                <td class="center"><span class="ud-badge returned"><span class="ud-skel" style="width:60px;height:10px;background:rgba(22,163,74,.25);"></span></span></td>
                                <td class="right"><span class="ud-skel" style="width:80px;height:13px;"></span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="ud-product">
                                        <div class="ud-product-img"></div>
                                        <span class="ud-product-name"><span class="ud-skel" style="width:160px;height:13px;"></span></span>
                                    </div>
                                </td>
                                <td><span class="ud-skel" style="width:150px;height:13px;"></span></td>
                                <td class="center"><span class="ud-badge returned"><span class="ud-skel" style="width:60px;height:10px;background:rgba(22,163,74,.25);"></span></span></td>
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

                    <div class="ud-incident">
                        <div class="ud-incident-top">
                            <div class="ud-incident-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                            </div>
                            <div class="ud-incident-body">
                                <div class="ud-incident-title"><span class="ud-skel" style="width:230px;height:14px;"></span></div>
                            </div>
                            <div class="ud-incident-date"><span class="ud-skel" style="width:60px;height:10px;"></span></div>
                        </div>
                        <div class="ud-incident-text">
                            <span class="ud-skel-block ud-skel" style="width:100%;height:11px;margin-bottom:6px;"></span>
                            <span class="ud-skel-block ud-skel" style="width:60%;height:11px;"></span>
                        </div>
                        <div class="ud-incident-footer">
                            <span class="ud-incident-tag">
                                <span class="ud-skel" style="width:70px;height:10px;background:rgba(255,255,255,.4);"></span>
                            </span>
                            <a href="#" class="ud-incident-link">Szczegóły protokołu</a>
                        </div>
                    </div>

                    <div class="ud-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <div class="ud-empty-text">Brak innych zgłoszonych incydentów</div>
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
        <h3 class="ud-modal-title">Zablokować konto?</h3>
        <p class="ud-modal-text">
            Czy na pewno chcesz zablokować to konto?<br>
            Użytkownik straci dostęp do platformy do momentu odblokowania.
        </p>
        <div class="ud-modal-actions">
            <button type="button" class="ud-modal-btn ud-modal-btn-cancel" id="ud-modal-cancel">Wróć</button>
            <button type="button" class="ud-modal-btn ud-modal-btn-confirm" id="ud-modal-confirm">Tak, zablokuj</button>
        </div>
    </div>
</div>

<script>
(function() {
    const modal      = document.getElementById('ud-block-modal');
    const trigger    = document.getElementById('ud-block-btn');
    const btnCancel  = document.getElementById('ud-modal-cancel');
    const btnConfirm = document.getElementById('ud-modal-confirm');

    if (!modal || !trigger) return;

    function open()  { modal.classList.add('open'); }
    function close() { modal.classList.remove('open'); }

    trigger.addEventListener('click', open);
    btnCancel.addEventListener('click', close);
    btnConfirm.addEventListener('click', () => {
        // TODO: tutaj będzie wywołanie fetch/form na backend (blokada konta)
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