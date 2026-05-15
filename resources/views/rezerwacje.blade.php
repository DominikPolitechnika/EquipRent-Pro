<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje rezerwacje – EquipRent Pro</title>

    <link rel="stylesheet" href="{{ asset('style-rezerwacje.css') }}">
    <style>
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.45; } }
        .placeholder { background: #e2e8f0; display: inline-block; border-radius: 3px; }
        .placeholder-block { background: #e2e8f0; display: block; border-radius: 3px; }
        .animate-pulse { animation: pulse 1.6s ease-in-out infinite; }
        .rez-card-img.placeholder { display: block; }
    </style>
</head>
<body class="rez-page">
@include('partials.header')
<div class="rez-page-header">
            <h1>Moje rezerwacje</h1>
            <p>Zarządzaj aktywnym sprzętem sportowym, śledź statystyki i pobieraj dokumentację.</p>
        </div>
<div class="rez-wrapper">

    {{-- ===== LEWA KOLUMNA ===== --}}
    <aside class="rez-sidebar">

        {{-- Terminy zwrotów --}}
        <div class="rez-sidebar-card-a">
            <div class="rez-sidebar-title">
                Terminy zwrotów
                <i class="fa-solid fa-circle-info" style="color:#1a6fa8; margin-left:6px;"></i>
            </div>

            <div class="rez-alert urgent">
                <i class="fa-solid fa-circle-exclamation rez-alert-icon"></i>
                <div style="flex:1; min-width:0;">
                    <div class="rez-alert-label">
                        <span class="placeholder-block animate-pulse" style="width:70%;height:14px;"></span>
                    </div>
                    <div class="rez-alert-sub">
                        <span class="placeholder-block animate-pulse" style="width:90%;height:12px;margin-top:6px;"></span>
                    </div>
                </div>
            </div>

            <div class="rez-alert upcoming">
                <i class="fa-regular fa-calendar rez-alert-icon"></i>
                <div style="flex:1; min-width:0;">
                    <div class="rez-alert-label">
                        <span class="placeholder-block animate-pulse" style="width:55%;height:14px;"></span>
                    </div>
                    <div class="rez-alert-sub">
                        <span class="placeholder-block animate-pulse" style="width:80%;height:12px;margin-top:6px;"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Przegląd konta --}}
        <div class="rez-sidebar-card">
            <div class="rez-sidebar-title">Przegląd konta</div>
            <div class="rez-stats-grid">
                <div class="rez-stat-item">
                    <div class="rez-stat-label">Łącznie wydano</div>
                    <div class="rez-stat-value">
                        <span class="placeholder-block animate-pulse" style="width:80%;height:22px;"></span>
                    </div>
                </div>
                <div class="rez-stat-item">
                    <div class="rez-stat-label">Wypożyczony sprzęt</div>
                    <div class="rez-stat-value">
                        <span class="placeholder-block animate-pulse" style="width:40%;height:22px;"></span>
                    </div>
                </div>
            </div>
        </div>

    </aside>

    {{-- ===== PRAWA KOLUMNA ===== --}}
    <main class="rez-content">



        {{-- AKTYWNE --}}
        <div class="rez-section-title">Aktywne wypożyczenia</div>

        {{-- Karta 1 --}}
        <div class="rez-card">
            <div class="rez-card-img placeholder animate-pulse"></div>
            <div class="rez-card-body">
                <div class="rez-card-top">
                    <span class="rez-card-name" style="flex:1; min-width:0;">
                        <span class="placeholder-block animate-pulse" style="width:75%;height:18px;"></span>
                    </span>
                    <span class="rez-badge wypozyczone">
                        <span class="placeholder animate-pulse" style="width:80px;height:12px;background:#ffffff55;"></span>
                    </span>
                </div>
          <div class="rez-card-meta">
    <div class="rez-card-meta-row">
        <span><span class="placeholder animate-pulse" style="width:90px;height:13px;"></span></span>
        <span><span class="placeholder animate-pulse" style="width:120px;height:13px;"></span></span>
    </div>
    <div class="rez-card-meta-row">
        <span>
            <svg class="rez-meta-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12,3A6,6,0,0,0,6,9c0,5,6,12,6,12s6-7,6-12A6,6,0,0,0,12,3Zm0,8a2,2,0,1,1,2-2A2,2,0,0,1,12,11Z" fill="#1a6fa8"/>
            </svg>
            <span class="placeholder animate-pulse" style="width:130px;height:13px;"></span>
        </span>
        <span>
            <svg class="rez-meta-icon" viewBox="0 -196 1416 1416" xmlns="http://www.w3.org/2000/svg">
    <g>
        <path d="M324.358919 22.140541H1361.643243c18.819459 0 33.210811 14.391351 33.210811 33.21081v645.396757c0 18.819459-14.391351 33.210811-33.210811 33.210811H324.358919c-18.819459 0-33.210811-14.391351-33.210811-33.210811V55.351351c0-18.819459 14.391351-33.210811 33.210811-33.21081z" fill="#1a6fa8"/>
        <path d="M230.261622 116.237838h1038.391351c18.819459 0 33.210811 14.391351 33.210811 33.210811v645.396756c0 18.819459-14.391351 33.210811-33.210811 33.210811H230.261622c-18.819459 0-33.210811-14.391351-33.210811-33.210811V149.448649c0-18.819459 14.391351-33.210811 33.210811-33.210811z" fill="#1a6fa8"/>
        <path d="M143.913514 208.121081h1038.391351c18.819459 0 33.210811 14.391351 33.210811 33.210811v645.396757c0 18.819459-14.391351 33.210811-33.210811 33.21081H143.913514c-18.819459 0-33.210811-14.391351-33.210811-33.21081V241.331892c0-17.712432 14.391351-33.210811 33.210811-33.210811z" fill="#1a6fa8"/>
        <path d="M55.351351 290.041081h1038.391352c18.819459 0 33.210811 14.391351 33.210811 33.210811v645.396757c0 18.819459-14.391351 33.210811-33.210811 33.21081H55.351351c-18.819459 0-33.210811-14.391351-33.21081-33.21081V323.251892c0-17.712432 14.391351-33.210811 33.21081-33.210811z" fill="#1a6fa8"/>
        <path d="M954.257297 902.227027H194.836757c0-52.03027-43.174054-95.204324-95.204325-95.204324V472.700541c52.03027 0 95.204324-43.174054 95.204325-95.204325h759.42054c0 52.03027 43.174054 95.204324 95.204325 95.204325v334.322162c-53.137297 0-95.204324 43.174054-95.204325 95.204324z" fill="#1a6fa8"/>
        <path d="M576.761081 790.417297c-35.424865 0-73.063784-13.284324-99.632432-47.602162-7.749189-9.963243-5.535135-23.247568 3.321081-30.996757 9.963243-7.749189 23.247568-5.535135 30.996756 3.321081 26.568649 34.317838 67.528649 35.424865 94.097298 26.568649 22.140541-7.749189 35.424865-22.140541 35.424865-37.638919 0-14.391351-34.317838-24.354595-64.207568-33.210811-46.495135-14.391351-105.167568-30.996757-105.167567-86.348108 0-26.568649 16.605405-50.923243 45.388108-65.314594 35.424865-17.712432 95.204324-24.354595 151.662702 16.605405 9.963243 7.749189 12.177297 21.033514 4.428108 30.996757-7.749189 9.963243-21.033514 12.177297-30.996756 4.428108-37.638919-27.675676-79.705946-26.568649-105.167568-13.284324-13.284324 6.642162-22.140541 16.605405-22.14054 26.568648 0 21.033514 30.996757 30.996757 73.063783 44.281081 45.388108 13.284324 95.204324 28.782703 95.204325 75.277838 0 34.317838-25.461622 65.314595-65.314595 79.705946-11.07027 3.321081-26.568649 6.642162-40.96 6.642162z" fill="#fff"/>
        <path d="M574.547027 549.085405c-12.177297 0-22.140541-9.963243-22.140541-22.14054v-48.709189c0-12.177297 9.963243-22.140541 22.140541-22.140541s22.140541 9.963243 22.140541 22.140541v48.709189c0 13.284324-9.963243 22.140541-22.140541 22.14054z" fill="#fff"/>
        <path d="M574.547027 832.484324c-12.177297 0-22.140541-9.963243-22.140541-22.14054v-36.531892c0-12.177297 9.963243-22.140541 22.140541-22.140541s22.140541 9.963243 22.140541 22.140541v36.531892c0 12.177297-9.963243 22.140541-22.140541 22.14054z" fill="#fff"/>
        <circle cx="285.612973" cy="653.145946" r="40.96" fill="#fff"/>
        <circle cx="865.695135" cy="653.145946" r="40.96" fill="#fff"/>
    </g>
