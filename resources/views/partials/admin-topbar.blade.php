{{-- ============================================================
     PANEL ADMINA - sam TOPBAR (górny pasek)
     Wstaw wewnątrz .adm-body, NAD .adm-content
============================================================ --}}
<header class="adm-topbar">
    <div class="adm-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Szukaj roweru, sprzętu, klienta...">
    </div>

    <div class="adm-topbar-actions">
        {{-- Dzwonek --}}
        <button type="button" class="adm-icon-btn" aria-label="Powiadomienia">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
        </button>

        {{-- Historia --}}
        <button type="button" class="adm-icon-btn" aria-label="Historia">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3v5h5"/>
                <path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/>
                <polyline points="12 7 12 12 15 15"/>
            </svg>
        </button>

        <div class="adm-topbar-divider"></div>

        <span class="adm-panel-label">Panel Admina</span>

        {{-- Avatar --}}
        <div class="adm-avatar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </div>
    </div>
</header>