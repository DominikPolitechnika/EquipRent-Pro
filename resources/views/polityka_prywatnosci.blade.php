<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Polityka prywatności — EquipRent Pro</title>
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
        .doc-page {
            max-width: 820px;
            margin: 0 auto;
            padding: 40px 40px 80px;
        }
        .doc-breadcrumb {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 20px;
        }
        .doc-breadcrumb a { color: #6b7280; text-decoration: none; }
        .doc-breadcrumb a:hover { color: #075071; }
        .doc-header h1 {
            font-size: 38px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -.01em;
            margin: 0 0 6px;
        }
        .doc-updated {
            font-size: 12px;
            color: #9aa5ad;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 32px;
        }
        .doc-body {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 36px 40px;
        }
        .doc-body h2 {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #075071;
            margin: 28px 0 12px;
        }
        .doc-body h2:first-child { margin-top: 0; }
        .doc-body p {
            font-size: 14px;
            line-height: 1.7;
            color: #4b5563;
            margin: 0 0 12px;
        }
        .doc-body ol, .doc-body ul {
            font-size: 14px;
            line-height: 1.7;
            color: #4b5563;
            padding-left: 22px;
            margin: 0 0 14px;
        }
        .doc-body li { margin-bottom: 6px; }
        .doc-body strong { color: #111827; }
        .doc-body a { color: #075071; }

        .doc-rights-box {
            background: #eef3f7;
            border: 1px solid #d1dae2;
            border-radius: 8px;
            padding: 18px 22px;
            margin: 16px 0;
        }
        .doc-rights-box strong { display: block; margin-bottom: 8px; color: #075071; }

        @media (max-width: 700px) {
            .doc-page { padding: 20px 20px 60px; }
            .doc-body { padding: 24px 22px; }
            .doc-header h1 { font-size: 24px; }
        }
    </style>
</head>
<body>

@include('partials.header')

<main class="doc-page">

    <div class="doc-breadcrumb">
        <a href="{{ url('/catalog') }}">Strona główna</a> › <span>Polityka prywatności</span>
    </div>

    <div class="doc-header">
        <h1>Polityka prywatności</h1>
        <div class="doc-updated">Ostatnia aktualizacja: 15.08.2026</div>
    </div>

    <div class="doc-body">

        <h2>1. Administrator danych</h2>
        <p>
            Administratorem danych osobowych zbieranych za pośrednictwem serwisu <strong>EquipRent Pro</strong>
            jest firma EquipRent Pro Sp. z o.o. z siedzibą w Warszawie.
        </p>
        <p>
            Kontakt w sprawach ochrony danych: <strong>rodo@equiprentpro.pl</strong>.
        </p>

        <h2>2. Jakie dane zbieramy</h2>
        <p>W ramach korzystania z serwisu przetwarzamy następujące dane osobowe:</p>
        <ul>
            <li>Imię i nazwisko</li>
            <li>Adres e-mail</li>
            <li>Numer telefonu (opcjonalnie)</li>
            <li>Nazwa klubu sportowego (opcjonalnie)</li>
            <li>Adres IP oraz informacje o przeglądarce</li>
            <li>Historia rezerwacji i płatności</li>
            <li>Opinie o produktach</li>
        </ul>

        <h2>3. W jakim celu przetwarzamy dane</h2>
        <ol>
            <li><strong>Realizacja umowy wypożyczenia</strong> — obsługa rezerwacji, płatności, zwrotu sprzętu.</li>
            <li><strong>Prowadzenie konta użytkownika</strong> — logowanie, zarządzanie profilem.</li>
            <li><strong>Obsługa reklamacji</strong> — kontakt w sprawie zgłoszonych roszczeń.</li>
            <li><strong>Wystawianie faktur</strong> — realizacja obowiązków podatkowych.</li>
            <li><strong>Bezpieczeństwo systemu</strong> — logowanie zdarzeń, wykrywanie nadużyć.</li>
        </ol>

        <h2>4. Podstawa prawna</h2>
        <p>Przetwarzanie danych opiera się na następujących podstawach prawnych (RODO):</p>
        <ul>
            <li>Art. 6 ust. 1 lit. b — wykonanie umowy (rezerwacja i wypożyczenie sprzętu)</li>
            <li>Art. 6 ust. 1 lit. c — obowiązek prawny (faktury, księgowość)</li>
            <li>Art. 6 ust. 1 lit. f — uzasadniony interes administratora (bezpieczeństwo systemu, dochodzenie roszczeń)</li>
        </ul>

        <h2>5. Okres przechowywania</h2>
        <ol>
            <li>Dane związane z rezerwacjami przechowujemy przez <strong>5 lat</strong> od zakończenia wypożyczenia (obowiązek podatkowy).</li>
            <li>Dane konta użytkownika przechowujemy do momentu jego usunięcia przez użytkownika.</li>
            <li>Logi systemowe usuwamy po <strong>12 miesiącach</strong>.</li>
        </ol>

        <h2>6. Odbiorcy danych</h2>
        <p>Dane osobowe możemy przekazywać następującym kategoriom odbiorców:</p>
        <ul>
            <li>Operatorzy płatności (obsługa transakcji online)</li>
            <li>Biuro księgowe (wystawianie faktur)</li>
            <li>Dostawcy usług IT (hosting, e-mail transakcyjny)</li>
            <li>Organy państwowe (na podstawie prawomocnych wezwań)</li>
        </ul>

        <div class="doc-rights-box">
            <strong>Twoje prawa</strong>
            Zgodnie z RODO masz prawo do dostępu do swoich danych, ich sprostowania, usunięcia,
            ograniczenia przetwarzania, przenoszenia oraz wniesienia sprzeciwu wobec przetwarzania.
            Możesz również cofnąć zgodę na przetwarzanie oraz złożyć skargę do Prezesa UODO.
        </div>

        <h2>7. Pliki cookies</h2>
        <p>
            Serwis korzysta z plików cookies w celach:
        </p>
        <ul>
            <li><strong>Niezbędne</strong> — utrzymanie sesji zalogowanego użytkownika, zapamiętanie koszyka rezerwacji.</li>
            <li><strong>Funkcjonalne</strong> — zapamiętanie preferencji widoku katalogu (grid / lista).</li>
            <li><strong>Analityczne</strong> — anonimowe statystyki odwiedzin (używamy Google Analytics).</li>
        </ul>
        <p>
            Możesz zablokować pliki cookies w ustawieniach swojej przeglądarki, ale może to wpłynąć
            na działanie serwisu (np. brak możliwości zalogowania).
        </p>

        <h2>8. Bezpieczeństwo danych</h2>
        <p>
            Stosujemy szyfrowanie SSL/TLS dla wszystkich transmisji danych. Hasła użytkowników przechowujemy
            w formie zahaszowanej (bcrypt) — nawet my nie znamy Twojego hasła w postaci jawnej.
        </p>
        <p>
            Dane kart płatniczych <strong>nigdy nie są przechowywane</strong> w naszym systemie —
            obsługuje je certyfikowany operator płatności zgodny ze standardem PCI DSS.
        </p>

        <h2>9. Zmiany polityki prywatności</h2>
        <p>
            O każdej zmianie polityki prywatności poinformujemy Cię drogą mailową z 14-dniowym wyprzedzeniem.
        </p>

        <h2>10. Kontakt</h2>
        <p>
            W sprawach związanych z ochroną danych osobowych możesz kontaktować się z nami:
        </p>
        <ul>
            <li>E-mail: <strong>rodo@equiprentpro.pl</strong></li>
            <li>Adres korespondencyjny: EquipRent Pro Sp. z o.o., ul. Sportowa 1, 00-001 Warszawa</li>
        </ul>

    </div>

</main>

@include('partials.footer')

</body>
</html>
