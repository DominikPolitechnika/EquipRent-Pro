<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja profilu – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-profil.css') }}">
</head>
<body class="prof-page">
@include('partials.header')

{{-- ===== NAGŁÓWEK STRONY ===== --}}
<div class="prof-page-header">
    <div class="prof-page-header-text">
        <h1>Edycja Profilu</h1>
        <p>Zaktualizuj swoje dane osobowe i preferencje płatności.</p>
    </div>
    <div class="prof-header-actions">
        <a href="{{ route('profil') }}" class="prof-btn-secondary">Anuluj</a>
        <button type="submit" form="form-edycja-profilu" class="prof-btn-primary">Zapisz</button>
    </div>
</div>

<form id="form-edycja-profilu" method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="prof-edit-wrapper">

        {{-- ===== LEWA KOLUMNA ===== --}}
        <aside>
            <div class="prof-edit-avatar-card">
                <div class="prof-edit-avatar-title">Edytuj zdjęcie</div>
                <div class="prof-edit-avatar">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=face"
                         alt="Avatar użytkownika">
                </div>
                <div class="prof-edit-avatar-actions">
                    <label class="prof-icon-btn" title="Zmień zdjęcie">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        <input type="file" name="avatar" accept="image/*" hidden>
                    </label>
                    <button type="button" class="prof-icon-btn danger" title="Usuń zdjęcie">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="prof-edit-mini-stats">
                <div class="prof-edit-mini-stat">
                    <span class="prof-edit-mini-label">Ostatnie logowanie</span>
                    <span class="prof-edit-mini-value">Dzisiaj, 09:45</span>
                </div>
                <div class="prof-edit-mini-stat">
                    <span class="prof-edit-mini-label">Aktywne wynajmy</span>
                    <span class="prof-edit-mini-value">3</span>
                </div>
            </div>
        </aside>

        {{-- ===== PRAWA KOLUMNA ===== --}}
        <div class="prof-edit-content">

            {{-- Dane osobowe --}}
            <section class="prof-form-section">
                <div class="prof-form-title">
                    <div class="prof-form-title-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    Dane osobowe
                </div>

                <div class="prof-form-row">
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="imie">Imię</label>
                        <input type="text" id="imie" name="imie" class="prof-form-input" value="Jan" required>
                    </div>
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="nazwisko">Nazwisko</label>
                        <input type="text" id="nazwisko" name="nazwisko" class="prof-form-input" value="Kowalski" required>
                    </div>
                </div>

                <div class="prof-form-row single">
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="email">Adres e-mail</label>
                        <input type="email" id="email" name="email" class="prof-form-input" value="jan.kowalski@example.pl" required>
                    </div>
                </div>
            </section>

            {{-- Informacje sportowe --}}
            <section class="prof-form-section">
                <div class="prof-form-title">
                    <div class="prof-form-title-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M2 12h20"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    Informacje sportowe
                </div>

                <div class="prof-form-row single">
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="klub">Członkostwo w klubie sportowym</label>
                        <input type="text" id="klub" name="klub" class="prof-form-input" placeholder="Wpisz nazwę swojego klubu...">
                        <div class="prof-form-help">Podanie nazwy klubu może uprawniać do zniżek lojalnościowych.</div>
                    </div>
                </div>

                <div class="prof-form-row single">
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="opis">Opis profilu</label>
                        <textarea id="opis" name="opis" class="prof-form-textarea" placeholder="Krótko opisz siebie i swoje zainteresowania sportowe..."></textarea>
                    </div>
                </div>
            </section>

            {{-- Zmień hasło --}}
            <section class="prof-form-section">
                <div class="prof-form-title">
                    <div class="prof-form-title-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    Zmień hasło
                </div>

                <div class="prof-form-row single">
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="aktualne_haslo">Podaj aktualne hasło</label>
                        <input type="password" id="aktualne_haslo" name="aktualne_haslo" class="prof-form-input" placeholder="Wpisz aktualne hasło...">
                    </div>
                </div>

                <div class="prof-form-row">
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="nowe_haslo">Nowe hasło</label>
                        <input type="password" id="nowe_haslo" name="nowe_haslo" class="prof-form-input" placeholder="Nowe hasło...">
                    </div>
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="nowe_haslo_confirm">Powtórz nowe hasło</label>
                        <input type="password" id="nowe_haslo_confirm" name="nowe_haslo_confirmation" class="prof-form-input" placeholder="Nowe hasło (drugi raz)...">
                    </div>
                </div>
            </section>

            {{-- Zapisane metody płatności --}}
            <section class="prof-form-section">
                <div class="prof-form-title">
                    <div class="prof-form-title-icon-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                    </div>
                    Zapisane Metody Płatności
                </div>

                <div class="prof-edit-payment-grid">
                    <div class="prof-payment-card">
                        <div class="prof-payment-logo visa">VISA</div>
                        <div class="prof-payment-info">
                            <div class="prof-payment-number">•••• 4242</div>
                            <div class="prof-payment-expiry">Ważność 12/26</div>
                        </div>
                        <button type="button" class="prof-payment-delete" title="Usuń kartę">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>

                    <div class="prof-payment-card">
                        <div class="prof-payment-logo mc">MC</div>
                        <div class="prof-payment-info">
                            <div class="prof-payment-number">•••• 8890</div>
                            <div class="prof-payment-expiry">Ważność 05/25</div>
                        </div>
                        <button type="button" class="prof-payment-delete" title="Usuń kartę">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </section>

        </div>
    </div>
</form>

@include('partials.footer')
</body>
</html>