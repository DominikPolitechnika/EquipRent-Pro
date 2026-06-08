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
                <div class="db-breadcrumb">
                    <span>Zarządzanie</span>
                    <span>›</span>
                    <span class="active">Panel Główny</span>
                </div>
                <h1 class="db-title">Przegląd Systemu</h1>
                <p class="db-subtitle">Monitorowanie wydajności sprzętu i metryk operacyjnych w czasie rzeczywistym.</p>

                {{-- ===== GÓRNE KAFLE ===== --}}
                <div class="db-stats">

                    {{-- Aktywne wynajmy --}}
                    <div class="db-stat">
                        <div class="db-stat-decor"></div>
                        <div class="db-stat-label">Aktywne wynajmy</div>
                        <div class="db-stat-value">
                            <span class="db-skel" style="width:90px;height:38px;"></span>
                            <span class="db-stat-trend">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 6 23 6 23 12"/><line x1="23" y1="6" x2="13" y2="16"/><line x1="13" y1="16" x2="8" y2="11"/><line x1="8" y1="11" x2="1" y2="18"/></svg>
                                <span class="db-skel" style="width:40px;height:11px;"></span>
                            </span>
                        </div>
                        <div class="db-stat-meta">
                            <span class="dot"></span>
                            <span class="db-skel" style="width:140px;height:11px;"></span>
                        </div>
                    </div>

                    {{-- Przychód miesięczny --}}
                    <div class="db-stat">
                        <div class="db-stat-label">Przychód miesięczny</div>
                        <div class="db-stat-value">
                            <span class="db-skel" style="width:130px;height:38px;"></span>
                            <span class="db-stat-trend">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 6 23 6 23 12"/><line x1="23" y1="6" x2="13" y2="16"/><line x1="13" y1="16" x2="8" y2="11"/><line x1="8" y1="11" x2="1" y2="18"/></svg>
                                <span class="db-skel" style="width:30px;height:11px;"></span>
                            </span>
                        </div>
                        <div class="db-progress">
                            <div class="db-progress-bar" style="width:82%"></div>
                        </div>
                        <div class="db-progress-label"><span class="db-skel" style="width:55px;height:11px;"></span></div>
                    </div>

                    {{-- Niebieski - Stan sprzętu --}}
                    <div class="db-stat dark">
                        <div class="db-stat-label">
                            Zarobki w twoim tygodniu
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                        </div>
                        <div class="db-dark-title">
                            <span class="db-skel db-skel-light" style="width:80%;height:22px;"></span>
                        </div>
                        <div class="db-dark-text">
                            <span class="db-skel db-skel-light db-skel-block" style="width:100%;height:11px;margin-bottom:5px;"></span>
                            <span class="db-skel db-skel-light db-skel-block" style="width:60%;height:11px;"></span>
                        </div>
                        <div class="db-mini-chart">
                            <div class="bar" style="height:35%"></div>
                            <div class="bar" style="height:55%"></div>
                            <div class="bar" style="height:45%"></div>
                            <div class="bar peak" style="height:90%"></div>
                            <div class="bar" style="height:60%"></div>
                            <div class="bar" style="height:50%"></div>
                            <div class="bar" style="height:30%"></div>
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
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="db-client">
                                                <div class="db-mono mk">MK</div>
                                                <div>
                                                    <div class="db-client-name"><span class="db-skel" style="width:100px;height:13px;"></span></div>
                                                    <div class="db-client-tag"><span class="db-skel" style="width:80px;height:10px;"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="db-equip-name"><span class="db-skel" style="width:110px;height:13px;"></span></div>
                                            <div class="db-equip-cat"><span class="db-skel" style="width:70px;height:10px;"></span></div>
                                        </td>
                                        <td><div class="db-period"><span class="db-skel" style="width:60px;height:12px;"></span></div></td>
                                        <td><span class="db-badge rented"><span class="db-skel" style="width:60px;height:10px;"></span></span></td>
                                        <td><button type="button" class="db-dots" aria-label="Więcej"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="19" cy="12" r="1.6"/></svg></button></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="db-client">
                                                <div class="db-mono az">AZ</div>
                                                <div>
                                                    <div class="db-client-name"><span class="db-skel" style="width:110px;height:13px;"></span></div>
                                                    <div class="db-client-tag"><span class="db-skel" style="width:80px;height:10px;"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="db-equip-name"><span class="db-skel" style="width:100px;height:13px;"></span></div>
                                            <div class="db-equip-cat"><span class="db-skel" style="width:90px;height:10px;"></span></div>
                                        </td>
                                        <td><div class="db-period"><span class="db-skel" style="width:60px;height:12px;"></span></div></td>
                                        <td><span class="db-badge waiting"><span class="db-skel" style="width:60px;height:10px;"></span></span></td>
                                        <td><button type="button" class="db-dots" aria-label="Więcej"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="19" cy="12" r="1.6"/></svg></button></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="db-client">
                                                <div class="db-mono js">JS</div>
                                                <div>
                                                    <div class="db-client-name"><span class="db-skel" style="width:100px;height:13px;"></span></div>
                                                    <div class="db-client-tag"><span class="db-skel" style="width:80px;height:10px;"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="db-equip-name"><span class="db-skel" style="width:110px;height:13px;"></span></div>
                                            <div class="db-equip-cat"><span class="db-skel" style="width:70px;height:10px;"></span></div>
                                        </td>
                                        <td><div class="db-period"><span class="db-skel" style="width:60px;height:12px;"></span></div></td>
                                        <td><span class="db-badge rented"><span class="db-skel" style="width:60px;height:10px;"></span></span></td>
                                        <td><button type="button" class="db-dots" aria-label="Więcej"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="19" cy="12" r="1.6"/></svg></button></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="db-client">
                                                <div class="db-mono sk">SK</div>
                                                <div>
                                                    <div class="db-client-name"><span class="db-skel" style="width:120px;height:13px;"></span></div>
                                                    <div class="db-client-tag"><span class="db-skel" style="width:90px;height:10px;"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="db-equip-name"><span class="db-skel" style="width:110px;height:13px;"></span></div>
                                            <div class="db-equip-cat"><span class="db-skel" style="width:80px;height:10px;"></span></div>
                                        </td>
                                        <td><div class="db-period"><span class="db-skel" style="width:60px;height:12px;"></span></div></td>
                                        <td><span class="db-badge returned"><span class="db-skel" style="width:60px;height:10px;"></span></span></td>
                                        <td><button type="button" class="db-dots" aria-label="Więcej"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="19" cy="12" r="1.6"/></svg></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Popularny Sprzęt --}}
                    <div>
                        <div class="db-section-header">
                            <div class="db-section-title">Popularny Sprzęt</div>
                        </div>

                        <div class="db-pop-list">
                            <div class="db-pop-card">
                                <div class="db-pop-img"></div>
                                <div class="db-pop-body">
                                    <div class="db-pop-name"><span class="db-skel" style="width:110px;height:13px;"></span></div>
                                    <div class="db-pop-meta"><span class="db-skel" style="width:130px;height:10px;"></span></div>
                                    <div class="db-pop-footer">
                                        <span class="db-pop-price"><span class="db-skel" style="width:80px;height:11px;"></span></span>
                                        <span class="db-pop-status available"><span class="db-skel" style="width:60px;height:10px;"></span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="db-pop-card">
                                <div class="db-pop-img"></div>
                                <div class="db-pop-body">
                                    <div class="db-pop-name"><span class="db-skel" style="width:130px;height:13px;"></span></div>
                                    <div class="db-pop-meta"><span class="db-skel" style="width:130px;height:10px;"></span></div>
                                    <div class="db-pop-footer">
                                        <span class="db-pop-price"><span class="db-skel" style="width:80px;height:11px;"></span></span>
                                        <span class="db-pop-status reserved"><span class="db-skel" style="width:70px;height:10px;"></span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="db-pop-card">
                                <div class="db-pop-img"></div>
                                <div class="db-pop-body">
                                    <div class="db-pop-name"><span class="db-skel" style="width:120px;height:13px;"></span></div>
                                    <div class="db-pop-meta"><span class="db-skel" style="width:130px;height:10px;"></span></div>
                                    <div class="db-pop-footer">
                                        <span class="db-pop-price"><span class="db-skel" style="width:80px;height:11px;"></span></span>
                                        <span class="db-pop-status available"><span class="db-skel" style="width:60px;height:10px;"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>{{-- /adm-content --}}
    </div>{{-- /adm-body --}}
</div>{{-- /adm-shell --}}
</body>
</html>