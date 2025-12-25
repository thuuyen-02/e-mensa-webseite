use emensawerbeseite;

/*Prozedur für erfolgreiche Anmeldung*/
CREATE PROCEDURE erfolgreich_anmeldung (IN benutzer_id INT8 )
BEGIN
    UPDATE benutzer
    SET anzahlfehler = 0,
        anzahlanmeldungen = anzahlanmeldungen + 1,
        letzteanmeldung = NOW()
    WHERE id = benutzer_id;
END;

/*Prozedur für fehlgeschlagene Anmeldung*/
CREATE PROCEDURE fehler_anmeldung (IN benutzer_id INT8 )
BEGIN
    UPDATE benutzer
    SET anzahlfehler = anzahlfehler + 1,
        letzterfehler = NOW()
    WHERE id = benutzer_id;
END;

/*Prozedur für sortierte Gerichte mit Allergene*/
CREATE PROCEDURE sortierGerichtmitAllergen (IN asc_sortiert BOOLEAN)
BEGIN
    IF asc_sortiert THEN
        SELECT g.name AS gericht_name, g.preis_intern, g.preis_extern,
               GROUP_CONCAT(a.code SEPARATOR ', ') AS allergene, g.bildname
        FROM gericht g
                 LEFT JOIN gericht_hat_allergen gha ON g.id = gha.gericht_id
                 LEFT JOIN allergen a ON gha.code = a.code
        GROUP BY g.id, g.name, g.preis_intern, g.preis_extern
        ORDER BY g.name ASC
        LIMIT 5;
    ELSE
        SELECT g.name AS gericht_name, g.preis_intern, g.preis_extern,
               GROUP_CONCAT(a.code SEPARATOR ', ') AS allergene, g.bildname
        FROM gericht g
                 LEFT JOIN gericht_hat_allergen gha ON g.id = gha.gericht_id
                 LEFT JOIN allergen a ON gha.code = a.code
        GROUP BY g.id, g.name, g.preis_intern, g.preis_extern
        ORDER BY g.name DESC
        LIMIT 5;
    END IF;
END;