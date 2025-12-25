<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmeldung</title>
    <style>
        fieldset {
            border: 3px solid #92b692;
            border-radius: 15px;
            padding: 5%;
            margin: auto;
            width: 50%;
        }
        .error {
            color: red;
            font-size: 1em;
            text-align: center;
        }
        legend {
            font-size: 2em;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #84bf84;
        }
        form {
            text-align: center;
        }
        form label {
            font: 1.5em bold sans-serif;
        }
        form input {
            width: 50%;
            height: auto;
            margin-bottom: 10px;
        }
        form button {
            font: 1.5em bold sans-serif;
            border-radius: 15px;
            background-color: #84bf84;
            color: black;
            border: none;
        }
        form button:hover {
            cursor: pointer;
            color: white;
            border: 2px solid black;
        }
    </style>
</head>
<body>
    <fieldset>
        <legend>Anmeldung</legend>
        @if($error !== '')
            <p class="error">{{$error}}</p>
        @endif
        <form action="/anmeldung_verifizieren" method="POST">
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" required>
            <br><br>
            <label for="passwort">Passwort:</label>
            <input type="password" id="passwort" name="passwort" required>
            <br><br>
            <button type="submit" name="submitted" value="true">Anmeldung</button>
        </form>
    </fieldset>
</body>
</html>