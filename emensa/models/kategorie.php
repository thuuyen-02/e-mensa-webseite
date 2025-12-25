<?php
/**
 * Diese Datei enthält alle SQL Statements für die Tabelle "kategorie"
 */
function db_kategorie_select_all() {
    $link = connectdb();

    $sql = "SELECT * FROM kategorie ORDER BY name ASC";
    $result = mysqli_query($link, $sql);

    $data = mysqli_fetch_all($result, MYSQLI_BOTH);

    mysqli_close($link);
    return $data;
}
function get_kategoriegerichte_vegetarisch() {
    $data = []; // Standardrückgabe initialisieren
    $link = null; // Datenbankverbindung

    try {
        // Verbindung zur Datenbank herstellen
        $link = connectdb();

        // SQL-Statement vorbereiten und ausführen
        $sql = 'SELECT * FROM view_kategoriegerichte_vegetarisch';
        $stmt = $link->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        // Ergebnisse in ein Array laden
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // Ressourcen freigeben
        $stmt->close();
    }
    catch (Exception $ex) {
        // Fehler abfangen und strukturierte Rückgabe erzeugen
        $data = [
            'error' => true,
            'message' => 'Datenbankfehler: ' . $ex->getMessage(),
        ];
    }
    finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
        return $data;
    }
}