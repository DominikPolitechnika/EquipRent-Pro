<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>O nas — EquipRent Pro</title>
    <link rel="icon" type="image/png" href="{{ asset('E.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style-head.css') }}">
    <link rel="stylesheet" href="{{ asset('style-foot.css') }}">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f7f7f8;
            color: #111827;
        }
        .info-page {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 40px 80px;
        }
        .info-breadcrumb {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 20px;
        }
        .info-breadcrumb a { color: #6b7280; text-decoration: none; }
        .info-breadcrumb a:hover { color: #075071; }
        .info-header h1 {
            font-size: 42px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -.01em;
            margin: 0 0 10px;
        }
        .info-header p.lead {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.6;
            margin: 0 0 40px;
            max-width: 620px;
        }
        .info-section {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 28px 32px;
            margin-bottom: 20px;
        }
        .info-section h2 {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #111827;
            margin: 0 0 14px;
        }
        .info-section p {
            font-size: 14px;
            line-height: 1.7;
            color: #4b5563;
            margin: 0 0 12px;
        }
        .info-section p:last-child { margin-bottom: 0; }
        .info-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin: 30px 0;
        }
        .info-stat {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 22px;
            text-align: center;
        }
        .info-stat-value {
            font-size: 34px;
            font-weight: 900;
            color: #075071;
            line-height: 1;
            margin-bottom: 6px;
        }
        .info-stat-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #9aa5ad;
        }
        .info-cta {
            background: #075071;
            color: #fff;
            border-radius: 12px;
            padding: 36px;
            text-align: center;
            margin-top: 24px;
        }
        .info-cta h3 {
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 10px;
        }
        .info-cta p {
            margin: 0 0 20px;
            opacity: .9;
            font-size: 14px;
        }
        .info-cta-btn {
            display: inline-block;
            background: #fff;
            color: #075071;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 12px 26px;
            border-radius: 8px;
            text-decoration: none;
            transition: transform .15s;
        }
        .info-cta-btn:hover { transform: translateY(-1px); }

        @media (max-width: 700px) {
            .info-page { padding: 20px 20px 60px; }
            .info-header h1 { font-size: 30px; }
            .info-stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

@include('partials.header')

<main class="info-page">

    <div class="info-breadcrumb">
        <a href="{{ url('/catalog') }}">Strona główna</a> › <span>O nas</span>
    </div>

    <div class="info-header">
        <h1>O EquipRent Pro</h1>
        <p class="lead">
            Jesteśmy wypożyczalnią profesjonalnego sprzętu sportowego dla wymagających klientów —
            od zawodowych sportowców po amatorów, którzy nie akceptują kompromisów.
        </p>
    </div>

    <div class="info-section">
        <h2>Nasza misja</h2>
        <p>
            Wierzymy, że dostęp do najlepszego sprzętu sportowego nie powinien być zarezerwowany dla nielicznych.
            Zamiast wydawać tysiące złotych na sprzęt, którego użyjesz kilkanaście razy w roku, możesz wypożyczyć
            u nas dokładnie to, czego potrzebujesz — kiedy tego potrzebujesz.
        </p>
        <p>
            Dbamy o każdy element floty: regularne serwisy, wymiana zużytych części, testy jakości przed każdym wynajmem.
            Kiedy odbierasz sprzęt od nas, wiesz, że jest w stanie idealnym.
        </p>
    </div>

    <div class="info-stats">
        <div class="info-stat">
            <div class="info-stat-value">200+</div>
            <div class="info-stat-label">Jednostek sprzętu</div>
        </div>
        <div class="info-stat">
            <div class="info-stat-value">8</div>
            <div class="info-stat-label">Kategorii dyscyplin</div>
        </div>
        <div class="info-stat">
            <div class="info-stat-value">2026</div>
            <div class="info-stat-label">Rok założenia</div>
        </div>
    </div>

    <div class="info-section">
        <h2>Co oferujemy</h2>
        <p>
            Nasza flota obejmuje osiem kategorii sprzętu sportowego: siłownia, fitness, sporty drużynowe,
            sporty zimowe, sporty wodne, turystyka, rowery i bieganie. Współpracujemy z renomowanymi
            markami — Wilson, Adidas, Shimano, Garmin, Nike, Polar i wieloma innymi.
        </p>
        <p>
            Wypożyczenie odbywa się w kilku krokach: przeglądasz katalog, wybierasz daty w kalendarzu,
            finalizujesz płatność online. Sprzęt jest gotowy do odbioru w wybranym terminie.
        </p>
    </div>

    <div class="info-section">
        <h2>Dlaczego my</h2>
        <p>
            <strong>Sprzęt najwyższej jakości.</strong> Nie kupujemy tanio i szybko — inwestujemy w sprzęt, który
            wytrzyma setki wypożyczeń bez utraty parametrów.
        </p>
        <p>
            <strong>Przejrzyste zasady.</strong> Cena za dobę, bez ukrytych opłat. Anulowanie do 48h przed odbiorem
            bez konsekwencji.
        </p>
        <p>
            <strong>Wsparcie zespołu.</strong> Nasi specjaliści doradzą Ci sprzęt dopasowany do Twojego poziomu
            i planowanej aktywności.
        </p>
    </div>

    <div class="info-cta">
        <h3>Gotowy na trening?</h3>
        <p>Wybierz sprzęt z naszego katalogu i zarezerwuj termin już dziś.</p>
        <a href="{{ url('/catalog') }}" class="info-cta-btn">Przejdź do katalogu →</a>
    </div>

</main>

@include('partials.footer')

</body>
</html>
