INSERT INTO Kayttaja (kayttajanimi, salasana) VALUES ('Kari', 'testi');
INSERT INTO Resepti (kayttaja_id, nimi, kategoria, kuvaus, ohje, valm_aika, annoksia) VALUES (1, 'lihapullat', 2, 'lihapullat sikanaudsta, on herkkua', 'sekoita aineet, pyörittele pullat ja paista uunissa', 60, 5);
INSERT INTO Raaka_aineet (id, nimi) VALUES (1, 'jauheliha');
INSERT INTO Suosikit (kayttaja_id, resepti_id) VALUES (1,1);
INSERT INTO Ainekset (resepti_id, raaka_aine_id, mittayksikko, maara) VALUES (1, 1, 'gramma', 2.5);
INSERT INTO Ravintoarvot (raaka_aine_id, energia, proteiini, hiilarit, rasva, kuidut) VALUES (1, 1.2, 3.4, 6.3, 8.4, 9.2);
