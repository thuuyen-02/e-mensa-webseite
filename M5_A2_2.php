<?php
/**
 * Praktikum DBWT. Autoren:
 * Khanh Duy, Huynh, 3648058
 * Ha Thu Uyen, Nguyen, 3640784
 */

$link = mysqli_connect("localhost", "root", "279134", "emensawerbeseite");

if (!$link) {
    echo "Verbindung fehlgeschlagen: ", mysqli_connect_error();
    exit();
}

// Bilderverzeichnis definieren
$imageDir = 'C:\DBWT\M5\emensa\public\img\gerichte';

// Dateien im Verzeichnis auslesen
$files = scandir($imageDir);

// Über alle Gerichte iterieren
$sql = "SELECT id FROM gericht";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo "Fehler während der Abfrage: ", mysqli_error($link);
    exit();
}

$gerichte = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Bilder zuordnen
foreach ($gerichte as $gericht) {
    $id = $gericht['id'];
    $formattedId = str_pad($id, 2, "0", STR_PAD_LEFT); // IDs auf 2 Stellen auffüllen
    $bildname = null;

    // Bild suchen, das mit der formatierten ID beginnt
    foreach ($files as $file) {
        if (strpos($file, $formattedId . '_') === 0) {
            $bildname = $file;
            break;
        }
    }

    // Datenbank aktualisieren
    $stmt = mysqli_prepare($link, "UPDATE gericht SET bildname = ? WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $bildname, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "Fehler beim Vorbereiten des Statements: ", mysqli_error($link);
    }
}


echo "Bilder erfolgreich zugeordnet!";
mysqli_close($link);
?>
