CREATE TABLE Kayttaja(
  id SERIAL PRIMARY KEY,
  kayttajanimi varchar(20),
  etunimi varchar(20),
  sukunimi varchar(30),
  sposti varchar(50),
  salasana varchar(20),
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

CREATE TABLE Suosikit(
  kayttaja_id INTEGER REFERENCES Kayttaja(id),
  resepti_id INTEGER REFERENCES Resepti(id) ON DELETE CASCADE
);

CREATE TABLE Raaka_aineet(
  id INTEGER PRIMARY KEY NOT NULL,
  nimi varchar(100)
);


CREATE TABLE Ainekset(
  resepti_id INTEGER REFERENCES Resepti(id) ON DELETE CASCADE,
  raaka_aine_id INTEGER REFERENCES Raaka_aineet(id),
  raaka_aine_nimi varchar(50),
  mittayksikko varchar(50) NOT NULL,
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

CREATE TABLE yksikko_muunnokset(
  lyhenne varchar(11) PRIMARY KEY,
  kuvaus varchar(40)
);

CREATE TABLE yksikot(raaka_aine_id integer references raaka_aineet(id) on delete cascade,
 lyhenne varchar(11),
 kerroin numeric (7,3)
);
