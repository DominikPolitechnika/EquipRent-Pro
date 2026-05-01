<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo: skladanie widoku z partiali</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f3f4f6; color: #111827; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 16px; }
        .surface { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 10px; margin: 16px 0; padding: 16px; }
    </style>
</head>
<body>
    @include('partials.header')
    @include('partials.body-navigation')

    <main class="container">
        @include('partials.main-section')
    </main>

    @include('partials.page-footer')
</body>
</html>
