<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Odzyskiwanie hasła</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-auth.css') }}">
</head>
<body class="auth-page">
@php
    $savedEmail = session(\App\Http\Controllers\Auth\PasswordResetController::SESSION_EMAIL);
    $codeVerified = session(\App\Http\Controllers\Auth\PasswordResetController::SESSION_VERIFIED);
    $initialStep = $codeVerified ? 3 : ($savedEmail ? 2 : 1);
@endphp
<main class="auth-card">
    <section class="auth-hero">
        <div>
            <h2 class="brand">EquipRent Pro</h2>
            <p class="brand-subtitle">Reset hasła</p>
        </div>

        <div class="hero-content">
            <h2>Nie pamiętasz hasła?</h2>
            <p id="hero-text">
                @if($initialStep === 3)
                    Ustaw nowe hasło do swojego konta.
                @elseif($initialStep === 2)
                    Wprowadź kod resetujący.
                @else
                    Podaj adres e-mail powiązany z kontem.
                @endif
            </p>
        </div>
    </section>

    <section class="auth-form">
        <h1 class="title">Reset hasła</h1>
        <p class="subtitle" id="step-subtitle">
            @if($initialStep === 3)
                Krok 3 z 3 — nowe hasło
            @elseif($initialStep === 2)
                Krok 2 z 3 — kod weryfikacyjny
            @else
                Krok 1 z 3 — adres e-mail
            @endif
        </p>

        {{-- Krok 1: e-mail --}}
        <form id="step-email-form" @if($initialStep !== 1) hidden @endif>
            <label for="email">Adres e-mail</label>
            <div class="input-icon">
                <i class="fa-solid fa-envelope input-left-icon"></i>
                <input id="email" name="email" type="email" placeholder="Wprowadź adres email" value="{{ $savedEmail }}" required>
            </div>

            <button type="submit">Dalej</button>
        </form>

        {{-- Krok 2: kod --}}
        <form id="step-code-form" @if($initialStep !== 2) hidden @endif>
            <label for="code">Kod weryfikacyjny</label>
            <div class="input-icon">
                <i class="fa-solid fa-key input-left-icon"></i>
                <input id="code" name="code" type="text" inputmode="numeric" maxlength="4" placeholder="np. 1234" required>
            </div>
            <p class="subtitle" style="margin-top: -0.5rem; font-size: 0.85rem;">Na potrzeby testów kod to: <strong>1234</strong></p>

            <button type="submit">Zweryfikuj kod</button>
            <button type="button" class="login-button" id="back-to-email" style="margin-top: 0.75rem;">Zmień e-mail</button>
        </form>

        {{-- Krok 3: nowe hasło --}}
        <form id="step-password-form" @if($initialStep !== 3) hidden @endif>
            <label for="password">Nowe hasło</label>
            <div class="input-icon">
                <i class="fa-solid fa-lock input-left-icon"></i>
                <input id="password" name="password" type="password" placeholder="Wprowadź nowe hasło" required>
                <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
            </div>

            <label for="password_confirmation">Powtórz hasło</label>
            <div class="input-icon">
                <i class="fa-solid fa-lock input-left-icon"></i>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Powtórz hasło" required>
                <i class="fa-solid fa-eye toggle-password" data-target="password_confirmation"></i>
            </div>

            <p id="password-hint" class="password-message"></p>

            <button type="submit">Zmień hasło</button>
        </form>

        <p id="message" class="message"></p>

        <a href="/login" class="login-button">Wróć do logowania</a>
    </section>
</main>

@include('partials.footer')

<script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const message = document.getElementById('message');
    const heroText = document.getElementById('hero-text');
    const stepSubtitle = document.getElementById('step-subtitle');

    const stepEmailForm = document.getElementById('step-email-form');
    const stepCodeForm = document.getElementById('step-code-form');
    const stepPasswordForm = document.getElementById('step-password-form');

    const emailInput = document.getElementById('email');
    const codeInput = document.getElementById('code');
    const passwordInput = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const passwordHint = document.getElementById('password-hint');

    let currentEmail = @json($savedEmail ?? '');

    function showMessage(text, type = 'error') {
        message.textContent = text;
        message.className = 'message ' + type;
    }

    function clearMessage() {
        message.textContent = '';
        message.className = 'message';
    }

    function showStep(step) {
        stepEmailForm.hidden = step !== 1;
        stepCodeForm.hidden = step !== 2;
        stepPasswordForm.hidden = step !== 3;

        if (step === 1) {
            heroText.textContent = 'Podaj adres e-mail powiązany z kontem.';
            stepSubtitle.textContent = 'Krok 1 z 3 — adres e-mail';
        } else if (step === 2) {
            heroText.textContent = 'Wprowadź kod resetujący.';
            stepSubtitle.textContent = 'Krok 2 z 3 — kod weryfikacyjny';
        } else {
            heroText.textContent = 'Ustaw nowe hasło do swojego konta.';
            stepSubtitle.textContent = 'Krok 3 z 3 — nowe hasło';
        }
    }

    async function postJson(url, payload) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json().catch(() => ({}));

        if (response.ok) {
            return { ok: true, data };
        }

        const firstError = data?.errors
            ? Object.values(data.errors)[0]?.[0]
            : null;

        return {
            ok: false,
            error: firstError || data?.message || 'Wystąpił błąd. Spróbuj ponownie.',
        };
    }

    stepEmailForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearMessage();

        const result = await postJson('/forgot-password', {
            email: emailInput.value.trim(),
        });

        if (!result.ok) {
            showMessage(result.error);
            return;
        }

        currentEmail = emailInput.value.trim();
        showMessage(result.data.message || 'Konto znalezione.', 'ok');
        showStep(2);
        codeInput.focus();
    });

    document.getElementById('back-to-email').addEventListener('click', () => {
        clearMessage();
        showStep(1);
    });

    stepCodeForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearMessage();

        const result = await postJson('/forgot-password/verify-code', {
            email: currentEmail,
            code: codeInput.value.trim(),
        });

        if (!result.ok) {
            showMessage(result.error);
            return;
        }

        showMessage(result.data.message || 'Kod poprawny.', 'ok');
        showStep(3);
        passwordInput.focus();
    });

    passwordConfirmation.addEventListener('input', () => {
        passwordHint.textContent = '';
        passwordHint.className = 'password-message';

        if (!passwordConfirmation.value) {
            return;
        }

        if (passwordInput.value !== passwordConfirmation.value) {
            passwordHint.textContent = 'Hasła nie są identyczne.';
            passwordHint.classList.add('error');
        } else {
            passwordHint.textContent = 'Hasła są zgodne.';
            passwordHint.classList.add('ok');
        }
    });

    stepPasswordForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearMessage();

        if (passwordInput.value !== passwordConfirmation.value) {
            showMessage('Hasła nie są identyczne.');
            return;
        }

        const result = await postJson('/reset-password', {
            email: currentEmail,
            password: passwordInput.value,
            password_confirmation: passwordConfirmation.value,
        });

        if (!result.ok) {
            showMessage(result.error);
            return;
        }

        showMessage('Hasło zostało zmienione. Przekierowuję do logowania...', 'ok');
        setTimeout(() => {
            window.location.href = '/login';
        }, 1200);
    });

    document.querySelectorAll('.toggle-password').forEach((icon) => {
        icon.addEventListener('click', () => {
            const input = document.getElementById(icon.dataset.target);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });
</script>
</body>
</html>
