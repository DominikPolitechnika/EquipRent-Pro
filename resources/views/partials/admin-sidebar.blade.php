{{-- ============================================================
     PANEL ADMINA - sam SIDEBAR (lewa kolumna)
     Topbar jest w osobnym partialu: partials.admin-topbar
============================================================ --}}
<aside class="adm-sidebar">
    <div class="adm-logo">EquipRent Pro</div>

    <nav class="adm-nav">
        <a href="{{ route('dashboard') }}"
           class="adm-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
            </svg>
            <span>Panel Główny</span>
        </a>

        <a href="{{ route('equipment.list') }}"
           class="adm-nav-item {{ request()->routeIs('equipment.list') || request()->routeIs('product.edit') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span>Inwentarz</span>
        </a>

        <a href="{{ route('rentals.list') }}"
           class="adm-nav-item {{ request()->routeIs('rentals.list') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span>Rezerwacje</span>
        </a>

        <a href="{{ route('users.list') }}"
           class="adm-nav-item {{ request()->routeIs('users.list') || request()->routeIs('users.show') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span>Użytkownicy</span>
        </a>
    </nav>
</aside>