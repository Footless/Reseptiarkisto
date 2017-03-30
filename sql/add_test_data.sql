INSERT INTO Kayttaja (kayttajanimi, salasana) VALUES ('Kari', 'testi');
INSERT INTO Kayttaja (kayttajanimi, salasana) VALUES ('Esko', 'testi2');
INSERT INTO Kayttaja (kayttajanimi, salasana) VALUES ('Pekka', 'testi3');
INSERT INTO Resepti (kayttaja_id, nimi, kategoria, kuvaus, ohje, valm_aika, annoksia) VALUES (1, 'Lihapullat', 2, 'lihapullat sikanaudasta, on herkkua', 'sekoita aineet, pyörittele pullat ja paista uunissa', 60, 5);
INSERT INTO Resepti (kayttaja_id, nimi, kategoria, kuvaus, ohje, valm_aika, annoksia) VALUES (1, 'Kaalikääryleet', 2, 'kaalia ja jauhelihaa', 'sekoita aineet, laita kaalin sisään ja kypsytä uunissa.', 60, 5);
INSERT INTO Suosikit (kayttaja_id, resepti_id) VALUES (1,1);
INSERT INTO Ainekset (resepti_id, raaka_aine_id, mittayksikko, maara) VALUES (1, 1, 'gramma', 2.5);
