<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-auth.css') }}">
</head>
<body class="auth-page">
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

            <label for="role">Rola użytkownika</label>
            <div class="input-icon">
                <i class="fa-solid fa-user-tag input-left-icon"></i>

                <select id="role" name="role" class="role-select" required>
                    <option value="" disabled selected>Wybierz rolę</option>
                    <option value="user">Zawodnik</option>
                    <option value="admin">Trener</option>
                </select>
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
</main>

@include('partials.footer')

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
            club: form.club.value,
            role: form.role.value,
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
</script>
</body>
</html>
