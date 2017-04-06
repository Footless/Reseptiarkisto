# Reseptiarkisto 

Yleisiä linkkejä:

* [Linkki sovellukseeni](https://vankari.users.cs.helsinki.fi/reseptiarkisto/)
* [Linkki dokumentaatiooni](https://github.com/Footless/Tsoha-Bootstrap/blob/master/doc/dokumentaatio.pdf)

Linkit yksittäisiin sivuihin:

* [Etusivu](http://vankari.users.cs.helsinki.fi/reseptiarkisto/)
* [Rekisteröidy](http://vankari.users.cs.helsinki.fi/reseptiarkisto/kayttajat/rekisteroidy/)
* [Reseptit](http://vankari.users.cs.helsinki.fi/reseptiarkisto/reseptit/)
* [Lisää resepti](http://vankari.users.cs.helsinki.fi/reseptiarkisto/lisaa-resepti/)
* [Käyttäjät](https://vankari.users.cs.helsinki.fi/reseptiarkisto/kayttajat/)

Admin-oikeudet: käyttäjätunnus: Kari salasana: testi

Ilman: käyttäjätunnus: Esko salasana: testi2

Sisään kirjaudutaan oikeasta yläkulmasta, samasta paikasta voi kirjautumisen jälkeen kirjautua ulos.
Admin oikeuksilla pääsee tarkastelemaan ja muokkaamaan käyttäjiä. Myös käyttäjän poistaminen onnistuu, mutta käyttäjää ei varsinaisesti poisteta taulusta, vaan ainoastaan kaikki tiedot id:tä lukuunottamatta poistetaan. Varsinainen tietokohteen poistaminen on toteutettu Resepti-luokkaan, reseptin sivulta reseptin voi poistaa joko reseptin tekijä tai ylläpitäjä. Reseptin muokkaus tai lisääminen ei vielä tässä vaiheessa kehitystä toimi.

Reseptin muokkaaminen tulee tapahtumaan reseptin omassa näkymässä, oikeus siihen on reseptin tekijällä ja ylläpidolla.

## Työn aihe

Työn aiheena on elektroninen reseptiarkisto. Arkistoon voi tallentaa omia reseptejään, sekä etsiä niitä aineosien ja asiasanojen perusteella. Sovellukseen tulee kirjautuminen, sovellusta voi käyttää useampi ihminen ja resepteistä voi valita ovatko ne julkisia vai kaikille näkyviä. Ainoastaan kirjautunut käyttäjä voi lisätä reseptejä, kirjautumaton käyttäjä voi ainoastaan selata julkisia reseptejä. Reseptiin voi liittää kuvan(/kuvia). Reseptiin voidaan kirjata myös ravintotietoja annoksesta.
