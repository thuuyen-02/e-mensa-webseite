<?php
/**
 * Diese Datei enthält alle SQL Statements für die Tabelle "gerichte"
 */
function db_gericht_select_all() {
    try {
        $link = connectdb();

        $sql = 'SELECT id, name, beschreibung, bildname FROM gericht ORDER BY name';
        $result = mysqli_query($link, $sql);

        $data = mysqli_fetch_all($result, MYSQLI_BOTH);

        mysqli_close($link);
    }
    catch (Exception $ex) {
        $data = array(
            'id'=>'-1',
            'error'=>true,
            'name' => 'Datenbankfehler '.$ex->getCode(),
            'beschreibung' => $ex->getMessage());
    }
    finally {
        return $data;
    }
}

function db_sortiert_gericht_preisintern_hoeher_als_2() {
    $data = []; // Standardrückgabe initialisieren
    $link = null; // Datenbankverbindung

    try {
        // Verbindung zur Datenbank herstellen
        $link = connectdb();

        // SQL-Statement vorbereiten und ausführen
        $sql = 'SELECT name, preisintern, bildname FROM gericht 
                WHERE preisintern > 2.0         
                ORDER BY name';
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

function getGerichteAnzahl()
{
    // Verbindung zur Datenbank herstellen
    $link = connectdb();
    // SQL-Query mit Prepared Statements
    $stmt = $link->prepare("SELECT COUNT(name) AS anz_gericht FROM gericht");

    // Ausführen der Abfrage
    if ($stmt->execute()) {
        // Ergebnis holen
        $result = $stmt->get_result();
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['anz_gericht'];
        }
    } else {
        // Fehlerbehandlung
        error_log("Fehler während der Abfrage: " . $stmt->error);
    }

    return 0; // Gibt 0 zurück, wenn Fehler eintreten
}

function gerichtWithAllergene_ASC() {
    $data = []; // Standardrückgabe initialisieren
    $link = null; // Datenbankverbindung

    try {
        // Verbindung zur Datenbank herstellen
        $link = connectdb();

        // SQL-Statement vorbereiten und ausführen
        $sql = "CALL sortierGerichtmitAllergen(TRUE)";
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

function gerichtWithAllergene_DESC() {
    $data = []; // Standardrückgabe initialisieren
    $link = null; // Datenbankverbindung

    try {
        // Verbindung zur Datenbank herstellen
        $link = connectdb();

        // SQL-Statement vorbereiten und ausführen
        $sql = "CALL sortierGerichtmitAllergen(FALSE)";
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

function wunschGerichtAnmeldung()
{
    $link = null; // Datenbankverbindung

    try {
        // Verbindung aufbauen
        $link = connectdb();

        // Daten aus dem Formular
        $name = $_POST['name'] ?: 'anonym'; // Standardwert, falls leer
        $email = $_POST['email'];
        $gericht_name = $_POST['gericht_name'];
        $beschreibung = $_POST['beschreibung'];
        $erstellungsdatum = date('Y-m-d');


        // Ersteller einfügen oder prüfen
        $stmt = $link->prepare("SELECT Ersteller_ID FROM Ersteller WHERE Email = ?");
        $stmt->execute([$email]);
        $result = $stmt->get_result();

        // Ergebnisse in ein Array laden
        $ersteller = $result->fetch_assoc();
        if (!$ersteller) {
            $stmt = $link->prepare("INSERT INTO Ersteller (Name, Email) VALUES (?, ?)");
            $stmt->execute([$name, $email]);
            // Die letzte eingefügte ID abrufen
            $ersteller_id = $link->insert_id;

        } else if (is_array($ersteller)) {
            // Datensatz gefunden
            $ersteller_id = $ersteller["Ersteller_ID"];
        } else {
            die("Fehler: Unerwarteter Wert von fetch()");
        }

        // Wunschgericht einfügen
        $stmt = $link->prepare("INSERT INTO Wunschgericht (Name, Beschreibung, Erstellungsdatum, Ersteller_ID) VALUES (?, ?, ?, ?)");
        $stmt->execute([$gericht_name, $beschreibung, $erstellungsdatum, $ersteller_id]);

        // Verbindung schließen
        $link = null;
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
        return 0;
    }
}

function bewertung()
{
    $link = null; // Datenbankverbindung

    try {
        // Verbindung aufbauen
        $link = connectdb();

        // Daten aus dem Formular
        $bemerkung = $_POST['bemerkung'] ?? null;
        $bewertung = $_POST['bewertung'] ?? null;
        $datum = $date = date('Y-m-d H:i:s');

        $gerichtID = $_SESSION['gerichtid'];
        echo $gerichtID;
        $benutzerID = $_SESSION['user_id'] ?? null;

        // Bewertung in die Datenbank einfügen
        $stmt = $link->prepare("INSERT INTO bewertungen (BenutzerID, GerichtID, Bemerkung, SterneBewertung, Bewertungszeitpunkt) 
                                    VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('iisss', $benutzerID, $gerichtID, $bemerkung, $bewertung, $datum);
        $stmt->execute();

        // Verbindung schließen
        $link = null;
    } catch (Exception $ex) {
        // Fehler abfangen und strukturierte Rückgabe erzeugen
        echo $ex->getMessage();
    } finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
        return 0;
    }
}

function getBildname()
{
    $link = null; // Datenbankverbindung
    $data = null; // Initialwert für das Bildname

    try {
        // Verbindung aufbauen
        $link = connectdb();

        $gerichtID = $_SESSION['gerichtid'];

        // Stored Procedure aufrufen
        $stmt = $link->prepare("CALL getBildnameVonID(?)");
        $stmt->bind_param('i', $gerichtID);
        $stmt->execute();

        $result = $stmt->get_result();

        // Überprüfen, ob Ergebnisse vorhanden sind
        if ($result && $result->num_rows > 0) {
            // Einzelergebnis auslesen
            $row = $result->fetch_assoc();
            $data = $row['bildname']; // Nur den Bildnamen zurückgeben
        }
        // Verbindung schließen
        $stmt->close();
    } catch (Exception $ex) {
        // Fehler abfangen und strukturierte Rückgabe erzeugen
        echo $ex->getMessage();
    } finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
    }

    // Rückgabe des Bildnamens (oder des Standardbildes)
    return $data;
}

function getGerichtBewertungen()
{
    $link = null; // Datenbankverbindung
    $data = []; // Initialwert für das Bildname

    try {
        // Verbindung aufbauen
        $link = connectdb();

        // Stored Procedure aufrufen
        $stmt = $link->prepare("CALL showBewertungen()");
        $stmt->execute();
        $result = $stmt->get_result();

        // Ergebnisse in ein Array laden
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // Ressourcen freigeben
        $stmt->close();

    } catch (Exception $ex) {
        // Fehler abfangen und strukturierte Rückgabe erzeugen
        echo $ex->getMessage();
    } finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
    }
    // Rückgabe des Bildnamens (oder des Standardbildes)
    return $data;
}

function getMeineBewertungen()
{
    $link = null; // Datenbankverbindung
    $data = []; // Initialwert für das Bildname

    try {
        // Verbindung aufbauen
        $link = connectdb();
        $userID = $_SESSION['user_id'] ?? null;
        // Stored Procedure aufrufen
        $stmt = $link->prepare("CALL showUserBewertungen($userID)");
        $stmt->execute();
        $result = $stmt->get_result();

        // Ergebnisse in ein Array laden
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // Ressourcen freigeben
        $stmt->close();

    } catch (Exception $ex) {
        // Fehler abfangen und strukturierte Rückgabe erzeugen
        echo $ex->getMessage();
    } finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
    }
    // Rückgabe des Bildnamens (oder des Standardbildes)
    return $data;
}

function deleteBewertung(int $bewertungID): bool
{
    $link = null; // Datenbankverbindung
    try {
        // Verbindung aufbauen
        $link = connectdb();

        // Bewertung löschen
        $stmt = $link->prepare("DELETE FROM bewertungen WHERE BewertungID = ?");
        $stmt->bind_param("i", $bewertungID);
        $stmt->execute();

        // Überprüfen, ob eine Zeile gelöscht wurde
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return true; // Erfolgreich gelöscht
        }

        // Falls keine Zeile gelöscht wurde
        $stmt->close();
        return false;

    } catch (Exception $ex) {
        // Fehler abfangen und ausgeben
        echo "Fehler: " . $ex->getMessage();
        return false;
    } finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
    }
}

