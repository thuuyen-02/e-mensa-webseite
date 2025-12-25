<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Bewertungen</title>
    <style>
        table {
            border: 1px solid black;
            width: 70%;
            height: 70%;
            text-align: center;
            margin: auto;
        }

        table img {
            max-width: 30%;
            max-height: 30%;
        }

        th {
            border: 1px solid black;
            background-color: #84bf84;
        }

        tr:nth-child(even) {
            background-color: #d3e1be;
        }

        .delete-button {
            color: white;
            background-color: red;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
<h1>Meine Bewertungen</h1>
<table>
    <thead>
    <tr>
        <th>Gericht</th>
        <th>Bemerkung</th>
        <th>Sterne-Bewertung</th>
        <th>Bewertungsdatum</th>
        <th>Aktion</th>
    </tr>
    </thead>
    <tbody>
    @forelse($bewertungen as $bewertung)
        <tr>
            <td>
                <img src="/img/gerichte/{{ $bewertung['bildname'] ?? '00_image_missing.jpg' }}"
                     alt="{{ $bewertung['gericht_name'] }}">
                {{ $bewertung['gericht_name'] }}
            </td>
            <td>{{ $bewertung['Bemerkung'] }}</td>
            <td>{{ $bewertung['SterneBewertung'] }}</td>
            <td>{{ $bewertung['Bewertungszeitpunkt'] }}</td>
            <td>
                <form action="/delete_bewertung" method="POST">
                    <input type="hidden" name="bewertung_id" value="{{ $bewertung['BewertungID'] }}">
                    <button type="submit" class="delete-button" name="submitted" value="true">Löschen</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5">Keine Bewertungen gefunden.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
