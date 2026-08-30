<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Regulamin — EquipRent Pro</title>
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

        @media (max-width: 700px) {
            .doc-page { padding: 20px 20px 60px; }
            .doc-body { padding: 24px 22px; }
            .doc-header h1 { font-size: 26px; }
        }
    </style>
</head>
<body>

@include('partials.header')

<main class="doc-page">

    <div class="doc-breadcrumb">
        <a href="{{ url('/catalog') }}">Strona główna</a> › <span>Regulamin</span>
    </div>

    <div class="doc-header">
        <h1>Regulamin</h1>
        <div class="doc-updated">Ostatnia aktualizacja: 15.08.2026</div>
    </div>

    <div class="doc-body">

        <h2>§1. Postanowienia ogólne</h2>
        <p>
            Niniejszy regulamin określa zasady korzystania z usług wypożyczalni sprzętu sportowego
            <strong>EquipRent Pro</strong>, dostępnej pod adresem equiprentpro.pl.
        </p>
        <p>
            Dokonanie rezerwacji sprzętu jest równoznaczne z akceptacją regulaminu.
        </p>

        <h2>§2. Rejestracja i konto użytkownika</h2>
        <ol>
            <li>Aby wypożyczyć sprzęt, użytkownik musi utworzyć konto z prawidłowym adresem e-mail.</li>
            <li>Użytkownik zobowiązuje się do podania prawdziwych danych osobowych.</li>
            <li>Jedno konto może być powiązane wyłącznie z jednym adresem e-mail.</li>
            <li>Użytkownik odpowiada za bezpieczeństwo hasła do swojego konta.</li>
        </ol>

        <h2>§3. Zasady wypożyczenia</h2>
        <ol>
            <li>Minimalny okres wypożyczenia to <strong>1 dzień</strong>, maksymalny <strong>30 dni</strong>.</li>
            <li>Rezerwacja obowiązuje po opłaceniu pełnej kwoty widocznej w podsumowaniu.</li>
            <li>Dostępność sprzętu w wybranym terminie jest weryfikowana w momencie potwierdzenia rezerwacji.</li>
            <li>Odbiór i zwrot sprzętu odbywa się osobiście w siedzibie wypożyczalni.</li>
            <li>Do wypożyczenia niezbędny jest ważny dokument tożsamości ze zdjęciem.</li>
        </ol>

        <h2>§4. Opłaty i płatności</h2>
        <ol>
            <li>Cena za wypożyczenie jest naliczana za każdą rozpoczętą dobę.</li>
            <li>Do ceny wypożyczenia doliczana jest opłata serwisowa oraz opłata logistyczna.</li>
            <li>Płatność odbywa się online kartą kredytową lub przelewem online.</li>
            <li>Faktura VAT wystawiana jest na żądanie klienta w ciągu 7 dni od zakończenia wypożyczenia.</li>
        </ol>

        <h2>§5. Anulowanie i zwroty</h2>
        <ol>
            <li>
                <strong>Do 48h przed odbiorem</strong> — anulowanie bezpłatne, zwrot pełnej kwoty w ciągu 7 dni.
            </li>
            <li>
                <strong>Od 48h do 24h przed odbiorem</strong> — zwrot 50% wartości.
            </li>
            <li>
                <strong>Poniżej 24h lub brak odbioru</strong> — brak zwrotu.
            </li>
            <li>
                Anulowania dokonuje się z poziomu panelu użytkownika w sekcji "Moje rezerwacje".
            </li>
        </ol>

        <h2>§6. Odpowiedzialność użytkownika</h2>
        <ol>
            <li>Użytkownik zobowiązuje się do użytkowania sprzętu zgodnie z jego przeznaczeniem.</li>
            <li>Za zniszczenia, utratę lub kradzież sprzętu użytkownik ponosi pełną odpowiedzialność finansową.</li>
            <li>W przypadku nietypowego zużycia lub uszkodzenia sprzętu wypożyczalnia może pobrać dodatkową opłatę.</li>
            <li>Sprzęt należy zwrócić w stanie nie gorszym niż w momencie wypożyczenia (z uwzględnieniem naturalnego zużycia).</li>
        </ol>

        <h2>§7. Opóźnienia w zwrocie</h2>
        <ol>
            <li>Za każdy dzień opóźnienia zwrotu naliczana jest opłata w wysokości <strong>150%</strong> stawki dziennej.</li>
            <li>Po 7 dniach opóźnienia sprawa może zostać skierowana do windykacji.</li>
        </ol>

        <h2>§8. Opinie</h2>
        <ol>
            <li>Użytkownik może dodać opinię o wypożyczonym sprzęcie po zakończeniu wypożyczenia.</li>
            <li>Opinie zawierające treści obraźliwe, nieprawdziwe lub niezwiązane z produktem mogą zostać usunięte.</li>
            <li>Każdy użytkownik może dodać maksymalnie jedną opinię na dany produkt.</li>
        </ol>

        <h2>§9. Reklamacje</h2>
        <p>
            Reklamacje należy zgłaszać na adres <strong>reklamacje@equiprentpro.pl</strong> w ciągu 14 dni od
            zakończenia wypożyczenia. Odpowiedź na reklamację zostanie udzielona w ciągu 14 dni roboczych.
        </p>

        <h2>§10. Postanowienia końcowe</h2>
        <ol>
            <li>Wypożyczalnia zastrzega sobie prawo do zmiany regulaminu z zachowaniem 14-dniowego okresu vacatio legis.</li>
            <li>W sprawach nieuregulowanych zastosowanie mają przepisy Kodeksu Cywilnego.</li>
            <li>Wszelkie spory rozstrzygane są przez sąd właściwy dla siedziby wypożyczalni.</li>
        </ol>

    </div>

</main>

@include('partials.footer')

</body>
</html>
