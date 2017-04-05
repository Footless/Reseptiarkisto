INSERT INTO Kayttaja (kayttajanimi, salasana, admin) VALUES ('Kari', 'testi', TRUE);
INSERT INTO Kayttaja (kayttajanimi, salasana, admin) VALUES ('Esko', 'testi2', FALSE);
INSERT INTO Kayttaja (kayttajanimi, salasana, admin) VALUES ('Pekka', 'testi3', FALSE);
INSERT INTO Resepti (kayttaja_id, nimi, kategoria, lisatty, kuvaus, ohje, valm_aika, annoksia) VALUES (1, 'Lihapullat', 2, CURRENT_DATE, 'lihapullat sikanaudasta, on herkkua', '{"tee jotain", "tee lisää", "paista se"}', 60, 5);
INSERT INTO Resepti (kayttaja_id, nimi, kategoria, lisatty, kuvaus, ohje, valm_aika, annoksia) VALUES (1, 'Kaalikääryleet', 2, CURRENT_DATE, 'kaalia ja jauhelihaa', '{"kääri liha kaaliin", "paista se"}', 60, 5);
INSERT INTO Suosikit (kayttaja_id, resepti_id) VALUES (1,1);
INSERT INTO Ainekset (resepti_id, raaka_aine_id, mittayksikko, maara) VALUES (1, 1, 'gramma', 2.5);
