<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edycja profilu – EquipRent Pro</title>
    <link rel="stylesheet" href="{{ asset('style-profil.css') }}">
    <style>
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.45; } }
        .placeholder { background: #e2e8f0; display: inline-block; border-radius: 3px; }
        .placeholder-circle { background: #e2e8f0; border-radius: 50%; display: block; }
        .animate-pulse { animation: pulse 1.6s ease-in-out infinite; }
        .prof-alert { max-width: 1100px; margin: 0 auto 1.25rem; padding: 0.9rem 1.1rem; border-radius: 10px; font-size: 0.92rem; }
        .prof-alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .prof-alert-error ul { margin: 0; padding-left: 1.1rem; }
        .prof-alert-error li + li { margin-top: 0.25rem; }
        .prof-form-input.is-invalid { border-color: #ef4444; }
        .prof-field-error { margin-top: 0.35rem; font-size: 0.82rem; color: #b91c1c; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('E.png') }}">
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

@if ($errors->any())
    <div class="prof-alert prof-alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="form-edycja-profilu" method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">

    <div class="prof-edit-wrapper">

        {{-- ===== LEWA KOLUMNA ===== --}}
        <aside>
            <div class="prof-edit-avatar-card">
                <div class="prof-edit-avatar-title">Edytuj zdjęcie</div>
                <div class="prof-edit-avatar prof-avatar-icon" id="user-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="prof-edit-avatar-actions">
                    <label class="prof-icon-btn" title="Zmień zdjęcie">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        <input type="file" id="avatar-input" name="avatar" accept="image/*" hidden>
                    </label>
                    <button type="button" id="avatar-remove-btn" class="prof-icon-btn danger" title="Usuń zdjęcie">
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
                    <span class="prof-edit-mini-value" id="last-login-value">
                        <span class="placeholder animate-pulse" style="width:110px;height:14px;"></span>
                    </span>
                </div>
                <div class="prof-edit-mini-stat">
                    <span class="prof-edit-mini-label">Aktywne wynajmy</span>
                    <span class="prof-edit-mini-value" id="active-rentals-value">
                        <span class="placeholder animate-pulse" style="width:30px;height:14px;"></span>
                    </span>
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
                        <input type="text" id="imie" name="imie" class="prof-form-input @error('imie') is-invalid @enderror" value="{{ old('imie') }}" placeholder="Wpisz imię..." required>
                        @error('imie')<div class="prof-field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="nazwisko">Nazwisko</label>
                        <input type="text" id="nazwisko" name="nazwisko" class="prof-form-input @error('nazwisko') is-invalid @enderror" value="{{ old('nazwisko') }}" placeholder="Wpisz nazwisko..." required>
                        @error('nazwisko')<div class="prof-field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="prof-form-row single">
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="email">Adres e-mail</label>
                        <input type="email" id="email" name="email" class="prof-form-input @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="np. jan.kowalski@example.pl" required>
                        @error('email')<div class="prof-field-error">{{ $message }}</div>@enderror
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
                        <input type="text" id="klub" name="klub" class="prof-form-input @error('klub') is-invalid @enderror" value="{{ old('klub') }}" placeholder="Wpisz nazwę swojego klubu...">
                        @error('klub')<div class="prof-field-error">{{ $message }}</div>@enderror
                        <div class="prof-form-help">Podanie nazwy klubu może uprawniać do zniżek lojalnościowych.</div>
                    </div>
                </div>

                <div class="prof-form-row single">
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="opis">Opis profilu</label>
                        <textarea id="opis" name="opis" class="prof-form-textarea @error('opis') is-invalid @enderror" placeholder="Krótko opisz siebie i swoje zainteresowania sportowe...">{{ old('opis') }}</textarea>
                        @error('opis')<div class="prof-field-error">{{ $message }}</div>@enderror
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
                        <input type="password" id="aktualne_haslo" name="aktualne_haslo" class="prof-form-input @error('aktualne_haslo') is-invalid @enderror" placeholder="Wpisz aktualne hasło...">
                        @error('aktualne_haslo')<div class="prof-field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="prof-form-row">
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="nowe_haslo">Nowe hasło</label>
                        <input type="password" id="nowe_haslo" name="nowe_haslo" class="prof-form-input @error('nowe_haslo') is-invalid @enderror" placeholder="Nowe hasło...">
                        @error('nowe_haslo')<div class="prof-field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="prof-form-group">
                        <label class="prof-form-label" for="nowe_haslo_confirm">Powtórz nowe hasło</label>
                        <input type="password" id="nowe_haslo_confirm" name="nowe_haslo_confirmation" class="prof-form-input @error('nowe_haslo_confirmation') is-invalid @enderror" placeholder="Nowe hasło (drugi raz)...">
                        @error('nowe_haslo_confirmation')<div class="prof-field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>
            
            <!--
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
                        <div class="prof-payment-logo visa">
                            <span class="placeholder animate-pulse" style="width:38px;height:14px;background:#ffffff55;"></span>
                        </div>
                        <div class="prof-payment-info">
                            <div class="prof-payment-number">
                                <span class="placeholder animate-pulse" style="width:120px;height:16px;"></span>
                            </div>
                            <div class="prof-payment-expiry">
                                <span class="placeholder animate-pulse" style="width:90px;height:12px;"></span>
                            </div>
                        </div>
                        <button type="button" class="prof-payment-delete" title="Usuń kartę">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>

                    <div class="prof-payment-card">
                        <div class="prof-payment-logo mc">
                            <span class="placeholder animate-pulse" style="width:28px;height:14px;background:#ffffff55;"></span>
                        </div>
                        <div class="prof-payment-info">
                            <div class="prof-payment-number">
                                <span class="placeholder animate-pulse" style="width:120px;height:16px;"></span>
                            </div>
                            <div class="prof-payment-expiry">
                                <span class="placeholder animate-pulse" style="width:90px;height:12px;"></span>
                            </div>
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
-->
        </div>
    </div>
</form>

@include('partials.footer')

<script>
(function () {
    const avatarIconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';

    const fields = {
        imie: 'name',
        nazwisko: 'surname',
        email: 'email',
        klub: 'klub',
        opis: 'profilDescription',
    };

    function renderAvatar(container, avatarUrl) {
        if (!container) {
            return;
        }

        container.innerHTML = '';

        if (avatarUrl) {
            container.classList.remove('prof-avatar-icon');
            const img = document.createElement('img');
            img.src = avatarUrl;
            img.alt = 'Zdjęcie profilowe';
            container.appendChild(img);
            return;
        }

        container.classList.add('prof-avatar-icon');
        container.innerHTML = avatarIconSvg;
    }

    function formatDate(dateStr) {
        if (!dateStr) {
            return '—';
        }

        return new Date(dateStr).toLocaleDateString('pl-PL', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    }

    function setText(id, value) {
        const el = document.getElementById(id);
        if (!el) {
            return;
        }

        el.textContent = value;
    }

    const hasValidationErrors = @json($errors->any());

    document.addEventListener('DOMContentLoaded', async function () {
        if (!hasValidationErrors) {
            try {
                const response = await fetch('/api/user/profile', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error('Nie udało się pobrać danych profilu.');
                }

                const json = await response.json();
                const user = json.data;

                Object.entries(fields).forEach(function ([elementId, dataKey]) {
                    const input = document.getElementById(elementId);
                    if (input) {
                        input.value = user[dataKey] ?? '';
                    }
                });

                renderAvatar(document.getElementById('user-avatar'), user.avatarUrl);
                setText('last-login-value', formatDate(user.lastLogin));
                setText('active-rentals-value', String(user.activeRentalsCount ?? 0));
            } catch (error) {
                console.error(error);
            }
        }

        const avatarInput = document.getElementById('avatar-input');
        const avatarRemoveBtn = document.getElementById('avatar-remove-btn');
        const removeAvatarField = document.getElementById('remove_avatar');
        const avatarContainer = document.getElementById('user-avatar');

        if (avatarInput) {
            avatarInput.addEventListener('change', function () {
                const file = avatarInput.files[0];

                if (!file) {
                    return;
                }

                if (removeAvatarField) {
                    removeAvatarField.value = '0';
                }

                renderAvatar(avatarContainer, URL.createObjectURL(file));
            });
        }

        if (avatarRemoveBtn) {
            avatarRemoveBtn.addEventListener('click', function () {
                if (avatarInput) {
                    avatarInput.value = '';
                }

                if (removeAvatarField) {
                    removeAvatarField.value = '1';
                }

                renderAvatar(avatarContainer, null);
            });
        }
    });
})();
</script>
</body>
</html>