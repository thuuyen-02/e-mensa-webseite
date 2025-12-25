use emensawerbeseite;

CREATE VIEW view_suppengerichte AS
SELECT * FROM gericht
WHERE name LIKE '%suppe%';

CREATE VIEW view_anmeldungen AS
SELECT id, anzahlanmeldungen
FROM benutzer
GROUP BY id
ORDER BY anzahlanmeldungen DESC;

CREATE VIEW view_kategoriegerichte_vegetarisch AS
SELECT kategorie.name AS Kategorie, GROUP_CONCAT(gericht.name) AS Gericht FROM gericht
                                                                                   LEFT JOIN gericht_hat_kategorie ON gericht.id = gericht_hat_kategorie.gericht_id
                                                                                   RIGHT JOIN kategorie ON gericht_hat_kategorie.kategorie_id = kategorie.id
                                                                          GROUP BY kategorie.id
UNION
SELECT kategorie.name AS Kategorie, GROUP_CONCAT(gericht.name) AS Gericht FROM gericht
                                                                                   LEFT JOIN gericht_hat_kategorie ON gericht.id = gericht_hat_kategorie.gericht_id
                                                                                   LEFT JOIN kategorie ON kategorie.id = gericht_hat_kategorie.kategorie_id
WHERE kategorie.name IS NULL AND vegetarisch GROUP BY kategorie.name;
