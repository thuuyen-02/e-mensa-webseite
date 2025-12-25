USE emensawerbeseite;
CREATE TABLE Bewertungen (
                             BewertungID INT8 AUTO_INCREMENT PRIMARY KEY,
                             BenutzerID INT8 NOT NULL,
                             GerichtID INT8 NOT NULL,
                             Bemerkung TEXT NOT NULL CHECK (LENGTH(Bemerkung) >= 5),
                             SterneBewertung ENUM('sehr gut', 'gut', 'schlecht', 'sehr schlecht') NOT NULL,
                             Bewertungszeitpunkt DATETIME,
                             Hervorgehoben BOOLEAN DEFAULT FALSE,

                             CONSTRAINT fk_benutzer FOREIGN KEY (BenutzerID) REFERENCES benutzer(ID) ON DELETE CASCADE,
                             CONSTRAINT fk_gericht FOREIGN KEY (GerichtID) REFERENCES gericht(ID) ON DELETE CASCADE
);

CREATE PROCEDURE getBildnameVonID (IN gericht_id INT)
BEGIN
    SELECT bildname
    FROM gericht
    WHERE id = gericht_id;
END;

CREATE PROCEDURE showBewertungen()
BEGIN
    SELECT
           b.BewertungID,
           b.Bemerkung,
           b.SterneBewertung,
           b.Bewertungszeitpunkt,
           b.Hervorgehoben,
           g.name AS gericht_name,
           g.bildname
    FROM bewertungen b
    JOIN gericht g ON b.GerichtID = g.id
    ORDER BY b.Bewertungszeitpunkt DESC
    LIMIT 30;
END;

CREATE PROCEDURE showUserBewertungen(IN user_id INT)
BEGIN
    SELECT b.BewertungID,
           b.Bemerkung,
           b.SterneBewertung,
           b.Bewertungszeitpunkt,
           g.name AS gericht_name,
           g.bildname
    FROM bewertungen b
    JOIN gericht g ON b.GerichtID = g.id
    WHERE b.BenutzerID = user_id
    ORDER BY b.Bewertungszeitpunkt DESC;
END;

INSERT INTO benutzer (name, email, passwort, admin)
VALUES ('Benutzer','benutzer@emensa.example','$2y$10$/R0dn18YH23uEI8DKVirXuuptZlTbpaUdYnlDLVhnXJ4oDv02mbQW',FALSE );

CREATE PROCEDURE showHervorgehobeneBewertungen()
BEGIN
    SELECT
        b.Bemerkung,
        b.SterneBewertung,
        b.Bewertungszeitpunkt,
        g.name AS gericht_name,
        g.bildname
    FROM bewertungen b
    JOIN gericht g ON b.GerichtID = g.id
    WHERE b.Hervorgehoben = TRUE
    ORDER BY b.Bewertungszeitpunkt DESC;
END;