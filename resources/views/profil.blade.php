<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil użytkownika – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-profil.css') }}">
    <style>
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.45; } }
        .placeholder { background: #e2e8f0; display: inline-block; border-radius: 3px; }
        .placeholder-block { background: #e2e8f0; display: block; border-radius: 3px; }
        .placeholder-circle { background: #e2e8f0; border-radius: 50%; display: block; }
        .animate-pulse { animation: pulse 1.6s ease-in-out infinite; }
    </style>
</head>
<body class="prof-page">
@include('partials.header')

{{-- ===== NAGŁÓWEK STRONY ===== --}}
<div class="prof-page-header">
    <div class="prof-page-header-text">
        <h1>Profil Użytkownika</h1>
        <p>Zarządzaj swoim sprzętem sportowym, płatnościami i powiadomieniami.</p>
    </div>
    <div class="prof-header-actions">
        <a href="{{ route('profil_edytuj') }}" class="prof-btn-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Edytuj profil
        </a>
    </div>
</div>

<div class="prof-wrapper">

    {{-- ===== LEWA KOLUMNA ===== --}}
    <aside class="prof-sidebar">

        {{-- Karta użytkownika --}}
        <div class="prof-card-user">
            <div class="prof-avatar-wrapper">
                <span class="placeholder-circle animate-pulse" style="width:120px;height:120px;"></span>
            </div>
            <h2 class="prof-user-name">
                <span class="placeholder animate-pulse" style="width:180px;height:22px;"></span>
            </h2>
            <p class="prof-user-role">
                <span class="placeholder animate-pulse" style="width:220px;height:14px;"></span>
            </p>

            <div class="prof-user-divider"></div>

            <div class="prof-user-info">
                <div class="prof-user-info-row">
                    <svg class="prof-user-info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                    <div class="prof-user-info-content">
                        <div class="prof-user-info-label">Klub sportowy</div>
                        <div class="prof-user-info-value">
                            <span class="placeholder animate-pulse" style="width:200px;height:14px;"></span>
                        </div>
                    </div>
                </div>

                <div class="prof-user-info-row">
                    <svg class="prof-user-info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <div class="prof-user-info-content">
                        <div class="prof-user-info-label">E-mail</div>
                        <div class="prof-user-info-value">
                            <span class="placeholder animate-pulse" style="width:170px;height:14px;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Karta podsumowania (niebieska) --}}
        <div class="prof-card-summary">
            <div class="prof-summary-title">Podsumowanie wypożyczeń</div>
            <div class="prof-summary-grid">
                <div>
                    <div class="prof-summary-label">Suma wydatków</div>
                    <div class="prof-summary-value">
                        <span class="placeholder animate-pulse" style="width:110px;height:22px;background:#ffffff55;"></span>
                    </div>
                </div>
                <div>
                    <div class="prof-summary-label">Wynajęte przedmioty</div>
                    <div class="prof-summary-value">
                        <span class="placeholder animate-pulse" style="width:40px;height:22px;background:#ffffff55;"></span>
                    </div>
                </div>
            </div>
            <div class="prof-summary-footer">
                <span class="prof-summary-footer-label">Status lojalnościowy</span>
                <span class="prof-loyalty-badge">
                    <span class="placeholder animate-pulse" style="width:90px;height:14px;background:#ffffff55;"></span>
                </span>
            </div>
        </div>

        {{-- Centrum powiadomień --}}
        <div class="prof-card-notif">
            <div class="prof-notif-header">
                <span class="prof-notif-title">Centrum Powiadomień</span>
                <span class="prof-notif-badge">
                    <span class="placeholder animate-pulse" style="width:50px;height:12px;background:#ffffff55;"></span>
                </span>
            </div>

            <div class="prof-notif-item urgent">
                <div class="prof-notif-item-top">
                    <span class="prof-notif-item-label">
                        <span class="placeholder animate-pulse" style="width:80px;height:13px;"></span>
                    </span>
                    <span class="prof-notif-item-time">
                        <span class="placeholder animate-pulse" style="width:50px;height:11px;"></span>
                    </span>
                </div>
                <div class="prof-notif-item-text">
                    <span class="placeholder-block animate-pulse" style="width:100%;height:12px;margin-bottom:4px;"></span>
                    <span class="placeholder-block animate-pulse" style="width:80%;height:12px;"></span>
                </div>
            </div>

            <div class="prof-notif-item">
                <div class="prof-notif-item-top">
                    <span class="prof-notif-item-label">
                        <span class="placeholder animate-pulse" style="width:100px;height:13px;"></span>
                    </span>
                    <span class="prof-notif-item-time">
                        <span class="placeholder animate-pulse" style="width:50px;height:11px;"></span>
                    </span>
                </div>
                <div class="prof-notif-item-text">
                    <span class="placeholder-block animate-pulse" style="width:100%;height:12px;margin-bottom:4px;"></span>
                    <span class="placeholder-block animate-pulse" style="width:70%;height:12px;"></span>
                </div>
            </div>

            <div class="prof-notif-item">
                <div class="prof-notif-item-top">
                    <span class="prof-notif-item-label">
                        <span class="placeholder animate-pulse" style="width:110px;height:13px;"></span>
                    </span>
                    <span class="prof-notif-item-time">
                        <span class="placeholder animate-pulse" style="width:50px;height:11px;"></span>
                    </span>
                </div>
                <div class="prof-notif-item-text">
                    <span class="placeholder-block animate-pulse" style="width:100%;height:12px;margin-bottom:4px;"></span>
                    <span class="placeholder-block animate-pulse" style="width:60%;height:12px;"></span>
                </div>
            </div>

            <div class="prof-notif-item">
                <div class="prof-notif-item-top">
                    <span class="prof-notif-item-label">
                        <span class="placeholder animate-pulse" style="width:90px;height:13px;"></span>
                    </span>
                    <span class="prof-notif-item-time">
                        <span class="placeholder animate-pulse" style="width:50px;height:11px;"></span>
                    </span>
                </div>
                <div class="prof-notif-item-text">
                    <span class="placeholder-block animate-pulse" style="width:100%;height:12px;margin-bottom:4px;"></span>
                    <span class="placeholder-block animate-pulse" style="width:75%;height:12px;"></span>
                </div>
            </div>
        </div>

    </aside>

    {{-- ===== PRAWA KOLUMNA ===== --}}
    <main class="prof-content">

        {{-- Aktywne rezerwacje --}}
        <section class="prof-section">
            <div class="prof-section-header">
                <div class="prof-section-title">
                    <svg class="prof-section-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Aktywne Rezerwacje
                </div>
                <a href="{{ route('rezerwacje') }}" class="prof-section-link">Zobacz historię</a>
            </div>

            {{-- Rezerwacja 1 --}}
            <div class="prof-rez-card">
                <div class="prof-rez-top">
                    <div class="prof-rez-info">
                        <div class="prof-rez-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <line x1="5.5" y1="5.5" x2="18.5" y2="18.5"/>
                            </svg>
                        </div>
                        <div class="prof-rez-titles">
                            <div class="prof-rez-name">
                                <span class="placeholder animate-pulse" style="width:220px;height:16px;"></span>
                            </div>
                            <div class="prof-rez-id">
                                <span class="placeholder animate-pulse" style="width:90px;height:12px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="prof-rez-status">
                        <span class="prof-rez-badge late">
                            <span class="placeholder animate-pulse" style="width:100px;height:12px;background:#ffffff55;"></span>
                        </span>
                        <div class="prof-rez-deadline">
                            <span class="placeholder animate-pulse" style="width:110px;height:12px;"></span>
                        </div>
                    </div>
                </div>
                <div class="prof-rez-details">
                    <div>
                        <div class="prof-rez-detail-label">Okres wynajmu</div>
                        <div class="prof-rez-detail-value">
                            <span class="placeholder animate-pulse" style="width:130px;height:14px;"></span>
                        </div>
                    </div>
                    <div>
                        <div class="prof-rez-detail-label">Lokalizacja</div>
                        <div class="prof-rez-detail-value">
                            <span class="placeholder animate-pulse" style="width:140px;height:14px;"></span>
                        </div>
                    </div>
                    <div class="prof-rez-detail penalty">
                        <div class="prof-rez-detail-label">Naliczone kary</div>
                        <div class="prof-rez-detail-value">
                            <span class="placeholder animate-pulse" style="width:90px;height:14px;"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rezerwacja 2 --}}
            <div class="prof-rez-card">
                <div class="prof-rez-top">
                    <div class="prof-rez-info">
                        <div class="prof-rez-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 17l6-6 4 4 8-8"/>
                                <path d="M14 7h7v7"/>
                            </svg>
                        </div>
                        <div class="prof-rez-titles">
                            <div class="prof-rez-name">
                                <span class="placeholder animate-pulse" style="width:200px;height:16px;"></span>
                            </div>
                            <div class="prof-rez-id">
                                <span class="placeholder animate-pulse" style="width:90px;height:12px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="prof-rez-status">
                        <span class="prof-rez-badge active">
                            <span class="placeholder animate-pulse" style="width:70px;height:12px;background:#ffffff55;"></span>
                        </span>
                        <div class="prof-rez-deadline">
                            <span class="placeholder animate-pulse" style="width:110px;height:12px;"></span>
                        </div>
                    </div>
                </div>
                <div class="prof-rez-details">
                    <div>
                        <div class="prof-rez-detail-label">Okres wynajmu</div>
                        <div class="prof-rez-detail-value">
                            <span class="placeholder animate-pulse" style="width:130px;height:14px;"></span>
                        </div>
                    </div>
                    <div>
                        <div class="prof-rez-detail-label">Lokalizacja</div>
                        <div class="prof-rez-detail-value">
                            <span class="placeholder animate-pulse" style="width:130px;height:14px;"></span>
                        </div>
                    </div>
                    <div>
                        <div class="prof-rez-detail-label">Naliczone kary</div>
                        <div class="prof-rez-detail-value">
                            <span class="placeholder animate-pulse" style="width:60px;height:14px;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Zapisane metody płatności --}}
        <section class="prof-section">
            <div class="prof-section-header">
                <div class="prof-section-title">
                    <svg class="prof-section-title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    Zapisane Metody Płatności
                </div>
            </div>

            <div class="prof-payment-grid">
                <div class="prof-payment-card">
                    <div class="prof-payment-logo visa">
                        <span class="placeholder animate-pulse" style="width:38px;height:14px;background:#ffffff55;"></span>
                    </div>
                    <div class="prof-payment-info">
                        <div class="prof-payment-number">
                            <span class="placeholder animate-pulse" style="width:120px;height:16px;"></span>
                        </div>
                        <div class="prof-payment-expiry">
                            <span class="placeholder animate-pulse" style="width:90px;height:12px;"></span>
                        </div>
                    </div>
                    <span class="prof-payment-default">
                        <span class="placeholder animate-pulse" style="width:60px;height:12px;"></span>
                    </span>
                </div>

                <div class="prof-payment-card">
                    <div class="prof-payment-logo mc">
                        <span class="placeholder animate-pulse" style="width:28px;height:14px;background:#ffffff55;"></span>
                    </div>
                    <div class="prof-payment-info">
                        <div class="prof-payment-number">
                            <span class="placeholder animate-pulse" style="width:120px;height:16px;"></span>
                        </div>
                        <div class="prof-payment-expiry">
                            <span class="placeholder animate-pulse" style="width:90px;height:12px;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="prof-payment-info-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span>Twoje dane są chronione zgodnie ze standardami PCI DSS oraz RODO. Nie przechowujemy pełnych numerów kart płatniczych na naszych serwerach. Wszystkie operacje finansowe są realizowane przez procesor Stripe.</span>
            </div>
        </section>

    </main>
</div>

@include('partials.footer')
</body>
</html>