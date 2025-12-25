<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>E-Mensa</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header>
    @yield('header')
</header>
<main>
    <div id="body-container">
        @if($username)
            <h1 class="username">Angemeldet als {{$username}}</h1>
        @endif
        <img src="img/Mensa-image.png" alt="Mensa">
        <div>
            <!-- Ankündigung -->
            <div id="ankündigung">
                <label for="text-area">Bald gibt es Essen auch online ;)</label>
                <textarea id="text-area" name="text-area" rows="9" cols="100">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</textarea>
            </div>
            @yield('gericht')
            @yield('kontakt')
            @yield('zahlen')
            <div id="wunschgericht">
                <h1>Sie haben einen Wunschgericht? <a href="/wunschgericht">Wunschgericht einreichen</a>
                </h1>
            </div>
            <div id="wichtig">
                <h1>Das ist uns wichtig</h1>
                <div>
                    <ul>
                        <li>Beste frische saisonale Zutaten</li>
                        <li>Ausgewogene abwechslungsreiche Gerichte</li>
                        <li>Sauberkeit</li>
                    </ul>
                </div>
            </div>
            @yield('begrüßung')
        </div>
    </div>
</main>
<footer>
    @yield('footer')
</footer>
</body>

</html>