CREATE TABLE Kayttaja(
  id SERIAL PRIMARY KEY,
  kayttajanimi varchar(20) NOT NULL,
  salasana varchar(20) NOT NULL,
  admin boolean NOT NULL
);

CREATE TABLE Resepti(
  id SERIAL PRIMARY KEY,
  kayttaja_id INTEGER REFERENCES Kayttaja(id),
  nimi varchar(50) NOT NULL,
  kuva varchar(100),
  kategoria INTEGER NOT NULL,
  lisatty DATE NOT NULL,
  kuvaus varchar(200) NOT NULL,
  ohje TEXT[],
  valm_aika INTEGER NOT NULL,
  annoksia INTEGER NOT NULL
);

CREATE TABLE Ohje(
  resepti_id INTEGER REFERENCES Resepti(id),
  ohje_alanumero INTEGER NOT NULL,
  ohje VARCHAR(1000)
);

CREATE TABLE Suosikit(
  kayttaja_id INTEGER REFERENCES Kayttaja(id),
  resepti_id INTEGER REFERENCES Resepti(id)
);

CREATE TABLE Raaka_aineet(
  id INTEGER PRIMARY KEY NOT NULL,
  nimi varchar(100)
);


CREATE TABLE Ainekset(
  resepti_id INTEGER REFERENCES Resepti(id),
  raaka_aine_id INTEGER REFERENCES Raaka_aineet(id),
  raaka_aine_nimi varchar(50),
  mittayksikko varchar(15) NOT NULL,
  maara decimal(8,3) NOT NULL
);

CREATE TABLE Ravintoarvot(
  raaka_aine_id INTEGER REFERENCES Raaka_aineet(id),
  energia decimal(12,7),
  proteiini decimal(11,7),
  hiilarit decimal(11,7),
  rasva decimal(11,7),
  kuidut decimal(11,7)
);
