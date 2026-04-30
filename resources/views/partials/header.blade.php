<header class="header">
    <div class="header-container">
        
        <div class="logo">
            EquipRent Pro
        </div>

        <nav class="nav">
            <a href="{{ route('Katalog') }}" 
               class="{{ request()->routeIs('Katalog') ? 'active' : '' }}">
                Katalog
            </a>
            <a href="#" 
               class="{{ request()->routeIs('reservations') ? 'active' : '' }}">
                Moje Rezerwacje
            </a>
        </nav>

        <div class="icons">
            <span class="icon">🔔</span>
            <span class="icon">👤</span>
        </div>

    </div>
</header>
