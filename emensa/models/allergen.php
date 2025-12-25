<?php
/**
 * Diese Datei enthält alle SQL Statements für die Tabelle "allergen"
 */

function getAllVerwendetenAllergenASC() {
    $data = []; // Standardrückgabe initialisieren
    $link = null; // Datenbankverbindung
    $gerichte = gerichtWithAllergene_ASC();
    $allergenCodes = [];
    foreach ($gerichte as $gericht) {
        if (!empty($gericht['allergene'])) {
            $allergenCodes = array_merge($allergenCodes, explode(', ', $gericht['allergene']));
        }
    }
    $allergenCodes = array_unique($allergenCodes);

    try {
        // Verbindung zur Datenbank herstellen
        $link = connectdb();

        // Prepare the WHERE clause values
        if (!empty($allergenCodes)) {
            $placeholders = implode(',', array_fill(0, count($allergenCodes), '?'));
            $stmtAllergen = $link->prepare("
                                                    SELECT DISTINCT a.code, a.name
                                                    FROM allergen a
                                                    WHERE a.code IN ($placeholders)
                                                    ORDER BY a.code
                                                    ");

            // Bind parameters dynamically
            $stmtAllergen->bind_param(str_repeat('s', count($allergenCodes)), ...$allergenCodes);

            $stmtAllergen->execute();
            $resultAllergen = $stmtAllergen->get_result();
        }

        // Ergebnisse in ein Array laden
        $data = $resultAllergen->fetch_all(MYSQLI_ASSOC);

        // Ressourcen freigeben
        $stmtAllergen->close();
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

function getAllVerwendetenAllergenDESC() {
    $data = []; // Standardrückgabe initialisieren
    $link = null; // Datenbankverbindung
    $gerichte = gerichtWithAllergene_DESC();
    $allergenCodes = [];
    foreach ($gerichte as $gericht) {
        if (!empty($gericht['allergene'])) {
            $allergenCodes = array_merge($allergenCodes, explode(', ', $gericht['allergene']));
        }
    }
    $allergenCodes = array_unique($allergenCodes);

    try {
        // Verbindung zur Datenbank herstellen
        $link = connectdb();

        // Prepare the WHERE clause values
        if (!empty($allergenCodes)) {
            $placeholders = implode(',', array_fill(0, count($allergenCodes), '?'));
            $stmtAllergen = $link->prepare("
                                                    SELECT DISTINCT a.code, a.name
                                                    FROM allergen a
                                                    WHERE a.code IN ($placeholders)
                                                    ORDER BY a.code
                                                    ");

            // Bind parameters dynamically
            $stmtAllergen->bind_param(str_repeat('s', count($allergenCodes)), ...$allergenCodes);

            $stmtAllergen->execute();
            $resultAllergen = $stmtAllergen->get_result();
        }

        // Ergebnisse in ein Array laden
        $data = $resultAllergen->fetch_all(MYSQLI_ASSOC);

        // Ressourcen freigeben
        $stmtAllergen->close();
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