</svg>
            <span class="placeholder animate-pulse" style="width:100px;height:13px;"></span>
        </span>
    </div>
</div>
            </div>
            <div class="rez-card-actions">
                <button class="rez-btn-cancel" onclick="openModal('this item')">
                    Anuluj rezerwację
                </button>
            </div>
        </div>

        {{-- Karta 2 --}}
        <div class="rez-card">
            <div class="rez-card-img placeholder animate-pulse"></div>
            <div class="rez-card-body">
                <div class="rez-card-top">
                    <span class="rez-card-name" style="flex:1; min-width:0;">
                        <span class="placeholder-block animate-pulse" style="width:70%;height:18px;"></span>
                    </span>
                    <span class="rez-badge oczekuje">
                        <span class="placeholder animate-pulse" style="width:70px;height:12px;background:#ffffff55;"></span>
                    </span>
                </div>
<div class="rez-card-meta">
    <div class="rez-card-meta-row">
        <span><span class="placeholder animate-pulse" style="width:90px;height:13px;"></span></span>
        <span><span class="placeholder animate-pulse" style="width:120px;height:13px;"></span></span>
    </div>
              <div class="rez-card-meta-row">
        <span>
            <svg class="rez-meta-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12,3A6,6,0,0,0,6,9c0,5,6,12,6,12s6-7,6-12A6,6,0,0,0,12,3Zm0,8a2,2,0,1,1,2-2A2,2,0,0,1,12,11Z" fill="#1a6fa8"/>
            </svg>
            <span class="placeholder animate-pulse" style="width:140px;height:13px;"></span>
        </span>
        <span>
            <svg class="rez-meta-icon" viewBox="0 -196 1416 1416" xmlns="http://www.w3.org/2000/svg">
    <g>
        <path d="M324.358919 22.140541H1361.643243c18.819459 0 33.210811 14.391351 33.210811 33.21081v645.396757c0 18.819459-14.391351 33.210811-33.210811 33.210811H324.358919c-18.819459 0-33.210811-14.391351-33.210811-33.210811V55.351351c0-18.819459 14.391351-33.210811 33.210811-33.21081z" fill="#1a6fa8"/>
        <path d="M230.261622 116.237838h1038.391351c18.819459 0 33.210811 14.391351 33.210811 33.210811v645.396756c0 18.819459-14.391351 33.210811-33.210811 33.210811H230.261622c-18.819459 0-33.210811-14.391351-33.210811-33.210811V149.448649c0-18.819459 14.391351-33.210811 33.210811-33.210811z" fill="#1a6fa8"/>
        <path d="M143.913514 208.121081h1038.391351c18.819459 0 33.210811 14.391351 33.210811 33.210811v645.396757c0 18.819459-14.391351 33.210811-33.210811 33.21081H143.913514c-18.819459 0-33.210811-14.391351-33.210811-33.21081V241.331892c0-17.712432 14.391351-33.210811 33.210811-33.210811z" fill="#1a6fa8"/>
        <path d="M55.351351 290.041081h1038.391352c18.819459 0 33.210811 14.391351 33.210811 33.210811v645.396757c0 18.819459-14.391351 33.210811-33.210811 33.21081H55.351351c-18.819459 0-33.210811-14.391351-33.21081-33.21081V323.251892c0-17.712432 14.391351-33.210811 33.21081-33.210811z" fill="#1a6fa8"/>
        <path d="M954.257297 902.227027H194.836757c0-52.03027-43.174054-95.204324-95.204325-95.204324V472.700541c52.03027 0 95.204324-43.174054 95.204325-95.204325h759.42054c0 52.03027 43.174054 95.204324 95.204325 95.204325v334.322162c-53.137297 0-95.204324 43.174054-95.204325 95.204324z" fill="#1a6fa8"/>
        <path d="M576.761081 790.417297c-35.424865 0-73.063784-13.284324-99.632432-47.602162-7.749189-9.963243-5.535135-23.247568 3.321081-30.996757 9.963243-7.749189 23.247568-5.535135 30.996756 3.321081 26.568649 34.317838 67.528649 35.424865 94.097298 26.568649 22.140541-7.749189 35.424865-22.140541 35.424865-37.638919 0-14.391351-34.317838-24.354595-64.207568-33.210811-46.495135-14.391351-105.167568-30.996757-105.167567-86.348108 0-26.568649 16.605405-50.923243 45.388108-65.314594 35.424865-17.712432 95.204324-24.354595 151.662702 16.605405 9.963243 7.749189 12.177297 21.033514 4.428108 30.996757-7.749189 9.963243-21.033514 12.177297-30.996756 4.428108-37.638919-27.675676-79.705946-26.568649-105.167568-13.284324-13.284324 6.642162-22.140541 16.605405-22.14054 26.568648 0 21.033514 30.996757 30.996757 73.063783 44.281081 45.388108 13.284324 95.204324 28.782703 95.204325 75.277838 0 34.317838-25.461622 65.314595-65.314595 79.705946-11.07027 3.321081-26.568649 6.642162-40.96 6.642162z" fill="#fff"/>
        <path d="M574.547027 549.085405c-12.177297 0-22.140541-9.963243-22.140541-22.14054v-48.709189c0-12.177297 9.963243-22.140541 22.140541-22.140541s22.140541 9.963243 22.140541 22.140541v48.709189c0 13.284324-9.963243 22.140541-22.140541 22.14054z" fill="#fff"/>
        <path d="M574.547027 832.484324c-12.177297 0-22.140541-9.963243-22.140541-22.14054v-36.531892c0-12.177297 9.963243-22.140541 22.140541-22.140541s22.140541 9.963243 22.140541 22.140541v36.531892c0 12.177297-9.963243 22.140541-22.140541 22.14054z" fill="#fff"/>
        <circle cx="285.612973" cy="653.145946" r="40.96" fill="#fff"/>
        <circle cx="865.695135" cy="653.145946" r="40.96" fill="#fff"/>
    </g>
