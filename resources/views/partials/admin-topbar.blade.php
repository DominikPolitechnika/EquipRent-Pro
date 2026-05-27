{{-- ============================================================
     PANEL ADMINA - sam TOPBAR (górny pasek)
     Wstaw wewnątrz .adm-body, NAD .adm-content
============================================================ --}}
<style>
    /* ===== Popover powiadomień ===== */
    @keyframes notif-pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.45; } }

    .notif-wrap { position: relative; }

    .notif-btn {
        background: transparent;
        border: 0;
        padding: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: inherit;
        position: relative;
    }
    .notif-dot {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 8px;
        height: 8px;
        background: #e23b3b;
        border-radius: 50%;
        border: 2px solid #fff;
    }

    .notif-popover {
        display: none;
        position: absolute;
        top: calc(100% + 12px);
        right: -8px;
        width: 340px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.14), 0 2px 6px rgba(0, 0, 0, 0.08);
        z-index: 1000;
        overflow: hidden;
    }
    .notif-popover.open { display: block; }
    .notif-popover::before {
        content: '';
        position: absolute;
        top: -7px;
        right: 18px;
        width: 14px;
        height: 14px;
        background: #fff;
        transform: rotate(45deg);
        box-shadow: -2px -2px 4px rgba(0, 0, 0, 0.04);
    }

    .notif-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid #eef0f3;
    }
    .notif-header-title {
        font-weight: 600;
        font-size: 14px;
        color: #1f2937;
    }
    .notif-header-badge {
        background: #1a6fa8;
        color: #fff;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 10px;
    }

    .notif-list { max-height: 360px; overflow-y: auto; }

    .notif-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
    .notif-item:last-child { border-bottom: 0; }
    .notif-item-icon {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #1a6fa8;
        margin-top: 6px;
        flex-shrink: 0;
    }
    .notif-item.urgent .notif-item-icon { background: #e23b3b; }
    .notif-item-body { flex: 1; min-width: 0; }

    .notif-skel {
        background: #e2e8f0;
        display: block;
        border-radius: 3px;
        animation: notif-pulse 1.6s ease-in-out infinite;
    }

    .notif-footer {
        padding: 10px 16px;
        text-align: center;
        border-top: 1px solid #eef0f3;
    }
    .notif-footer a {
        color: #1a6fa8;
        font-size: 13px;
        text-decoration: none;
        font-weight: 500;
    }
    .notif-footer a:hover { text-decoration: underline; }
</style>

<header class="adm-topbar">
    <div class="adm-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Szukaj roweru, sprzętu, klienta...">
    </div>

    <div class="adm-topbar-actions">
        {{-- Dzwonek z popoverem powiadomień --}}
        <div class="notif-wrap">
            <button type="button" class="adm-icon-btn notif-btn" id="adm-notif-btn"
                    aria-label="Powiadomienia" aria-haspopup="true" aria-expanded="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="notif-dot" aria-hidden="true"></span>
            </button>

            <div class="notif-popover" id="adm-notif-popover" role="menu">
                <div class="notif-header">
                    <span class="notif-header-title">Powiadomienia</span>
                    <span class="notif-header-badge">
                        <span class="notif-skel" style="width:40px;height:10px;background:#ffffff55;display:inline-block;"></span>
                    </span>
                </div>

                <div class="notif-list">
                    <div class="notif-item urgent">
                        <div class="notif-item-icon"></div>
                        <div class="notif-item-body">
                            <span class="notif-skel" style="width:55%;height:12px;margin-bottom:6px;"></span>
                            <span class="notif-skel" style="width:90%;height:11px;margin-bottom:4px;"></span>
                            <span class="notif-skel" style="width:40%;height:10px;"></span>
                        </div>
                    </div>

                    <div class="notif-item">
                        <div class="notif-item-icon"></div>
                        <div class="notif-item-body">
                            <span class="notif-skel" style="width:70%;height:12px;margin-bottom:6px;"></span>
                            <span class="notif-skel" style="width:85%;height:11px;margin-bottom:4px;"></span>
                            <span class="notif-skel" style="width:35%;height:10px;"></span>
                        </div>
                    </div>

                    <div class="notif-item">
                        <div class="notif-item-icon"></div>
                        <div class="notif-item-body">
                            <span class="notif-skel" style="width:60%;height:12px;margin-bottom:6px;"></span>
                            <span class="notif-skel" style="width:75%;height:11px;margin-bottom:4px;"></span>
                            <span class="notif-skel" style="width:45%;height:10px;"></span>
                        </div>
                    </div>

                    <div class="notif-item">
                        <div class="notif-item-icon"></div>
                        <div class="notif-item-body">
                            <span class="notif-skel" style="width:50%;height:12px;margin-bottom:6px;"></span>
                            <span class="notif-skel" style="width:80%;height:11px;margin-bottom:4px;"></span>
                            <span class="notif-skel" style="width:40%;height:10px;"></span>
                        </div>
                    </div>
                </div>

                <div class="notif-footer">
                    <a href="#">Zobacz wszystkie powiadomienia</a>
                </div>
            </div>
        </div>

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

<script>
(function() {
    const btn     = document.getElementById('adm-notif-btn');
    const popover = document.getElementById('adm-notif-popover');
    if (!btn || !popover) return;

    function open()   { popover.classList.add('open');    btn.setAttribute('aria-expanded', 'true');  }
    function close()  { popover.classList.remove('open'); btn.setAttribute('aria-expanded', 'false'); }
    function toggle() { popover.classList.contains('open') ? close() : open(); }

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggle();
    });

    // klik poza popoverem - zamknij
    document.addEventListener('click', (e) => {
        if (!popover.classList.contains('open')) return;
        if (popover.contains(e.target)) return;
        close();
    });

    // Escape - zamknij
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && popover.classList.contains('open')) close();
    });
})();
</script>