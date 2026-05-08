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
            border-color: #2563eb;
        }

        .rez-alert-icon {
            font-size: 14px;
            margin-top: 1px;
            flex-shrink: 0;
        }
        .rez-alert.urgent  .rez-alert-icon { color: #dc2626; }
        .rez-alert.upcoming .rez-alert-icon { color: #2563eb; }

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
            font-size: 26px;
            font-weight: 900;
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
            background: #2563eb;
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
            gap: 16px;
            flex-wrap: wrap;
        }
        .rez-card-meta span { display: flex; align-items: center; gap: 5px; }
        .rez-card-meta i    { color: #aaa; font-size: 11px; }

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
            font-weight: 600;
            color: #2a3439;
            margin-bottom: 6px;
        }

        .rez-stars {
            color: #2563eb;
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
            background: #2563eb;
            border: none;
            color: #fff;
            cursor: pointer;
            white-space: nowrap;
            transition: background .2s;
            flex-shrink: 0;
            margin-right: 18px;
        }
        .rez-btn-rebook:hover { background: #1d4ed8; }

        /* Dodaj opinię */
        .rez-add-review-toggle {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #2563eb;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 6px;
        }
        .rez-add-review-toggle:hover { text-decoration: underline; }

        /* Panel opinii */
        .rez-review-panel {
            background: #f7f9fb;
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
        .rez-star-input span.active { color: #2563eb; }

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
        .rez-review-textarea:focus { outline: none; border-color: #2563eb; }

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
            background: #2a3439;
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
                <i class="fa-solid fa-circle-info" style="color:#2563eb; margin-left:6px;"></i>
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
                    <span>ID: #SP-48291</span>
                    <span>12 paź – 22 paź</span>
                    <span><i class="fa-solid fa-location-dot"></i> Punkt Centrum A</span>
                    <span><i class="fa-solid fa-credit-card"></i> 450 zł/tydz.</span>
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
                    <span>ID: #SP-48552</span>
                    <span>25 paź – 05 lis</span>
                    <span><i class="fa-solid fa-location-dot"></i> Magazyn Północ</span>
                    <span><i class="fa-solid fa-credit-card"></i> 220 zł/tydz.</span>
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