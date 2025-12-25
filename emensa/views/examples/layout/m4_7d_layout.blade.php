<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Standard Title')</title>
</head>
<body>

<header>
    <h1>Willkommen zu meiner Webseite</h1>
</header>

<main>
    @yield('main')
</main>

<footer>
    <p>&copy; 2024 Mein Unternehmen</p>
</footer>

</body>
</html>
