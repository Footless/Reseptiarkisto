CREATE TABLE Kayttaja(
  id SERIAL PRIMARY KEY,
  kayttajanimi varchar(20) NOT NULL,
  salasana varchar(20) NOT NULL
);

CREATE TABLE Resepti(
  id SERIAL PRIMARY KEY,
  kayttaja_id INTEGER REFERENCES Kayttaja(id),
  nimi varchar(50) NOT NULL,
  kategoria INTEGER NOT NULL,
  kuvaus varchar(200) NOT NULL,
  ohje varchar(2000) NOT NULL,
  valm_aika INTEGER NOT NULL,
  annoksia INTEGER NOT NULL
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
  maara decimal(4,3) NOT NULL
);

CREATE TABLE Ravintoarvot(
  raaka_aine_id INTEGER REFERENCES Raaka_aineet(id),
  energia decimal(7,6),
  proteiini decimal(7,6),
  hiilarit decimal(7,6),
  rasva decimal(7,6),
  kuidut decimal(7,6)
);
