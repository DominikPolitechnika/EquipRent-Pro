<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rejestracja</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<main class="auth-card">
    <section class="auth-hero">
        <div class="hero-content register-hero-content">
            <h2>Wynajem sprzętu sportowego</h2>
            <p>Dołącz do EquipRent Pro.</p>

            <ul class="hero-list">
                <li><i class="fa-solid fa-gauge-high"></i> Błyskawiczny proces wynajmu</li>
                <li><i class="fa-solid fa-chart-line"></i> Pełna analityka kosztów</li>
                <li><i class="fa-solid fa-shield-halved"></i> Certyfikowana flota premium</li>
            </ul>
        </div>
    </section>

    <section class="auth-form">
        <h1 class="title">Załóż nowe konto</h1>
        <p class="subtitle">Uzupełnij poniższe dane, aby rozpocząć.</p>

        <form id="register-form">
            <label for="name">Imię i nazwisko</label>
            <div class="input-icon">
                <i class="fa-solid fa-user input-left-icon"></i>
                <input id="name" name="name" type="text" placeholder="Jan Kowalski" required>
            </div>

            <label for="email">Adres e-mail</label>
            <div class="input-icon">
                <i class="fa-solid fa-envelope input-left-icon"></i>
                <input id="email" name="email" type="email" placeholder="email@firma.pl" required>
            </div>

            <label for="club">Nazwa klubu sportowego</label>
            <div class="input-icon">
                <i class="fa-solid fa-building input-left-icon"></i>
                <input id="club" name="club" type="text" placeholder="Klub sportowy">
            </div>

            <label for="password">Hasło</label>
            <div class="input-icon">
                <i class="fa-solid fa-lock input-left-icon"></i>
                <input id="password" name="password" type="password" placeholder="••••••••" required>
                <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <label for="password_confirmation">Potwierdź hasło</label>
            <div class="input-icon">
                <i class="fa-solid fa-lock input-left-icon"></i>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" required>
                <i class="fa-solid fa-eye toggle-password" id="togglePasswordConfirmation"></i>
            </div>

            <button type="submit">Utwórz konto</button>
        </form>

        <p id="message" class="message"></p>

        <div class="divider">
            <span>Masz już konto?</span>
        </div>

        <a href="/login" class="login-button">Zaloguj się</a>
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
    <h1>Rejestracja</h1>

    <form id="register-form">
        <label for="name">Imię i nazwisko</label>
        <input id="name" name="name" type="text" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required>

        <label for="password">Hasło</label>
        <input id="password" name="password" type="password" required>

        <label for="password_confirmation">Powtórz hasło</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required>

        <button type="submit">Utwórz konto</button>
    </form>

    <p id="message" class="message"></p>
    <p class="hint">Masz już konto? <a href="/login">Zaloguj się</a></p>
>>>>>>> a1b716982b2211082a5786c1d96f65a75d72942b
</main>

<script>
    const form = document.getElementById('register-form');
    const message = document.getElementById('message');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        message.textContent = '';
        message.className = 'message';

        const payload = {
            name: form.name.value,
            email: form.email.value,
<<<<<<< HEAD
            club: form.club.value,
=======
>>>>>>> a1b716982b2211082a5786c1d96f65a75d72942b
            password: form.password.value,
            password_confirmation: form.password_confirmation.value,
        };

        try {
            const response = await fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload),
            });

            if (response.ok) {
                message.textContent = 'Konto utworzone. Przekierowuję...';
                message.classList.add('ok');
                window.location.href = '/';
                return;
            }

            const data = await response.json().catch(() => ({}));
            const firstError = data?.errors ? Object.values(data.errors)[0]?.[0] : null;
            message.textContent = firstError || data?.message || 'Nie udało się zarejestrować.';
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

    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const passwordConfirmationInput = document.getElementById('password_confirmation');

    togglePasswordConfirmation.addEventListener('click', () => {
        const isPassword = passwordConfirmationInput.type === 'password';

        passwordConfirmationInput.type = isPassword ? 'text' : 'password';

        togglePasswordConfirmation.classList.toggle('fa-eye');
        togglePasswordConfirmation.classList.toggle('fa-eye-slash');
    });
=======
>>>>>>> a1b716982b2211082a5786c1d96f65a75d72942b
</script>
</body>
</html>
