<?php
/**
 * Diese Datei enthält alle SQL Statements für die Tabelle "besucher"
 */
function getAnzahlBesucher()
{
    $link = connectdb();

    // IP-Adresse und aktuelles Datum
    $ip = $_SERVER['REMOTE_ADDR'];
    $datum = date('Y-m-d'); // Aktuelles Datum

    // Überprüfen, ob die IP heute bereits gezählt wurde
    $sql_check_ip = "SELECT 1 FROM besucher_log WHERE ip = ? AND datum = ?";
    $stmt_check = mysqli_prepare($link, $sql_check_ip);

    if ($stmt_check) {
        mysqli_stmt_bind_param($stmt_check, 'ss', $ip, $datum);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        $num_rows = mysqli_num_rows($result_check);
        mysqli_stmt_close($stmt_check);

        if ($num_rows == 0) {
            // IP wurde heute noch nicht gezählt, Besucherzähler aktualisieren
            $sql_anz_besucher_update = "UPDATE besucher_zaehler SET zaehler = zaehler + 1 WHERE ip = 1";
            if (!mysqli_query($link, $sql_anz_besucher_update)) {
                error_log("Fehler beim Aktualisieren des Besucherzählers: " . mysqli_error($link));
            }

            // IP und Datum in die Log-Tabelle einfügen
            $sql_insert_ip = "INSERT INTO besucher_log (ip, datum) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($link, $sql_insert_ip);
            if ($stmt_insert) {
                mysqli_stmt_bind_param($stmt_insert, 'ss', $ip, $datum);
                if (!mysqli_stmt_execute($stmt_insert)) {
                    error_log("Fehler beim Einfügen in die Besucher-Log-Tabelle: " . mysqli_error($link));
                }
                mysqli_stmt_close($stmt_insert);
            }
        }
    } else {
        error_log("Fehler bei der Vorbereitung der Abfrage: " . mysqli_error($link));
        return 0;
    }

    // Aktuellen Besucherzähler abrufen
    $sql_anz_besucher_get = "SELECT zaehler FROM besucher_zaehler WHERE ip = 1";
    $result = mysqli_query($link, $sql_anz_besucher_get);

    if (!$result) {
        error_log("Fehler beim Abrufen des Besucherzählers: " . mysqli_error($link));
        return 0; // Standardwert bei Fehler
    }

    $row = mysqli_fetch_assoc($result);
    return $row['zaehler'];
}

function getNewsletterAnzahl()
{
    $cnt = 0;
    $file = fopen('../storage/anmeldung.txt', "r");
    if (!$file) {
        die("File could not be opened.");
    } else {
        while (!feof($file)) {
            $lines = fgets($file);
            if($lines !== false)
                $cnt++;
        }
    }
    fclose($file);
    return $cnt;
}

function isDisposableEmail($email) {
// Liste der unerwünschten Domains
    $disposableDomains = [
        'wegwerfmail.de',
        'trashmail.de',
        'trashmail.com'
// Weitere unerwünschte Domains können hier hinzugefügt werden
    ];
// Extrahiere die Domain aus der E-Mail-Adresse
    $emailDomain = substr(strrchr($email, "@"), 1);
// Überprüfe, ob die Domain in der Liste der unerwünschten Domains enthalten ist
    return in_array($emailDomain, $disposableDomains);
}

/**
 * Holt einen Benutzer basierend auf der E-Mail-Adresse aus der Datenbank.
 *
 * @param string $email Die E-Mail-Adresse des Benutzers.
 * @return array|null Benutzer-Daten oder null, wenn kein Benutzer gefunden wurde.
 */
function getBenutzerByEmail(string $email): ?array {
    $data = []; // Standardrückgabe initialisieren
    $link = null; // Datenbankverbindung

    try {
        // Verbindung zur Datenbank herstellen
        $link = connectdb();

        // Transaktion starten
        $link->begin_transaction();

        // SQL-Statement mit einem Platzhalter
        $sql = "SELECT * FROM benutzer WHERE email = ?";
        $stmt = $link->prepare($sql);

        // Platzhalter binden und ausführen
        $stmt->bind_param('s', $email); // 's' steht für String
        $stmt->execute();

        // Ergebnisse abrufen
        $result = $stmt->get_result();

        // Nur einen Benutzer laden, da die E-Mail eindeutig ist
        $data = $result->fetch_assoc(); // Einzelnes Array statt fetch_all()

        // Ressourcen freigeben
        $stmt->close();
        // Transaktion abschließen
        $link->commit();
    }
    catch (Exception $ex) {
        // Fehler abfangen und strukturierte Rückgabe erzeugen
        echo $ex->getMessage();
        echo 'getBenutzerByEmail() sind falsch!';
    }
    finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
        return $data;
    }
}

/**
 * Aktualisiert die Datenbank nach einer erfolgreichen Anmeldung.
 *
 * @param int $userId Die ID des Benutzers.
 * @return void
 */
function updateBenutzerErfolgreicheAnmeldung(int $userId): void {

    $link = null; // Datenbankverbindung
    //$date = date('Y-m-d H:i:s'); // Aktuelles Datum
    try {
        // Verbindung zur Datenbank herstellen
        $link = connectdb();

        // Transaktion starten
        $link->begin_transaction();

        // SQL-Statement vorbereiten und ausführen
        $sql = "CALL erfolgreich_anmeldung(?)";

        $stmt = $link->prepare($sql);
        $stmt->bind_param('i',$userId);
        $stmt->execute();

        // Ressourcen freigeben
        $stmt->close();
        // Transaktion abschließen
        $link->commit();
    }
    catch (Exception $ex) {
        // Fehler abfangen und strukturierte Rückgabe erzeugen
        echo $ex->getMessage();
        echo 'updateBenutzerErfolgreicheAnmeldung() sind falsch!';
    }
    finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
    }
}

/**
 * Aktualisiert die Datenbank nach einer fehlgeschlagenen Anmeldung.
 *
 * @param int $userId Die ID des Benutzers.
 * @return void
 */
function updateBenutzerFehlgeschlageneAnmeldung(int $userId): void {
    $link = null; // Datenbankverbindung
    try {
        // Verbindung zur Datenbank herstellen
        $link = connectdb();

        // Transaktion starten
        $link->begin_transaction();
        // SQL-Statement vorbereiten und ausführen
        $sql = "CALL fehler_anmeldung(?)";
        $stmt = $link->prepare($sql);
        $stmt->bind_param('i',$userId);
        $stmt->execute();

        // Ressourcen freigeben
        $stmt->close();
        // Transaktion abschließen
        $link->commit();
    }
    catch (Exception $ex) {
        // Fehler abfangen und strukturierte Rückgabe erzeugen
        echo $ex->getMessage();
        echo 'updateBenutzerFehlgeschlageneAnmeldung() sind falsch!';
    }
    finally {
        // Verbindung schließen, falls geöffnet
        if ($link !== null) {
            $link->close();
        }
    }
}

?>