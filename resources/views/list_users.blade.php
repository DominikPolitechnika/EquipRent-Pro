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
                <div class="adm-breadcrumb">
                    <span>Zarządzanie</span>
                    <span class="sep">›</span>
                    <span class="active">Użytkownicy</span>
                </div>

                {{-- NAGŁÓWEK --}}
                <div class="lu-header">
                    <div class="lu-header-text">
                        <h1>Zarejestrowani Klienci</h1>
                        <p>Zarządzaj siecią profesjonalnych sportowców i klubów partnerskich.</p>
                    </div>
                    <button type="button" class="lu-filter-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                        </svg>
                        Filtruj wg statusu
                    </button>
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
                        <tbody>
                            {{-- Wiersz 1 --}}
                            <tr>
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
                                        <a href="{{ route('users.show') }}" class="lu-details-btn">Szczegóły</a>
                                        <button type="button" class="lu-toggle-icon" aria-label="Status">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Wiersz 2 --}}
                            <tr>
                                <td>
                                    <div class="lu-profile">
                                        <div class="lu-avatar"><span class="lu-avatar-dot off"></span></div>
                                        <div>
                                            <div class="lu-profile-name"><span class="lu-skel" style="width:120px;height:14px;"></span></div>
                                            <div class="lu-profile-role"><span class="lu-skel" style="width:90px;height:11px;"></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="lu-email"><span class="lu-skel" style="width:150px;height:13px;"></span></div>
                                    <div class="lu-tag"><span class="lu-skel" style="width:190px;height:9px;"></span></div>
                                </td>
                                <td>
                                    <div class="lu-activity">
                                        <div class="lu-activity-num"><span class="lu-skel" style="width:30px;height:20px;"></span></div>
                                        <div class="lu-activity-label"><span class="lu-skel" style="width:70px;height:9px;"></span></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="lu-reg-date"><span class="lu-skel" style="width:90px;height:13px;"></span></div>
                                    <div class="lu-reg-sub"><span class="lu-skel" style="width:120px;height:11px;"></span></div>
                                </td>
                                <td>
                                    <div class="lu-admin">
                                        <a href="{{ route('users.show') }}" class="lu-details-btn">Szczegóły</a>
                                        <button type="button" class="lu-toggle-icon" aria-label="Status">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Wiersz 3 (zablokowany) --}}
                            <tr>
                                <td>
                                    <div class="lu-profile">
                                        <div class="lu-avatar"><span class="lu-avatar-dot off"></span></div>
                                        <div>
                                            <div class="lu-profile-name"><span class="lu-skel" style="width:110px;height:14px;"></span></div>
                                            <div class="lu-profile-role"><span class="lu-skel" style="width:90px;height:11px;"></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="lu-email"><span class="lu-skel" style="width:140px;height:13px;"></span></div>
                                    <div class="lu-tag blocked"><span class="lu-skel" style="width:90px;height:9px;"></span></div>
                                </td>
                                <td>
                                    <div class="lu-activity">
                                        <div class="lu-activity-num"><span class="lu-skel" style="width:22px;height:20px;"></span></div>
                                        <div class="lu-activity-label"><span class="lu-skel" style="width:70px;height:9px;"></span></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="lu-reg-date"><span class="lu-skel" style="width:90px;height:13px;"></span></div>
                                    <div class="lu-reg-sub"><span class="lu-skel" style="width:120px;height:11px;"></span></div>
                                </td>
                                <td>
                                    <div class="lu-admin">
                                        <a href="{{ route('users.show') }}" class="lu-details-btn">Szczegóły</a>
                                        <button type="button" class="lu-toggle-icon on" aria-label="Status">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                                <polyline points="22 4 12 14.01 9 11.01"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Wiersz 4 --}}
                            <tr>
                                <td>
                                    <div class="lu-profile">
                                        <div class="lu-avatar"><span class="lu-avatar-dot"></span></div>
                                        <div>
                                            <div class="lu-profile-name"><span class="lu-skel" style="width:120px;height:14px;"></span></div>
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
                                        <div class="lu-activity-num"><span class="lu-skel" style="width:38px;height:20px;"></span></div>
                                        <div class="lu-activity-label"><span class="lu-skel" style="width:70px;height:9px;"></span></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="lu-reg-date"><span class="lu-skel" style="width:90px;height:13px;"></span></div>
                                    <div class="lu-reg-sub"><span class="lu-skel" style="width:120px;height:11px;"></span></div>
                                </td>
                                <td>
                                    <div class="lu-admin">
                                        <a href="{{ route('users.show') }}" class="lu-details-btn">Szczegóły</a>
                                        <button type="button" class="lu-toggle-icon" aria-label="Status">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- STOPKA + PAGINACJA --}}
                <div class="lu-footer">
                    <div class="lu-footer-count">
                        Wyświetlono <span class="lu-skel" style="width:14px;height:11px;"></span> z <span class="lu-skel" style="width:24px;height:11px;"></span> użytkowników
                    </div>
                    <div class="lu-pagination">
                        <a href="#" class="lu-page nav">‹</a>
                        <a href="#" class="lu-page active">1</a>
                        <a href="#" class="lu-page">2</a>
                        <a href="#" class="lu-page">3</a>
                        <a href="#" class="lu-page nav">›</a>
                    </div>
                </div>

            </div>
        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}
</body>
</html>