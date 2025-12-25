@extends('layouts.layout')

@section('header')
    <div id="header-container">
        <div class="img"><img src="img/E-Mensa-logo.png" alt="E-Mensa Logo"></div>
        <!-- Navigation-Bar -->
        <div class="Menu">
            <ul>
                <li><a href="#ankündigung">Ankündigung</a></li>
                <li><a href="#speisen">Speisen</a></li>
                <li><a href="#zahlen">Zahlen</a></li>
                <li><a href="#kontakt">Kontakt</a></li>
                <li><a href="#wichtig">Wichtig für uns</a></li>
                @if(empty($_SESSION['user_id']))
                    <li><a href="/anmeldung">Anmelden</a></li>
                @else
                    <li><a href="/abmeldung">Abmelden</a></li>
                @endif

            </ul>
        </div>
    </div>
@endsection

@section('gericht')
    <!-- Gerichte -->
    <div id="speisen">
        <h1>Köstlichkeiten, die Sie erwarten</h1>
        <a href="/berichtkategorien">Berichtkategorien</a>
        @if($username)
            <a href="/meinebewertungen">Meine Bewertungen</a>
        @endif
        <!-- Sortieren-Button -->
        <form method="GET" action="/gerichte">
            <button type="submit" name="um_sortier" value="{{ $nextOrder }}">
                Umsortieren ({{ $nextOrder }})
            </button>
        </form>
        <!-- Tabelle -->
        <table>
            <thead>
            <tr>
                <th>Gericht</th>
                <th>Allergen</th>
                <th>Preis intern</th>
                <th>Preis extern</th>
                <th>Bewertung</th>
            </tr>
            </thead>
            <tbody>
            @forelse($gerichte as $gericht)
                <tr>
                    <td><img src="/img/gerichte/{{$gericht['bildname'] ?? '00_image_missing.jpg'}}" alt="Gerichtbild">{{ $gericht['gericht_name'] }}</td>
                    <td>{{ $gericht['allergene'] ?? 'Keine' }}</td>
                    <td>{{ $gericht['preis_intern'] }}</td>
                    <td>{{ $gericht['preis_extern'] }}</td>
                    <td><a href="/bewertung?gerichtid={{ $gericht['id'] ?? 'kein wert' }}" class="link-bewertung">Bewerten</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Keine Gerichte gefunden.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <!-- Allergene -->
        <h3>Liste der verwendeten Allergene</h3>
        <ul>
            @foreach($allergene as $allergen)
                <li>{{ $allergen['code'] }}: {{ $allergen['name'] }}</li>
            @endforeach
        </ul>
        <!-- Bewertungen -->
        <h3>Meinungen unserer Gäste</h3>
        <table>
            <thead>
            <tr>
                <th>Gericht</th>
                <th>Bemerkung</th>
                <th>Sterne-Bewertung</th>
                <th>Bewertungsdatum</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bewertungen as $bewertung)
                <tr>
                    <td><img src="/img/gerichte/{{$bewertung['bildname'] ?? '00_image_missing.jpg'}}" alt="{{ $bewertung['gericht_name'] }}">{{ $bewertung['gericht_name'] }}</td>
                    <td>{{ $bewertung['Bemerkung'] }}</td>
                    <td>{{ $bewertung['SterneBewertung'] }}</td>
                    <td>{{ $bewertung['Bewertungszeitpunkt'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Keine Bewertung gefunden.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('kontakt')
    <!-- Kontakt -->
    <div id="kontakt">
        <form method="POST" action="/newsletter">
            <h1>Interesse geweckt? Wir informieren Sie!</h1>
            <div>
                <div>
                    <label for="name">Ihr Name:</label>
                    <br>
                    <input type="text" name="name" id="name" placeholder="Vorname" value="<?php echo htmlspecialchars($_POST["name"] ?? ""); ?>">
                </div>
                <div>
                    <label for="email">Ihre E-Mail:</label>
                    <br>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>">
                </div>
                <div>
                    <label for="language">Newsletter bitte in:</label>
                    <br>
                    <select name="language" id="language">
                        <option value="de" <?php echo (($_POST['language'] ?? '') === 'de') ? 'selected' : ''; ?>>Deutsch</option>
                        <option value="en" <?php echo (($_POST['language'] ?? '') === 'en') ? 'selected' : ''; ?>>English</option>
                    </select>
                </div>
            </div>
            <div>
                <div>
                    <input type="checkbox" id="checkbox" name="datenschutz" required>
                    <label for="checkbox">Den Datenschutzbestimmungen stimme ich zu</label>
                </div>
                <div>
                    <button type="submit" name="submitted" value="true">Zum Newsletter anmelden</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('zahlen')
    <!-- Zahlen -->
    <div id="zahlen">
        <h1>E-Mensa in Zahlen</h1>
        <div>{{$AnzahlBesucher}} Besuchen</div>
        <div>{{$NewsletterAnzahl}} Anmeldungen zum Newsletter</div>
        <div>{{$GerichteAnzahl}} Speisen</div>
    </div>
@endsection

@section('begrüßung')
    <div id="begrüßung">
        <h1>Wir freuen uns auf Ihren Besuch!</h1>
    </div>
@endsection

@section('footer')
    <div id="footer-container">
        <ul>
            <li>(c) E-Mensa GmbH</li>
            <li>Khanh Duy Huynh <br>Ha Thu Uyen Nguyen</li>
            <li>
                <a href="impressum-links">Impressum</a>
            </li>
        </ul>
    </div>
@endsection