function update_hervorheben(int $bewertungID): bool
{
    $link = null; // Datenbankverbindung
    try {
        // Verbindung aufbauen
        $link = connectdb();

        // Hervorhebungsstatus der Bewertung umkehren
        $stmt = $link->prepare("UPDATE bewertungen
                                      SET Hervorgehoben = CASE 
                                                            WHEN Hervorgehoben = 1 THEN 0 
                                                            ELSE 1 
                                                          END
                                      WHERE BewertungID = ?;");
        $stmt->bind_param("i", $bewertungID);
        $stmt->execute();

        /// Überprüfen, ob eine Zeile aktualisiert wurde
        $updated = $stmt->affected_rows > 0;

        $stmt->close();
        return $updated;

    } catch (Exception $ex) {
        // Fehler abfangen und ausgeben
        echo "Fehler: " . $ex->getMessage();
        return false;
    } finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
    }
}

function getHervorgehobeneBewertungen()
{
    $link = null; // Datenbankverbindung
    $data = []; // Initialwert für das Bildname

    try {
        // Verbindung aufbauen
        $link = connectdb();

        // Stored Procedure aufrufen
        $stmt = $link->prepare("CALL showHervorgehobeneBewertungen()");
        $stmt->execute();
        $result = $stmt->get_result();

        // Ergebnisse in ein Array laden
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // Ressourcen freigeben
        $stmt->close();

    } catch (Exception $ex) {
        // Fehler abfangen und strukturierte Rückgabe erzeugen
        echo $ex->getMessage();
    } finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
    }
    // Rückgabe des Bildnamens (oder des Standardbildes)
    return $data;
}