</svg>
            <span class="placeholder animate-pulse" style="width:100px;height:13px;"></span>
        </span>
    </div>
    </div>
            </div>
            <div class="rez-card-actions">
                <button class="rez-btn-cancel" onclick="openModal('this item')">
                    Anuluj rezerwację
                </button>
            </div>
        </div>

        {{-- ZAKOŃCZONE --}}
        <div class="rez-section-title done" style="margin-top: 32px;">Zakończone wypożyczenia</div>
{{-- Zakończona 1 --}}
<div class="rez-card-done" id="done-1">
    <div class="rez-card-img placeholder animate-pulse"></div>
    <div class="rez-card-body">
        <div class="rez-card-name" style="margin-bottom:4px;">
            <span class="placeholder-block animate-pulse" style="width:65%;max-width:240px;height:16px;"></span>
        </div>
        <div class="rez-done-meta">
            <span class="placeholder-block animate-pulse" style="width:80%;max-width:260px;height:12px;"></span>
        </div>
        <div class="rez-done-cost">
            <span class="placeholder-block animate-pulse" style="width:50%;max-width:180px;height:14px;"></span>
        </div>
        <div>
            <span class="rez-stars">
                <span class="placeholder animate-pulse" style="width:80px;height:13px;"></span>
            </span>
            <span class="rez-review-quote">
                <span class="placeholder animate-pulse" style="width:180px;height:12px;margin-left:6px;"></span>
            </span>
        </div>
        <button class="rez-add-review-toggle" onclick="toggleReview('review-1')">
             <svg width="12" height="12" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="#1a6fa8">
        <path d="M480,1117 C472.268,1117 466,1110.73 466,1103 C466,1095.27 472.268,1089 480,1089 C487.732,1089 494,1095.27 494,1103 C494,1110.73 487.732,1117 480,1117 L480,1117 Z M480,1087 C471.163,1087 464,1094.16 464,1103 C464,1111.84 471.163,1119 480,1119 C488.837,1119 496,1111.84 496,1103 C496,1094.16 488.837,1087 480,1087 L480,1087 Z M486,1102 L481,1102 L481,1097 C481,1096.45 480.553,1096 480,1096 C479.447,1096 479,1096.45 479,1097 L479,1102 L474,1102 C473.447,1102 473,1102.45 473,1103 C473,1103.55 473.447,1104 474,1104 L479,1104 L479,1109 C479,1109.55 479.447,1110 480,1110 C480.553,1110 481,1109.55 481,1109 L481,1104 L486,1104 C486.553,1104 487,1103.55 487,1103 C487,1102.45 486.553,1102 486,1102 L486,1102 Z" transform="translate(-464, -1087)"/>
    </svg>
    <i class="fa-solid fa-plus"></i> Dodaj opinię
        </button>
    </div>
    <button class="rez-btn-rebook">Wypożycz ponownie</button>
