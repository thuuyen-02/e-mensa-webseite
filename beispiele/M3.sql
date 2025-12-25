#A2_2
CREATE SCHEMA emensawerbeseite;

use emensawerbeseite;
CREATE TABLE gericht(
                        PRIMARY KEY (id),
                        CONSTRAINT preis CHECK(preis_intern <= preis_extern),
                        id int8,
                        name VARCHAR(80) NOT NULL UNIQUE,
                        beschreibung VARCHAR(800) NOT NULL,
                        erfasst_am DATE NOT NULL,
                        vegetarisch BOOLEAN NOT NULL DEFAULT (false),
                        vegan BOOLEAN NOT NULL DEFAULT (false),
                        preis_intern DOUBLE NOT NULL CHECK( preis_intern > 0),
                        preis_extern DOUBLE NOT NULL
);

CREATE TABLE allergen(
                         PRIMARY KEY (code),
                         code CHAR(4),
                         name VARCHAR(300) NOT NULL,
                         typ VARCHAR(20) NOT NULL DEFAULT 'allergen'
);

CREATE TABLE kategorie(
                          PRIMARY KEY (id),
                          FOREIGN KEY (eltern_id) REFERENCES kategorie(id),
                          id INT8,
                          name VARCHAR(80) NOT NULL,
                          eltern_id INT8,
                          bildname VARCHAR(200)
);

CREATE TABLE gericht_hat_allergen(
                                     FOREIGN KEY (code) REFERENCES allergen(code),
                                     FOREIGN KEY (gericht_id) REFERENCES gericht(id),
                                     code CHAR(4),
                                     gericht_id INT8 NOT NULL
);

CREATE TABLE gericht_hat_kategorie(
                                      FOREIGN KEY (gericht_id) REFERENCES gericht(id),
                                      FOREIGN KEY (kategorie_id) REFERENCES kategorie(id),
                                      gericht_id INT8 NOT NULL,
                                      kategorie_id INT8 NOT NULL
);


#A2_4
USE emensawerbeseite;
SHOW TABLES;
SELECT COUNT(*) AS Anzahl FROM allergen;
SELECT COUNT(*) AS Anzahl FROM gericht;
SELECT COUNT(*) AS Anzahl FROM gericht_hat_allergen;
SELECT COUNT(*) AS Anzahl FROM kategorie;
SELECT COUNT(*) AS Anzahl FROM gericht_hat_kategorie;


#A3
SELECT * FROM gericht;
SELECT erfasst_am, name FROM gericht;
SELECT erfasst_am, name as Gerichtname FROM gericht ORDER BY (name) DESC;
SELECT name, beschreibung FROM gericht ORDER BY name ASC LIMIT 5;
SELECT name, beschreibung FROM gericht ORDER BY name LIMIT 10 OFFSET 5;
SELECT DISTINCT typ FROM allergen;
SELECT name FROM gericht WHERE name LIKE 'L%';
SELECT id, name FROM gericht WHERE name LIKE '%suppe%' ORDER BY name DESC;
SELECT * FROM kategorie WHERE eltern_id IS NULL;
UPDATE allergen SET name = 'Kamut' WHERE name = 'Dinkel' AND code = 'a6';
INSERT INTO gericht VALUES (21, 'Currywurst mit Pommes', 'Geschnittene Bratwurst mit Currysoße und Pommes', CURRENT_DATE ,0,0,3.5,6);


#A6
SELECT gericht.id, gericht.name, allergen.code, allergen.name
FROM gericht
    LEFT JOIN gericht_hat_allergen ON gericht.id = gericht_hat_allergen.gericht_id
    LEFT JOIN allergen ON gericht_hat_allergen.code = allergen.code
WHERE allergen.name IS NOT NULL;

SELECT gericht.id, gericht.name, allergen.code, allergen.name
FROM gericht
    LEFT JOIN gericht_hat_allergen ON gericht.id = gericht_hat_allergen.gericht_id
    LEFT JOIN allergen ON gericht_hat_allergen.code = allergen.code;

SELECT gericht.name, allergen.name
FROM gericht
    RIGHT JOIN gericht_hat_allergen ON gericht.id = gericht_hat_allergen.gericht_id
    RIGHT JOIN allergen ON gericht_hat_allergen.code = allergen.code;

SELECT k.name, COUNT(ghk.gericht_id) AS Anzahl_Gericht
FROM kategorie k
         LEFT JOIN gericht_hat_kategorie ghk ON k.id = ghk.kategorie_id
GROUP BY k.name
ORDER BY Anzahl_Gericht ASC;

SELECT kategorie.name, COUNT(gericht.name) AS anzahl
FROM gericht
    JOIN gericht_hat_kategorie ON gericht.id = gericht_hat_kategorie.gericht_id
    JOIN kategorie ON gericht_hat_kategorie.kategorie_id = kategorie.id
GROUP BY kategorie.name
HAVING anzahl > 2
ORDER BY anzahl ASC;

#A7
ALTER TABLE gericht
    ADD CONSTRAINT chk_preisintern CHECK ( preis_intern > 0 );

ALTER TABLE allergen
    ADD CONSTRAINT unique_allergen_name UNIQUE (name);

ALTER TABLE kategorie
    ADD CONSTRAINT unique_kategorie_name UNIQUE (name);

ALTER TABLE gericht_hat_allergen
    ADD CONSTRAINT gericht_hat_allergen_gericht_gericht_id_fk FOREIGN KEY (gericht_id) REFERENCES gericht(id)
        ON DELETE CASCADE ON UPDATE CASCADE ,
    ADD CONSTRAINT gericht_hat_allergen_allergen_code_fk FOREIGN KEY (code) REFERENCES allergen(code)
        ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE gericht_hat_kategorie
    ADD CONSTRAINT gericht_hat_kategorie_gericht_gericht_id_fk FOREIGN KEY (gericht_id) REFERENCES gericht(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT gericht_hat_kategorie_kategorie_kategorie_id_fk FOREIGN KEY (kategorie_id) REFERENCES kategorie(id)
        ON DELETE CASCADE ON UPDATE CASCADE;

-- Tabelle zur Speicherung der IP-Adressen und des Datums der Besuche
CREATE TABLE besucher_log (
                              id INT AUTO_INCREMENT PRIMARY KEY, -- Eindeutige ID für jeden Eintrag
                              ip VARCHAR(45) NOT NULL,           -- IP-Adresse des Besuchers (IPv4/IPv6)
                              datum DATE NOT NULL                -- Datum des Besuchs
);

-- Tabelle für den Besucherzähler
CREATE TABLE besucher_zaehler (
                                  ip INT PRIMARY KEY,                -- Fester Primärschlüssel (z. B. 1, da immer nur eine Zeile)
                                  zaehler INT NOT NULL DEFAULT 0     -- Zähler für die Besucheranzahl
);

-- Initialer Eintrag in die Tabelle besucher_zaehler, falls notwendig
INSERT INTO besucher_zaehler (ip, zaehler) VALUES (1, 0);

