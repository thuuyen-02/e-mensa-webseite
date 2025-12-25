<!DOCTYPE html>
<html>
<head>
    <title>Kategorien</title>
    <style>
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
<h1>Liste der Kategorien</h1>
<ul>
    @foreach($categories as $index => $category)
        <li class="{{ $index % 2 == 1 ? 'bold' : '' }}">
            {{ $category['name'] }}
        </li>
    @endforeach
</ul>
</body>
</html>
