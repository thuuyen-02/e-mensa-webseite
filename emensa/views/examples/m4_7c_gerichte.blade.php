<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gerichte</title>
</head>
<body>
@if(!$gerichte)
    <p>Es sind keine Gerichte vorhanden</p>
@else
    <ul>
        @foreach($gerichte as $gericht)
            <li>{{ $gericht['name'] }} - {{ $gericht['preis_intern'] }} Euro</li>
        @endforeach
    </ul>
@endif
</body>
</html>