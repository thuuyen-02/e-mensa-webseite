<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewertung</title>
    <style>
        body {
            border: 2px solid #98b36a;
            margin-left: 10%;
            margin-right: 10%;
        }
        h1 {
            text-align: center;
            color: #228f5c;
        }
        table {
            text-align: center;
        }
        .bewertungen th, td {
            border: 1px solid black;
        }
        .bewertungen>table img {
            float: left;
        }

        form {
            margin: 5%;
            padding-left: 10%;
            padding-right: 10%;
        }
        label {
            font-size: 25px;
        }
        textarea, select {
            width: 100%;
            font-size: 20px;
            margin-top: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
        }
        button {
            border-radius: 10px;
            border: 1px solid green;
            margin-top: 20px;
            margin-left: 40%;
            width: 20%;
            height: 30px;
        }
        button:hover {
            color: white;
            background-color: #499773;
        }
        .error {
            color: red;
            font-size: 1em;
            text-align: center;
        }
        img {
            max-height: 20%;
            max-width: 20%;
            padding-left: 40%;
        }
        .hervorgehoben {
            background-color: #76a676;
        }
        /* Für kleinere Bildschirme (bis 600px) Flexbox nebeneinander */
        @media (max-width: 600px) {
            form {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            label {
                width: 25%;
                text-align: right;
            }

            textarea,
            select {
                width: 70%;
            }

            button {
                margin-left: 0;
                width: 15%;
            }
        }
    </style>
</head>
<body>
<h1>Bewertung eingeben</h1>
<img src="/img/gerichte/{{ $bildname ?? '00_image_missing.jpg' }}" alt="{{ $bildname }}">
@if(!isset($error)||$error !== '')
    <p class="error">{{$error}}</p>
@endif
<form action="/bewertung_action" method="POST">
    <!-- Bemerkung -->
    <label for="bemerkung">Bemerkung:</label>
    <textarea id="bemerkung" name="bemerkung" minlength="5" required></textarea>
    <!-- Sterne-Bewertung -->
    <label for="bewertung">Bewertung:</label>
    <select id="bewertung" name="bewertung" required>
        <option value="" disabled selected>Bitte eine Bewertung auswählen</option>
        <option value="sehr gut">Sehr gut</option>
        <option value="gut">Gut</option>
        <option value="schlecht">Schlecht</option>
        <option value="sehr schlecht">Sehr schlecht</option>
    </select>
    <!-- Absendebutton -->
    <button type="submit" name="submitted" value="true">Abschicken</button>
</form>
<!-- erfassten Bewertungen -->
<div class="bewertungen">
    <table>
        <thead>
        <tr>
            <th>Gericht</th>
            <th>Bemerkung</th>
            <th>Sterne-Bewertung</th>
            <th>Bewertungsdatum</th>
            @if($admin)
                <th>Hervorheben</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($bewertungen as $bewertung)
            <tr @if($bewertung['Hervorgehoben'])
                    class="hervorgehoben"
            @endif
            >
                <td><img src="/img/gerichte/{{$bewertung['bildname'] ?? '00_image_missing.jpg'}}" alt="{{ $bewertung['gericht_name'] }}">{{ $bewertung['gericht_name'] }}</td>
                <td>{{ $bewertung['Bemerkung'] }}</td>
                <td>{{ $bewertung['SterneBewertung'] }}</td>
                <td>{{ $bewertung['Bewertungszeitpunkt'] }}</td>
                @if($admin)
                    <td>
                        <a href="/updateHervorheben?bewertungID={{ $bewertung['BewertungID'] }}">{{ $bewertung['Hervorgehoben']? 'Hervorhebung abwählen':'hervorheben'}}</a>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="4">Keine Bewertung gefunden.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>