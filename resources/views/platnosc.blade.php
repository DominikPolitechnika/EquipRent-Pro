<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finalizacja Wynajmu – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-head.css') }}">
    <link rel="stylesheet" href="{{ asset('style-foot.css') }}">
    <link rel="stylesheet" href="{{ asset('style-platnosc.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('E.png') }}">
</head>
<body class="pay-page">

@include('partials.header')

<main class="pay-wrapper">

    {{-- Powrót + tytuł --}}
    <a href="{{ route('catalog') }}" class="pay-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        Powrót do inwentarza
    </a>
    <h1 class="pay-title">Finalizacja Wynajmu</h1>

    <div class="pay-grid">

        {{-- ===== LEWA KOLUMNA - FORMULARZ ===== --}}
        <div class="pay-card">
            <div class="pay-card-header">
                <span class="pay-card-title">Metoda Płatności</span>
                <span class="pay-ssl">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Bezpieczne połączenie SSL
                </span>
            </div>

            <form id="pay-form" onsubmit="event.preventDefault(); /* TODO: integracja z bramką płatności */">

                <div class="pay-form-group">
                    <label class="pay-label" for="pay-name">Imię i nazwisko posiadacza karty</label>
                    <input type="text" id="pay-name" class="pay-input" placeholder="Jan Kowalski" autocomplete="cc-name">
                </div>

                <div class="pay-form-group">
                    <label class="pay-label" for="pay-number">Dane karty</label>
                    <div class="pay-input-with-icon">
                        <span class="pay-input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                <line x1="1" y1="10" x2="23" y2="10"/>
                            </svg>
                        </span>
                        <input type="text" id="pay-number" class="pay-input" placeholder="0000 0000 0000 0000" inputmode="numeric" maxlength="19" autocomplete="cc-number">
                    </div>
                </div>

                <div class="pay-form-row">
                    <div class="pay-form-group" style="margin-bottom:0;">
                        <label class="pay-label" for="pay-expiry">Data ważności</label>
                        <input type="text" id="pay-expiry" class="pay-input" placeholder="MM / RR" inputmode="numeric" maxlength="7" autocomplete="cc-exp">
                    </div>
                    <div class="pay-form-group" style="margin-bottom:0;">
                        <label class="pay-label" for="pay-cvc">Kod CVC</label>
                        <input type="text" id="pay-cvc" class="pay-input" placeholder="123" inputmode="numeric" maxlength="4" autocomplete="cc-csc">
                    </div>
                </div>

                <button type="submit" class="pay-submit">
                    Zapłać 345,00 PLN teraz
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </form>

            <div class="pay-card-footer">
                <div class="pay-pci-logo"></div>
                <span class="pay-pci-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Zgodność PCI
                </span>
            </div>
        </div>

        {{-- ===== PRAWA KOLUMNA - PODSUMOWANIE ===== --}}
        <div>
            <div class="pay-summary">

                {{-- Hero z obrazkiem produktu --}}
                <div class="pay-hero">
                    <div class="pay-hero-overlay"></div>
                    <span class="pay-hero-cat">Sprzęt sportowy</span>
                    <div class="pay-hero-title">Wynajem Roweru MTB</div>
                </div>

                {{-- Daty --}}
                <div class="pay-dates">
                    <div class="pay-date-col">
                        <div class="pay-date-label">Odbiór</div>
                        <div class="pay-date-value">24 paź 2023</div>
                    </div>
                    <div class="pay-date-arrow">→</div>
                    <div class="pay-date-col end">
                        <div class="pay-date-label">Zwrot</div>
                        <div class="pay-date-value">31 paź 2023</div>
                    </div>
                </div>

                {{-- Pozycje rachunku --}}
                <div class="pay-items">
                    <div class="pay-item">
                        <span class="pay-item-label">Wynajem <small>(2 dni)</small></span>
                        <span class="pay-item-amount">240,00 PLN</span>
                    </div>
                    <div class="pay-item">
                        <span class="pay-item-label">Wynajem <small>(2 dni) 60,00 PLN</small></span>
                        <span class="pay-item-amount">60,00 PLN</span>
                    </div>
                    <div class="pay-item">
                        <span class="pay-item-label">Serwis i przygotowanie</span>
                        <span class="pay-item-amount">45,00 PLN</span>
                    </div>
                </div>

                {{-- Suma --}}
                <div class="pay-total">
                    <span class="pay-total-label">Wynajem <small style="color:#9aa5ad;font-weight:400;">(2 dni)</small></span>
                    <span class="pay-total-amount">240,00 PLN</span>
                </div>

                {{-- Timer --}}
                <div class="pay-timer">
                    <span class="pay-timer-dot"></span>
                    Sprzęt zarezerwowany przez <span id="pay-timer-text">14:59</span> minut
                </div>
            </div>

            {{-- Pomoc --}}
            <div class="pay-help">
                <div class="pay-help-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                </div>
                <div>
                    <div class="pay-help-title">Potrzebujesz pomocy z rezerwacją?</div>
                    <div class="pay-help-text">Nasz zespół logistyczny jest dostępny 24/7, aby pomóc w transporcie i specyfikacji technicznej.</div>
                </div>
            </div>
        </div>

    </div>
</main>

@include('partials.footer')

<script>
(function() {
    // ===== Formatowanie numeru karty =====
    const numberInput = document.getElementById('pay-number');
    if (numberInput) {
        numberInput.addEventListener('input', (e) => {
            // tylko cyfry, grupy po 4 oddzielone spacją
            const digits = e.target.value.replace(/\D/g, '').slice(0, 16);
            e.target.value = digits.replace(/(\d{4})(?=\d)/g, '$1 ');
        });
    }

    // ===== Formatowanie daty MM / RR =====
    const expiryInput = document.getElementById('pay-expiry');
    if (expiryInput) {
        expiryInput.addEventListener('input', (e) => {
            const digits = e.target.value.replace(/\D/g, '').slice(0, 4);
            if (digits.length >= 3) {
                e.target.value = digits.slice(0, 2) + ' / ' + digits.slice(2);
            } else {
                e.target.value = digits;
            }
        });
    }

    // ===== Tylko cyfry w CVC =====
    const cvcInput = document.getElementById('pay-cvc');
    if (cvcInput) {
        cvcInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
        });
    }

    // ===== Odliczanie timera 15 min =====
    const timerEl = document.getElementById('pay-timer-text');
    if (timerEl) {
        let seconds = 15 * 60 - 1; // start od 14:59
        const tick = () => {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            timerEl.textContent = m + ':' + (s < 10 ? '0' + s : s);
            if (seconds > 0) seconds--;
        };
        tick();
        setInterval(tick, 1000);
    }
})();
</script>

</body>
</html>