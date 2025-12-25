USE emensawerbeseite;
CREATE TABLE benutzer (
    id INT8 AUTO_INCREMENT PRIMARY KEY ,
    name VARCHAR(200) NOT NULL ,
    email VARCHAR(100) NOT NULL UNIQUE ,
    passwort VARCHAR(200) NOT NULL ,
    admin BOOLEAN NOT NULL DEFAULT FALSE,
    anzahlfehler INT NOT NULL DEFAULT 0,
    anzahlanmeldungen INT NOT NULL DEFAULT 0,
    letzteanmeldung DATETIME,
    letzterfehler DATETIME
);