<?php
$link=mysqli_connect("localhost", // Host der Datenbank
    "root",                 // Benutzername zur Anmeldung
    "279134",    // Passwort
    "emensawerbeseite"      // Auswahl der Datenbanken (bzw. des Schemas)
// optional port der Datenbank
);

if (!$link) {
    echo "Verbindung fehlgeschlagen: ", mysqli_connect_error();
    exit();
}

$sql = "SELECT id, name, beschreibung FROM gericht";

$result = mysqli_query($link, $sql);
if (!$result) {
    echo "Fehler während der Abfrage:  ", mysqli_error($link);
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>A4</title>
    <style>
        table,th,td{
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <table>
        <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Typ</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>'.'<td>'.$row['id'].'</td>'.'<td>'.$row['name'].'</td>'.'<td>'.$row['beschreibung'].'</td>'.'</tr>';
        }
        ?>
        </tbody>
    </table>
</body>
</html>


<?php
mysqli_free_result($result);
mysqli_close($link);
?>