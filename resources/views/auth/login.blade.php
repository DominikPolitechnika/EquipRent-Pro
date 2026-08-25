<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Logowanie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-auth.css') }}">
    <style>
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 16px;
        }

        .modal-overlay[hidden] {
            display: none;
        }

        .modal-box {
            position: relative;
            width: 100%;
            max-width: 380px;
            background: #fff;
            border-radius: 8px;
            padding: 28px 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-close {
            position: absolute;
            top: 12px;
            right: 14px;
            width: auto;
            margin: 0;
            padding: 0;
            border: 0;
            background: none;
            color: #9aa5ad;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
        }

        .modal-close:hover {
            background: none;
            color: #075071;
        }

        #pwreset-back {
            display: block;
            margin-top: 12px;
            color: #075071;
            font-size: 12px;
            text-align: center;
            text-decoration: none;
        }

        #pwreset-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="auth-page">
<main class="auth-card">
    <section class="auth-hero">
        <div>
            <h2 class="brand">EquipRent Pro</h2>
            <p class="brand-subtitle">Ewidencja sprzętu</p>
        </div>

        <div class="hero-content">
            <h2>Precyzyjne zarządzaniem sprzętem sportowym.</h2>
            <p>Rok założenia 2026</p>
        </div>
    </section>

    <section class="auth-form">
        <h1 class="title">Witaj ponownie</h1>
        <p class="subtitle">Wprowadź swoje dane.</p>

        <form id="login-form">
            <label for="email">Email</label>
            <div class="input-icon">
                <i class="fa-solid fa-envelope input-left-icon"></i>
                <input id="email" name="email" type="email" placeholder="Wprowadź adres email" required>
            </div>

            <div class="password-header">
                <label for="password">Hasło</label>
                <a href="#" class="forgot" id="forgot-password-link">Zapomniałeś hasła?</a>
            </div>

            <div class="input-icon">
                <i class="fa-solid fa-lock input-left-icon"></i>
                <input id="password" name="password" type="password" placeholder="Wprowadź hasło" required>
                <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <label class="remember">
                <input type="checkbox" name="remember">Zapamiętaj mnie
            </label>

            <button type="submit">Zaloguj</button>
        </form>

        @if (session('success'))
            <p id="message" class="message ok">{{ session('success') }}</p>
        @else
            <p id="message" class="message"></p>
        @endif

        <div class="divider">
            <span>Nie masz jeszcze konta?</span>
        </div>

        <a href="/register" class="register-button">Zarejestruj się</a>
    </section>
</main>

@include('partials.footer')

{{-- Modal resetu hasła (zaślepka: kod weryfikacyjny to zawsze 1234) --}}
<div class="modal-overlay" id="pwreset-overlay" hidden>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="pwreset-title">
        <button type="button" class="modal-close" id="pwreset-close" aria-label="Zamknij">&times;</button>

        <div id="pwreset-step-email">
            <h2 id="pwreset-title" class="title">Reset hasła</h2>
            <p class="subtitle">Podaj adres e-mail, a wyślemy kod weryfikacyjny.</p>

            <form id="pwreset-email-form">
                <label for="pwreset-email">Email</label>
                <div class="input-icon">
                    <i class="fa-solid fa-envelope input-left-icon"></i>
                    <input id="pwreset-email" name="email" type="email" placeholder="Wprowadź adres email" required>
                </div>
                <p id="pwreset-email-message" class="message"></p>
                <button type="submit">Wyślij kod</button>
            </form>
        </div>

        <div id="pwreset-step-code" hidden>
            <h2 class="title">Wprowadź kod</h2>
            <p class="subtitle" id="pwreset-code-subtitle">Kod wysłany na podany adres e-mail.</p>

            <form id="pwreset-code-form">
                <label for="pwreset-code">Kod weryfikacyjny</label>
                <div class="input-icon">
                    <i class="fa-solid fa-key input-left-icon"></i>
                    <input id="pwreset-code" name="code" type="text" inputmode="numeric" placeholder="Wprowadź kod" required>
                </div>

                <label for="pwreset-password">Nowe hasło</label>
                <div class="input-icon">
                    <i class="fa-solid fa-lock input-left-icon"></i>
                    <input id="pwreset-password" name="password" type="password" placeholder="Wprowadź nowe hasło" required>
                </div>

                <label for="pwreset-password-confirmation">Powtórz hasło</label>
                <div class="input-icon">
                    <i class="fa-solid fa-lock input-left-icon"></i>
                    <input id="pwreset-password-confirmation" name="password_confirmation" type="password" placeholder="Powtórz nowe hasło" required>
                </div>

                <p id="pwreset-code-message" class="message"></p>
                <button type="submit">Zmień hasło</button>
            </form>

            <a href="#" id="pwreset-back">Wróć</a>
        </div>
    </div>
