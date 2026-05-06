<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odzyskiwanie hasła</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-auth.css') }}">
</head>
<body>
    <main class="auth-card">
        <section class="auth-hero">
            <div>
                <h2 class="brand">EquipRent Pro</h2>
                <p class="brand-subtitle">Reset hasła</p>
            </div>

            <div class="hero-content">
                <h2>Nie pamiętasz hasła?</h2>
                <p>Podaj email, a wyślemy link resetujący.</p>
            </div>
        </section>

        <section class="auth-form">
            <h1 class="title">Reset hasła</h1>
            <p class="subtitle">Wprowadź swój adres email.</p>

            <form>
                <lable class="email">Email</lable>
                <div class="input-icon">
                    <i class="fa-solid fa-envelope input-left-icon"></i>
                    <input type="email" id="email" placeholder="Wprowadź adres email" required>
                </div>

                <button type="submit">Wyślij link resetujący</button>
            </form>

            <a href="/login" class="login-button">Wróć do logowania</a>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>