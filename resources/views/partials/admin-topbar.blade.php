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
        <div class="adm-topbar-divider"></div>

        <span class="adm-panel-label">Panel Admina</span>

        {{-- Avatar --}}
        <div class="adm-avatar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </div>

        {{-- Wyloguj --}}
        <button type="button" class="adm-icon-btn" id="adm-logout-btn" aria-label="Wyloguj się" title="Wyloguj się">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
        </button>
    </div>
</header>

<script>
(function() {
    // ===== Wylogowanie =====
    const logoutBtn = document.getElementById('adm-logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            try {
                await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    credentials: 'same-origin',
                });
            } catch (error) {
                console.error(error);
            }
            window.location.href = '/login';
        });
    }
})();
</script>