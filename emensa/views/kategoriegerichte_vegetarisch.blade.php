<!DOCTYPE html>
<html lang="de">
<head>
    <title>Kategorie</title>
    <meta charset="utf-8">
    <style>
        table {
            border: 1px solid black;
            width: 70%;
            height: 70%;
            text-align: center;
        }

        th {
             border: 1px solid black;
            background-color: #84bf84;
        }
        tr:nth-child(even) {
            background-color: #d3e1be;
        }
    </style>
</head>
<body>
<h1>Bericht: Kategorien und vegetarische Gerichte</h1>
<table>
    <thead>
    <tr>
        <th>Kategorie-Name</th>
        <th>Gericht-Name</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($kategorien as $kategorie): ?>
    <tr>
        <td><?= htmlspecialchars($kategorie['kategorie_name']) ?></td>
        <td><?= htmlspecialchars($kategorie['gericht_name'] ?? 'Keine') ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
