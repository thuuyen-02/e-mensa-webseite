<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/kategorie.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/allergen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/besucher.php');
const ALLOWED_ORDERS = ['ASC', 'DESC']; // Whitelist
/* Datei: controllers/HomeController.php */
class HomeController
{
    public function index(RequestData $request) {
        // Standard-Sortierreihenfolge festlegen
        logger()->info('Zugriff auf die Hauptseite', ["IP: ".$_SERVER['REMOTE_ADDR']]);
        $order = 'ASC';
        $selectedOrder = strtoupper($request->query['um_sortier'] ?? "");
        if ( ($pos = array_search($selectedOrder, ALLOWED_ORDERS)) !== false) {
            $order = ALLOWED_ORDERS[$pos];
        }
        $gerichte = $order === "ASC" ? gerichtWithAllergene_ASC() : gerichtWithAllergene_DESC();
        $nextOrder = $order === 'ASC' ? 'DESC' : 'ASC';

        $bewertungen = getHervorgehobeneBewertungen();

        // Zusätzliche Daten abrufen
        $allergene = $order === "ASC" ? getAllVerwendetenAllergenASC(): getAllVerwendetenAllergenDESC();;
        // Newsletter-Verarbeitung
        $fehler = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['submitted'])) {
            $name = trim($_POST["name"] ?? "");
            $name = preg_replace('/[\x00-\x1F\x7F]/u', '', $name);
            if (empty($name)) {
                $fehler['Name'] = "Ihre Name entspricht nicht den Vorgaben.";
            }

            $email = $_POST['email'] ?? null;
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || isDisposableEmail($email)) {
                $fehler['Email'] = "Ihre E-Mail entspricht nicht den Vorgaben.";
            }

            $language = $_POST['language'] ?? 'de';
            if (!in_array($language, ['de', 'en'], true)) {
                $fehler['Language'] = "Ungültige Sprache.";
            }

            if (empty($fehler)) {
                $entry = sprintf("Name: %s E-Mail: %s Sprache: %s\n", $name, $email, $language);
                //hier soll DB verwenden
                if (file_put_contents($_SERVER['DOCUMENT_ROOT'].'/../storage/anmeldung.txt', $entry, FILE_APPEND) === false) {
                    $fehler['System'] = "Daten konnten nicht gespeichert werden.";
                } else {
                    echo '<p>Vielen Dank für Ihre Anmeldung!</p>';
                }
            } else {
                echo '<div id="warning">Es sind Fehler aufgetreten<ul>';
                foreach ($fehler as $key => $value) {
                    echo "<li>$key: $value</li>";
                }
                echo '</ul></div>';
            }
        }

        $AnzahlBesucher = getAnzahlBesucher();
        $NewsletterAnzahl = getNewsletterAnzahl();
        $GerichteAnzahl = getGerichteAnzahl();

        return view('home', [
            'rd' => $request ,
            'gerichte' => $gerichte,
            'bewertungen' => $bewertungen,
            'nextOrder' => $nextOrder,
            'allergene' => $allergene,
            'AnzahlBesucher' => $AnzahlBesucher,
            'NewsletterAnzahl' => $NewsletterAnzahl,
            'GerichteAnzahl' => $GerichteAnzahl,
            'username' => $_SESSION['username']?? NULL,
        ]);
    }

    public function debug(RequestData $request) {
        return view('debug');
    }

    public function wunschgericht() {
        unset($_SESSION['parent']);
        if (!(isset($_SESSION['login']) && $_SESSION['login'])) {
            $_SESSION['parent'] = "/wunschgericht";
            header('Location: /anmeldung');
            exit;
        }
        return view('wunschgericht', []);
    }

    public function wunschgerechtemeldung() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['submitted'])) {
            wunschGerichtAnmeldung();
            header('Location: /');
            exit;
        }
        return view('wunschgericht', []);
    }

    public function anmeldung() {
        return view('anmeldung', ['error' => '']);
    }

    public function anmeldung_verifizieren() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['submitted'])) {
            // Eingegebene Daten aus dem Formular holen
            $email = $_POST['email'] ?? null;
            $password = $_POST['passwort'] ?? null;

            if (!$email || !$password) {
                return view('anmeldung', ['error' => 'Bitte geben Sie E-Mail und Passwort ein.']);
            }

            // Salt verwenden (entsprechend der Registrierung)
            $salt = "honig";
            // Passwort mit Salt kombinieren
            $saltedPassword = $salt . $password;
            // Benutzer in der Datenbank suchen
            $user = getBenutzerByEmail($email);
            if ($user) {
                // Überprüfen, ob das eingegebene Passwort mit dem gespeicherten Hash übereinstimmt
                if (password_verify($saltedPassword, $user['passwort'])) {
                    // Anmeldung erfolgreich
                    $_SESSION['login'] = true;
                    $_SESSION['username'] = $user['name'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['admin'] = $user['admin'];
                    logger()->info($_SESSION['username']." hat angemeldet.", ["IP: ".$_SERVER['REMOTE_ADDR']]);

                    // Statistik aktualisieren
                    updateBenutzerErfolgreicheAnmeldung($user['id']);
                    // Weiterleitung
                    if(isset($_SESSION['parent'])) {
                        header('Location: '.$_SESSION['parent']);
                        exit;
                    }
                    header('Location: /');
                    exit;
                } else {
                    // Fehlgeschlagene Anmeldung
                    $_SESSION['login'] = false;
                    logger()->warning($user['name']." hat fehlgeschlagenen angemeldet!!", ["IP: ".$_SERVER['REMOTE_ADDR']]);
                    updateBenutzerFehlgeschlageneAnmeldung($user['id']);
                }
            }
            $_SESSION['login'] = false;
            return view('anmeldung', ['error' => 'E-Mail oder Passwort sind falsch.']);
        }

        // Standardmäßig Anmeldemaske anzeigen
        return view('anmeldung', ['error' => '']);
    }

    public function abmeldung() {
        logger()->info($_SESSION['username']. " hat abgemeldet.", ["IP: ".$_SERVER['REMOTE_ADDR']]);

        // Session-Variablen löschen und Session zerstören
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }

    public function berichtkategorien() {
        $kategorien = get_kategoriegerichte_vegetarisch();
        // Daten an die View übergeben
        return view('kategoriegerichte_vegetarisch', ['kategorien' => $kategorien]);
    }

    public function bewertung()
    {
        $_SESSION['gerichtid'] = $_GET['gerichtid'];
        unset($_SESSION['parent']);
        if (!(isset($_SESSION['login']) && $_SESSION['login'])) {
            $_SESSION['parent'] = "/bewertung?gerichtid=".$_SESSION['gerichtid'];
            header('Location: /anmeldung');
            exit;
        }
        $bildname = getBildname();
        $bewertungen = getGerichtBewertungen();
        return view('bewertung', ['bildname' => $bildname,
            'bewertungen' => $bewertungen,
            'admin' => $_SESSION['admin']?? false,
            'error' => '']);
    }

    public function bewertung_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['submitted'])) {
            bewertung();
            header('Location: /');
            exit;
        }
        return view('bewertung', ['error' => 'Fehler beim Bewerten!']);
    }

    public function meinebewertungen()
    {
        unset($_SESSION['parent']);
        if (!(isset($_SESSION['login']) && $_SESSION['login'])) {
            $_SESSION['parent'] = "/meinebewertungen";
            header('Location: /anmeldung');
            exit;
        }
        $bewertungen = getMeineBewertungen();
        // Daten an die View übergeben
        return view('meineBewertung', ['bewertungen' => $bewertungen]);
    }

    public function delete_bewertung()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['submitted'])) {
            $bewertungId = $_POST['bewertung_id'];
            if (deleteBewertung($bewertungId)) {
                echo "Bewertung erfolgreich gelöscht.";
            } else {
                echo "Bewertung konnte nicht gelöscht werden.";
            }
        }
        header('Location: /meinebewertungen');
        exit;
    }

    public function updateHervorheben()
    {
        $bewertungId = $_GET['bewertungID'];
        update_hervorheben($bewertungId);
        header("Location: /bewertung?gerichtid=".$_SESSION['gerichtid']);
        exit;
    }
}