</div>

<script>
    (function () {
        const overlay = document.getElementById('pwreset-overlay');
        const openLink = document.getElementById('forgot-password-link');
        const closeBtn = document.getElementById('pwreset-close');
        const backLink = document.getElementById('pwreset-back');
        const stepEmail = document.getElementById('pwreset-step-email');
        const stepCode = document.getElementById('pwreset-step-code');
        const emailForm = document.getElementById('pwreset-email-form');
        const codeForm = document.getElementById('pwreset-code-form');
        const emailMessage = document.getElementById('pwreset-email-message');
        const codeMessage = document.getElementById('pwreset-code-message');
        const codeSubtitle = document.getElementById('pwreset-code-subtitle');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        let resetEmail = '';

        function showStepEmail() {
            stepEmail.hidden = false;
            stepCode.hidden = true;
            emailMessage.textContent = '';
            emailMessage.className = 'message';
            codeMessage.textContent = '';
            codeMessage.className = 'message';
            emailForm.reset();
            codeForm.reset();
        }

        function openModal() {
            overlay.hidden = false;
            showStepEmail();
        }

        function closeModal() {
            overlay.hidden = true;
        }

        openLink.addEventListener('click', (event) => {
            event.preventDefault();
            openModal();
        });

        closeBtn.addEventListener('click', closeModal);

        overlay.addEventListener('click', (event) => {
            if (event.target === overlay) closeModal();
        });

        backLink.addEventListener('click', (event) => {
            event.preventDefault();
            showStepEmail();
        });

        emailForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            emailMessage.textContent = '';
            emailMessage.className = 'message';

            resetEmail = emailForm.email.value;

            try {
                const response = await fetch('/password-reset-mock/send-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ email: resetEmail }),
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    const firstError = data?.errors ? Object.values(data.errors)[0]?.[0] : null;
                    emailMessage.textContent = firstError || data?.message || 'Nie udało się wysłać kodu.';
                    emailMessage.classList.add('error');
                    return;
                }

                codeSubtitle.textContent = `Kod wysłany na adres ${resetEmail}.`;
                stepEmail.hidden = true;
                stepCode.hidden = false;
            } catch (error) {
                emailMessage.textContent = 'Błąd połączenia z serwerem.';
                emailMessage.classList.add('error');
            }
        });

        codeForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            codeMessage.textContent = '';
            codeMessage.className = 'message';

            const payload = {
                email: resetEmail,
                code: codeForm.code.value,
                password: codeForm.password.value,
                password_confirmation: codeForm.password_confirmation.value,
            };

            try {
                const response = await fetch('/password-reset-mock/confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    const firstError = data?.errors ? Object.values(data.errors)[0]?.[0] : null;
                    codeMessage.textContent = firstError || data?.message || 'Nie udało się zmienić hasła.';
                    codeMessage.classList.add('error');
                    return;
                }

                codeMessage.textContent = data.message || 'Hasło zostało zmienione.';
                codeMessage.classList.add('ok');

                setTimeout(closeModal, 1500);
            } catch (error) {
                codeMessage.textContent = 'Błąd połączenia z serwerem.';
                codeMessage.classList.add('error');
            }
        });
    })();
</script>

<script>
    const form = document.getElementById('login-form');
    const message = document.getElementById('message');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        message.textContent = '';
        message.className = 'message';

        const payload = {
            email: form.email.value,
            password: form.password.value,
            remember: form.remember.checked,
        };

        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload),
            });

            if (response.ok) {
                message.textContent = 'Zalogowano poprawnie. Przekierowuję...';
                message.classList.add('ok');
                window.location.href = '/';
                return;
            }

            const data = await response.json().catch(() => ({}));
            const firstError = data?.errors ? Object.values(data.errors)[0]?.[0] : null;
            message.textContent = firstError || data?.message || 'Nie udało się zalogować.';
            message.classList.add('error');
        } catch (error) {
            message.textContent = 'Błąd połączenia z serwerem.';
            message.classList.add('error');
        }
    });

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';

        togglePassword.classList.toggle('fa-eye');
        togglePassword.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