</div>

{{-- Panel dodawania opinii (Deska SUP) --}}
<div class="rez-review-panel" id="review-1">
    <div class="rez-review-panel-title">Podziel się swoją opinią</div>
    <div class="rez-star-input" id="stars-1">
        <span data-v="1">★</span>
        <span data-v="2">★</span>
        <span data-v="3">★</span>
        <span data-v="4">★</span>
        <span data-v="5">★</span>
    </div>
    <textarea class="rez-review-textarea" placeholder="Opisz swoje wrażenia..."></textarea>
    <div class="rez-review-actions">
        <button class="rez-btn-cancel-review" onclick="toggleReview('review-1')">Anuluj</button>
        <button class="rez-btn-submit-review">Wyślij opinię</button>
    </div>
</div>

        {{-- Zakończona 2 --}}
        <div class="rez-card-done" id="done-2">
            <div class="rez-card-img placeholder animate-pulse"></div>
            <div class="rez-card-body">
                <div class="rez-card-name" style="margin-bottom:4px;">
                    <span class="placeholder-block animate-pulse" style="width:65%;max-width:240px;height:16px;"></span>
                </div>
                <div class="rez-done-meta">
                    <span class="placeholder-block animate-pulse" style="width:80%;max-width:260px;height:12px;"></span>
                </div>
                <div class="rez-done-cost">
                    <span class="placeholder-block animate-pulse" style="width:50%;max-width:180px;height:14px;"></span>
                </div>
                <button class="rez-add-review-toggle" onclick="toggleReview('review-2')">
             <svg width="12" height="12" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="#1a6fa8">
        <path d="M480,1117 C472.268,1117 466,1110.73 466,1103 C466,1095.27 472.268,1089 480,1089 C487.732,1089 494,1095.27 494,1103 C494,1110.73 487.732,1117 480,1117 L480,1117 Z M480,1087 C471.163,1087 464,1094.16 464,1103 C464,1111.84 471.163,1119 480,1119 C488.837,1119 496,1111.84 496,1103 C496,1094.16 488.837,1087 480,1087 L480,1087 Z M486,1102 L481,1102 L481,1097 C481,1096.45 480.553,1096 480,1096 C479.447,1096 479,1096.45 479,1097 L479,1102 L474,1102 C473.447,1102 473,1102.45 473,1103 C473,1103.55 473.447,1104 474,1104 L479,1104 L479,1109 C479,1109.55 479.447,1110 480,1110 C480.553,1110 481,1109.55 481,1109 L481,1104 L486,1104 C486.553,1104 487,1103.55 487,1103 C487,1102.45 486.553,1102 486,1102 L486,1102 Z" transform="translate(-464, -1087)"/>
    </svg>
    <i class="fa-solid fa-plus"></i> Dodaj opinię
        </button>
            </div>
            <button class="rez-btn-rebook">Wypożycz ponownie</button>
        </div>

        {{-- Panel dodawania opinii (namiot) --}}
        <div class="rez-review-panel" id="review-2">
            <div class="rez-review-panel-title">Podziel się swoją opinią</div>
            <div class="rez-star-input" id="stars-2">
                <span data-v="1">★</span>
                <span data-v="2">★</span>
                <span data-v="3">★</span>
                <span data-v="4">★</span>
                <span data-v="5">★</span>
            </div>
            <textarea class="rez-review-textarea" placeholder="Opisz swoje wrażenia..."></textarea>
            <div class="rez-review-actions">
                <button class="rez-btn-cancel-review" onclick="toggleReview('review-2')">Anuluj</button>
                <button class="rez-btn-submit-review">Wyślij opinię</button>
            </div>
        </div>

    </main>
