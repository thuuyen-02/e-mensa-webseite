USE emensawerbeseite;
CREATE TABLE Ersteller (
                           Ersteller_ID INT8 AUTO_INCREMENT PRIMARY KEY,
                           Name VARCHAR(80) DEFAULT 'anonym',
                           Email VARCHAR(80) NOT NULL
);

CREATE TABLE Wunschgericht (
                               Wunschgericht_ID INT AUTO_INCREMENT PRIMARY KEY,
                               Name VARCHAR(80) NOT NULL,
                               Beschreibung VARCHAR(800) NOT NULL,
                               Erstellungsdatum DATE NOT NULL,
                               Ersteller_ID INT8,
                               FOREIGN KEY (Ersteller_ID) REFERENCES Ersteller(Ersteller_ID)
);
