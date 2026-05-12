<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje rezerwacje – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('css/style-prod.css') }}">
    <style>
        /* ================================================
           REZERWACJE PAGE
        ================================================ */
       .rez-page {
    margin: 0;
    padding: 0;
    font-family: 'Barlow', sans-serif;
    background: #f7f9fb;
    min-height: 100vh;
    color: #2a3439;
}

        .rez-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 40px 80px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 32px;
            align-items: start;
        }

        /* ---- LEWA KOLUMNA ---- */
        .rez-sidebar {
            position: sticky;
            top: 24px;
        }

        .rez-sidebar-card {
            background: #f0f4f7;
            border: 1px solid #e8ebee;
            padding: 20px;
            margin-bottom: 16px;
        }
         .rez-sidebar-card-a{
            background: #fff;
            border: 1px solid #e8ebee;
            padding: 20px;
            margin-bottom: 16px;
        }

        .rez-sidebar-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #aaa;
            margin-bottom: 14px;
        }

        /* Alerty terminów */
        .rez-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 10px;
            border-left: 3px solid;
        }
        .rez-alert:last-child { margin-bottom: 0; }

        .rez-alert.urgent {
            background: #fff5f5;
            border-color: #dc2626;
        }
        .rez-alert.upcoming {
            background: #eff6ff;
            border-color: #1a6fa8;
        }

        .rez-alert-icon {
            font-size: 14px;
            margin-top: 1px;
            flex-shrink: 0;
        }
        .rez-alert.urgent  .rez-alert-icon { color: #dc2626; }
        .rez-alert.upcoming .rez-alert-icon { color: #1a6fa8; }

        .rez-alert-label {
            font-size: 12px;
            font-weight: 700;
            color: #2a3439;
            margin-bottom: 2px;
        }
        .rez-alert.urgent .rez-alert-label { color: #dc2626; }

        .rez-alert-sub {
            font-size: 11px;
            color: #777;
            line-height: 1.4;
        }

        /* Stats konta */
        .rez-stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1px;
            background: #e8ebee;
            border: 1px solid #e8ebee;
        }
        .rez-stat-item {
            background: #fff;
            padding: 16px 14px;
        }
        .rez-stat-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #aaa;
            margin-bottom: 6px;
        }
        .rez-stat-value {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 18x;
            font-weight: 600;
            color: #2a3439;
            line-height: 1;
        }

        /* ---- PRAWA KOLUMNA ---- */
        .rez-content {}

     .rez-page-header {
    max-width: 1200px;
    margin: 0 auto;
    padding: 10px 10px 1px 0px;
    box-sizing: border-box;
}
        .rez-page-header h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 36px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .02em;
            color: #2a3439;
            margin: 0 0 6px;
        }
        .rez-page-header p {
            font-size: 13px;
            color: #777;
            margin: 0;
        }

        /* Sekcja */
        .rez-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #2a3439;
            margin-bottom: 12px;
        }
        .rez-section-title::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #1a6fa8;
            flex-shrink: 0;
        }
        .rez-section-title.done::before { background: #aaa; }

        /* Karta rezerwacji (aktywna) */
        .rez-card {
            background: #fff;
            border: 1px solid #e8ebee;
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .rez-card-img {
            width: 100px;
            height: 80px;
            object-fit: cover;
            flex-shrink: 0;
            display: block;
            margin: 12px 0 12px 12px;
            border-radius: 6px;
        }

        .rez-card-body {
            padding: 14px 18px;
            flex: 1;
            min-width: 0;
        }

        .rez-card-top {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }

        .rez-card-name {
            font-size: 15px;
            font-weight: 600;
            color: #2a3439;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rez-badge {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 3px 8px;
            flex-shrink: 0;
        }
        .rez-badge.wypozyczone { background: #1a6fa8; color: #fff; }
        .rez-badge.oczekuje    { background: #e8ebee; color: #555; }

.rez-card-meta {
    font-size: 12px;
    color: #777;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.rez-card-meta-row {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}
.rez-card-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
}
.rez-meta-icon {
    width: 13px;
    height: 13px;
    flex-shrink: 0;
}

        .rez-card-actions {
            padding: 0 18px;
            flex-shrink: 0;
        }

        .rez-btn-cancel {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 9px 16px;
            background: transparent;
            border: 1px solid #dc2626;
            color: #dc2626;
            cursor: pointer;
            white-space: nowrap;
            transition: background .2s, color .2s;
        }
        .rez-btn-cancel:hover { background: #dc2626; color: #fff; }

        /* Karta zakończona */
        .rez-card-done {
            background: #fff;
            border: 1px solid #e8ebee;
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .rez-card-done .rez-card-img {
            filter: grayscale(30%);
        }

        .rez-card-done .rez-card-body {
            padding: 14px 18px;
            flex: 1;
        }

        .rez-done-meta {
            font-size: 12px;
            color: #777;
            margin-bottom: 4px;
        }

        .rez-done-cost {
            font-size: 13px;
           
            color: #2a3439;
            margin-bottom: 6px;
        }

        .rez-stars {
            color: #1a6fa8;
            font-size: 13px;
            letter-spacing: 2px;
            margin-right: 6px;
        }

        .rez-review-quote {
            font-size: 12px;
            color: #555;
            font-style: italic;
        }

        .rez-btn-rebook {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 9px 16px;
            background: #006398;
            border: none;
            color: #fff;
            cursor: pointer;
            white-space: nowrap;
            transition: background .2s;
            flex-shrink: 0;
            margin-right: 18px;
        }
        .rez-btn-rebook:hover { background: #96b0f7; }

        /* Dodaj opinię */
        .rez-add-review-toggle {
            
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #1a6fa8;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
           gap: 2px;
            margin-top: 6px;
        }
        .rez-add-review-toggle:hover { text-decoration: underline; }

        /* Panel opinii */
        .rez-review-panel {
            background: #f0f4f7;
            border: 1px solid #e8ebee;
            border-top: none;
            padding: 18px 20px;
            display: none;
            margin-bottom: 10px;
        }
        .rez-review-panel.open { display: block; }

        .rez-review-panel-title {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #aaa;
            margin-bottom: 10px;
        }

        .rez-star-input {
            font-size: 22px;
            cursor: pointer;
            letter-spacing: 4px;
            color: #d1d5db;
            margin-bottom: 10px;
            display: inline-block;
            user-select: none;
        }
        .rez-star-input span { transition: color .1s; }
        .rez-star-input span:hover,
        .rez-star-input span.active { color: #1a6fa8; }

        .rez-review-textarea {
            width: 100%;
            background: #fff;
            border: 1px solid #e8ebee;
            color: #2a3439;
            padding: 10px 12px;
            font-family: 'Barlow', sans-serif;
            font-size: 13px;
            resize: vertical;
            min-height: 80px;
            box-sizing: border-box;
        }
        .rez-review-textarea::placeholder { color: #aaa; }
        .rez-review-textarea:focus { outline: none; border-color: #1a6fa8; }

        .rez-review-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        .rez-btn-cancel-review {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 9px 16px;
            background: transparent;
            border: 1px solid #e8ebee;
            color: #777;
            cursor: pointer;
        }
        .rez-btn-cancel-review:hover { border-color: #aaa; color: #444; }

        .rez-btn-submit-review {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 9px 20px;
            background: #006398;
            border: none;
            color: #fff;
            cursor: pointer;
            transition: background .2s;
        }
        .rez-btn-submit-review:hover { background: #333; }

        /* Modal anulowania */
        .rez-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .rez-modal-backdrop.open { display: flex; }

        .rez-modal {
            background: #fff;
            border: 1px solid #e8ebee;
            padding: 32px;
            max-width: 420px;
            width: 90%;
        }
        .rez-modal h3 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 10px;
            color: #2a3439;
        }
        .rez-modal p {
            font-size: 13px;
            color: #555;
            line-height: 1.6;
            margin: 0 0 24px;
        }
        .rez-modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Responsywność */
        @media (max-width: 900px) {
            .rez-wrapper {
                grid-template-columns: 1fr;
                padding: 20px;
            }
            .rez-sidebar { position: static; }
            .rez-card, .rez-card-done { flex-wrap: wrap; }
            .rez-card-img { width: 100%; height: 160px; }
            .rez-card-actions { padding: 0 18px 14px; }
            .rez-btn-rebook { margin: 0 18px 14px; }
            .rez-page-header {padding: 20px;}
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
                <div>
                    <div class="rez-alert-label">Termin zwrotu: pozostało 4h</div>
                    <div class="rez-alert-sub">Rower górski Specialized Stumpjumper</div>
                </div>
            </div>

            <div class="rez-alert upcoming">
                <i class="fa-regular fa-calendar rez-alert-icon"></i>
                <div>
                    <div class="rez-alert-label">Odbiór: jutro</div>
                    <div class="rez-alert-sub">Zestaw do tenisa stołowego Pro</div>
                </div>
            </div>
        </div>

        {{-- Przegląd konta --}}
        <div class="rez-sidebar-card">
            <div class="rez-sidebar-title">Przegląd konta</div>
            <div class="rez-stats-grid">
                <div class="rez-stat-item">
                    <div class="rez-stat-label">Łącznie wydano</div>
                    <div class="rez-stat-value">12 450 zł</div>
                </div>
                <div class="rez-stat-item">
                    <div class="rez-stat-label">Wypożyczony sprzęt</div>
                    <div class="rez-stat-value">18</div>
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
            <img class="rez-card-img"
                 src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=200&h=160&fit=crop"
                 alt="Rower Specialized Stumpjumper">
            <div class="rez-card-body">
                <div class="rez-card-top">
                    <span class="rez-card-name">Rower Specialized Stumpjumper</span>
                    <span class="rez-badge wypozyczone">Wypożyczone</span>
                </div>
          <div class="rez-card-meta">
    <div class="rez-card-meta-row">
        <span>ID: #SP-48291</span>
        <span>12 paź – 22 paź</span>
    </div>
    <div class="rez-card-meta-row">
        <span>
            <svg class="rez-meta-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12,3A6,6,0,0,0,6,9c0,5,6,12,6,12s6-7,6-12A6,6,0,0,0,12,3Zm0,8a2,2,0,1,1,2-2A2,2,0,0,1,12,11Z" fill="#1a6fa8"/>
            </svg>
            Punkt Centrum A
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
            450 zł/tydz.
        </span>
    </div>
</div>
            </div>
            <div class="rez-card-actions">
                <button class="rez-btn-cancel" onclick="openModal('Rower Specialized Stumpjumper')">
                    Anuluj rezerwację
                </button>
            </div>
        </div>

        {{-- Karta 2 --}}
        <div class="rez-card">
            <img class="rez-card-img"
                 src="https://images.unsplash.com/photo-1501854140801-50d01698950b?w=200&h=160&fit=crop"
                 alt="Zestaw do trekkingu Pro">
            <div class="rez-card-body">
                <div class="rez-card-top">
                    <span class="rez-card-name">Zestaw do trekkingu Pro</span>
                    <span class="rez-badge oczekuje">Oczekuje</span>
                </div>
<div class="rez-card-meta">
    <div class="rez-card-meta-row">
        <span>ID: #SP-48291</span>
        <span>12 paź – 22 paź</span>
    </div>
              <div class="rez-card-meta-row">
        <span>
            <svg class="rez-meta-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12,3A6,6,0,0,0,6,9c0,5,6,12,6,12s6-7,6-12A6,6,0,0,0,12,3Zm0,8a2,2,0,1,1,2-2A2,2,0,0,1,12,11Z" fill="#1a6fa8"/>
            </svg>
            Magazyn Północ
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
            220 zł/tydz.
        </span>
    </div>
    </div>
            </div>
            <div class="rez-card-actions">
                <button class="rez-btn-cancel" onclick="openModal('Zestaw do trekkingu Pro')">
                    Anuluj rezerwację
                </button>
            </div>
        </div>

        {{-- ZAKOŃCZONE --}}
        <div class="rez-section-title done" style="margin-top: 32px;">Zakończone wypożyczenia</div>
{{-- Zakończona 1 --}}
<div class="rez-card-done" id="done-1">
    <img class="rez-card-img"
         src="https://images.unsplash.com/photo-1531722569936-825d4eee19b7?w=200&h=160&fit=crop"
         alt="Deska SUP Red Paddle Co">
    <div class="rez-card-body">
        <div class="rez-card-name" style="margin-bottom:4px;">Deska SUP Red Paddle Co</div>
        <div class="rez-done-meta">Zwrócono 05 paź 2023 • 14 dni wypożyczenia</div>
        <div class="rez-done-cost">Łączny koszt: 980,00 zł</div>
        <div>
            <span class="rez-stars">★★★★★</span>
            <span class="rez-review-quote">"Świetna stabilność na wodzie"</span>
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
            <img class="rez-card-img"
                 src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=200&h=160&fit=crop"
                 alt="Namiot wyprawowy Thule">
            <div class="rez-card-body">
                <div class="rez-card-name" style="margin-bottom:4px;">Namiot wyprawowy Thule</div>
                <div class="rez-done-meta">Zwrócono 28 wrz 2023 • 3 dni wypożyczenia</div>
                <div class="rez-done-cost">Łączny koszt: 240,00 zł</div>
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
            `Czy na pewno chcesz anulować rezerwację <strong>${name}</strong>?<br>Ta operacja jest nieodwracalna.`;
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