</div>

{{-- Modal anulowania --}}
<div class="rez-modal-backdrop" id="cancel-modal">
    <div class="rez-modal">
        <h3>Anuluj rezerwację</h3>
        <p id="modal-text">Czy na pewno chcesz anulować rezerwację?<br>Ta operacja jest nieodwracalna.</p>
        <div class="rez-modal-actions">
            <button class="rez-btn-cancel-review" onclick="closeModal()">Wróć</button>
            <button class="rez-btn-cancel" onclick="closeModal()">Tak, anuluj</button>
        </div>
    </div>
</div>

<script>
    /* ---- Modal ---- */
    function openModal(name) {
        document.getElementById('modal-text').innerHTML =
            `Czy na pewno chcesz anulować tę rezerwację?<br>Ta operacja jest nieodwracalna.`;
        document.getElementById('cancel-modal').classList.add('open');
    }
    function closeModal() {
        document.getElementById('cancel-modal').classList.remove('open');
    }
    document.getElementById('cancel-modal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    /* ---- Panel opinii ---- */
    function toggleReview(id) {
        document.getElementById(id).classList.toggle('open');
    }

    /* ---- Gwiazdki ---- */
    document.querySelectorAll('.rez-star-input').forEach(container => {
        const stars = container.querySelectorAll('span');
        let selected = 0;

        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                stars.forEach(s => s.classList.remove('active'));
                for (let i = 0; i < star.dataset.v; i++) stars[i].classList.add('active');
            });
            star.addEventListener('mouseout', () => {
                stars.forEach(s => s.classList.remove('active'));
                for (let i = 0; i < selected; i++) stars[i].classList.add('active');
            });
            star.addEventListener('click', () => {
                selected = parseInt(star.dataset.v);
            });
        });
    });
</script>
@include('partials.footer')
</body>
</html>