<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Logowanie</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
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
                <a href="/forgot-password" class="forgot">Zapomniałeś hasła?</a>
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

        <p id="message" class="message"></p>

        <div class="divider">
            <span>Nie masz jeszcze konta?</span>
        </div>

        <a href="/register" class="register-button">Zarejestruj się</a>
    </section>
=======
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f8; margin: 0; padding: 24px; }
        .card { max-width: 420px; margin: 40px auto; background: #fff; border-radius: 8px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        h1 { margin-top: 0; font-size: 1.4rem; }
        label { display: block; margin-top: 12px; font-weight: 600; }
        input { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #cfcfd4; border-radius: 6px; box-sizing: border-box; }
        button { margin-top: 16px; width: 100%; border: 0; background: #1f6feb; color: #fff; padding: 10px 14px; border-radius: 6px; cursor: pointer; }
        .hint { font-size: 0.9rem; color: #666; margin-top: 14px; }
        .message { margin-top: 14px; font-size: 0.95rem; }
        .error { color: #b42318; }
        .ok { color: #067647; }
    </style>
</head>
<body>
<main class="card">
    <h1>Logowanie</h1>

    <form id="login-form">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" required>

        <label for="password">Hasło</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Zaloguj</button>
    </form>

    <p id="message" class="message"></p>
    <p class="hint">Nie masz konta? <a href="/register">Zarejestruj się</a></p>
>>>>>>> a1b716982b2211082a5786c1d96f65a75d72942b
</main>

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
<<<<<<< HEAD
            remember: form.remember.checked,
=======
>>>>>>> a1b716982b2211082a5786c1d96f65a75d72942b
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
<<<<<<< HEAD

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';

        togglePassword.classList.toggle('fa-eye');
        togglePassword.classList.toggle('fa-eye-slash');
    });
=======
>>>>>>> a1b716982b2211082a5786c1d96f65a75d72942b
</script>
</body>
</html>
