USE emensawerbeseite;

ALTER TABLE gericht_hat_kategorie
    ADD CONSTRAINT unique_gericht_kategorie UNIQUE (gericht_id, kategorie_id);

CREATE INDEX idc_gericht_name ON gericht(name);

ALTER TABLE gericht_hat_kategorie
    ADD CONSTRAINT fk_gericht_kategorie FOREIGN KEY (gericht_id) REFERENCES gericht(id) ON DELETE CASCADE ;

ALTER TABLE gericht_hat_allergen
    ADD CONSTRAINT fk_gericht_allergen FOREIGN KEY (gericht_id) REFERENCES gericht(id) ON DELETE CASCADE ;

ALTER TABLE gericht_hat_kategorie
    ADD CONSTRAINT fk_kategorie_besitzt_gericht FOREIGN KEY (kategorie_id) REFERENCES kategorie(id) ON DELETE RESTRICT ;

ALTER TABLE kategorie
    ADD CONSTRAINT fk_kategorie_eltern FOREIGN KEY (eltern_id) REFERENCES kategorie(id) ON DELETE RESTRICT ;

ALTER TABLE gericht_hat_allergen
    ADD CONSTRAINT fk_allergen_codeupdate FOREIGN KEY (code) REFERENCES allergen(code) ON UPDATE CASCADE ;

ALTER TABLE gericht_hat_kategorie
    ADD PRIMARY KEY (gericht_id, kategorie_id) ;
