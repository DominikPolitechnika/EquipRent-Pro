<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset hasła</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-auth.css') }}">
</head>
<body class="auth-page">

<main class="auth-card">

    <section class="auth-hero">
        <div>
            <h2 class="brand">EquipRent Pro</h2>
            <p class="brand-subtitle">Reset hasła</p>
        </div>

        <div class="hero-content">
            <h2>Ustaw nowe hasło</h2>
            <p>Wprowadź nowe hasło dla swojego konta.</p>
        </div>
    </section>

    <section class="auth-form">

        <h1 class="title">Nowe hasło</h1>
        <p class="subtitle">Wprowadź i potwierdź nowe hasło.</p>

        <form id="reset-form">

            <label for="password">Nowe hasło</label>
            <div class="input-icon">
                <i class="fa-solid fa-lock input-left-icon"></i>
                <input type="password" id="password" placeholder="Wprowadź nowe hasło" required>
                <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
            </div>

            <label for="password_confirmation">Powtórz hasło</label>
            <div class="input-icon">
                <i class="fa-solid fa-lock input-left-icon"></i>
                <input type="password" id="password_confirmation" placeholder="Powtórz hasło" required>
                <i class="fa-solid fa-eye toggle-password" data-target="password_confirmation"></i>
            </div>

            
            <p id="password-message" class="password-message"></p>

            <button type="submit">Zmień hasło</button>

        </form>

        <a href="/login" class="login-button">Wróć do logowania</a>

    </section>

</main>

@include('partials.footer')

<script>
    const form = document.getElementById('reset-form');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const message = document.getElementById('password-message');

    passwordConfirmation.addEventListener('input', () => {
        message.textContent = '';
        message.className = 'password-message';

        if (passwordConfirmation.value.length === 0) return;

        if (password.value !== passwordConfirmation.value) {
            message.textContent = 'Hasła nie są identyczne.';
            message.classList.add('error');
        } else {
            message.textContent = 'Hasła są zgodne.';
            message.classList.add('ok');
        }
    });

    form.addEventListener('submit', (event) => {
        message.textContent = '';
        message.className = 'message';

        if (password.value !== passwordConfirmation.value) {
            event.preventDefault();
            message.textContent = 'Hasła nie są identyczne.';
            message.classList.add('error');
            return;
        }
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