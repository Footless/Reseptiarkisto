<?php
class Resepti extends BaseModel{
  public $id, $kayttaja_id, $nimi, $kategoria, $kuvaus, $ohje, $valm_aika, $annoksia, $kayttajanimi, $kuva;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function kaikkiReseptit() {
    $query = DB::connection()->prepare('SELECT Resepti.id as id, kayttaja_id, nimi, kategoria, kuvaus, ohje, valm_aika, annoksia, kayttajanimi FROM Resepti LEFT JOIN Kayttaja ON Kayttaja.id=Resepti.kayttaja_id');
    $query->execute();
    $rows = $query->fetchAll();

    foreach ($rows as $row) {
      $reseptit[] = new Resepti(array(
        'id' => $row['id'],
        'kayttaja_id' => $row['kayttaja_id'],
        'nimi' => $row['nimi'],
        'kategoria' => $row['kategoria'],
        'kuvaus' => $row['kuvaus'],
        'ohje' => $row['ohje'],
        'valm_aika' => $row['valm_aika'],
        'annoksia' => $row['annoksia'],
        'kayttajanimi' => $row['kayttajanimi']
      ));

    }
    shuffle($reseptit);
    return $reseptit;
  }

  public static function uusimmatReseptit() {
    $query = DB::connection()->prepare('SELECT Resepti.id as id, nimi, kuvaus, kayttajanimi, lisatty FROM Resepti LEFT JOIN Kayttaja ON Kayttaja.id=Resepti.kayttaja_id ORDER BY lisatty DESC LIMIT 20');
    $query->execute();
    $rows = $query->fetchAll();

    foreach ($rows as $row) {
      $reseptit[] = new Resepti(array(
        'id' => $row['id'],
        'nimi' => $row['nimi'],
        'kuvaus' => $row['kuvaus'],
        'kayttajanimi' => $row['kayttajanimi']
      ));

    }
    return $reseptit;
  }

  public static function suosituimmat() {
    $query = DB::connection()->prepare('SELECT s.resepti_id, count(*) AS montako, r.nimi, r.kuvaus, k.kayttajanimi FROM suosikit s LEFT JOIN resepti r ON r.id = s.resepti_id LEFT JOIN kayttaja k ON k.id=r.kayttaja_id GROUP BY s.resepti_id, r.nimi, r.kuvaus, k.kayttajanimi ORDER BY montako DESC LIMIT 20');
    $query->execute();
    $rows = $query->fetchAll();

    foreach ($rows as $row) {
      $reseptit[] = new Resepti(array(
        'id' => $row['resepti_id'],
        'nimi' => $row['nimi'],
        'kuvaus' => $row['kuvaus'],
        'kayttajanimi' => $row['kayttajanimi']
      ));
    }
    return $reseptit;
  }

  public static function kaikki($i) {
    $query = DB::connection()->prepare('SELECT Resepti.id as id, kayttaja_id, nimi, kategoria, kuvaus, ohje, valm_aika, annoksia, kayttajanimi FROM Resepti LEFT JOIN Kayttaja ON Kayttaja.id=Resepti.kayttaja_id WHERE Resepti.kategoria= :kategoria');
    $query->execute(array('kategoria' => $i));
    $rows = $query->fetchAll();
    $reseptit = array();

    foreach ($rows as $row) {
      $reseptit[] = new Resepti(array(
        'id' => $row['id'],
        'kayttaja_id' => $row['kayttaja_id'],
        'nimi' => $row['nimi'],
        'kategoria' => $row['kategoria'],
        'kuvaus' => $row['kuvaus'],
        'ohje' => $row['ohje'],
        'valm_aika' => $row['valm_aika'],
        'annoksia' => $row['annoksia'],
        'kayttajanimi' => $row['kayttajanimi']
      ));

    }
    return $reseptit;
  }

  public static function nayta($id){
    $query = DB::connection()->prepare('SELECT Resepti.id as id, kayttaja_id, nimi, kategoria, kuvaus, valm_aika, annoksia, kayttajanimi FROM Resepti JOIN Kayttaja ON Kayttaja.id=Resepti.kayttaja_id WHERE Resepti.id= :id LIMIT 1');
    $query->execute(array('id' => $id));
    $row = $query->fetch();

    if($row) {
      $resepti = new Resepti(array(
        'id' => $row['id'],
        'kayttaja_id' => $row['kayttaja_id'],
        'nimi' => $row['nimi'],
        'kategoria' => $row['kategoria'],
        'kuvaus' => $row['kuvaus'],
        'valm_aika' => $row['valm_aika'],
        'annoksia' => $row['annoksia'],
        'kayttajanimi' => $row['kayttajanimi']
      ));

      return $resepti;
    }
    return null;
  }

  public static function ohjeet($id) {
    $query =DB::connection()->prepare('SELECT unnest(ohje) FROM Resepti WHERE id = :id');
    $query->execute(array('id' => $id));
    $rows = $query->fetchAll();

    if ($rows) {
      foreach($rows as $row) {
        $ohjeet[] = new Ohje(array(
          'ohje' => $row['unnest']
        ));
      }
      return $ohjeet;
    }
    return null;
    }

    public static function ainekset($id) {
      $query = DB::connection()->prepare('SELECT * FROM Ainekset WHERE resepti_id = :id');
      $query->execute(array('id' => $id));
      $rows = $query->fetchAll();
      $ainekset = array();

      if($rows) {
        foreach ($rows as $row) {
            $ainekset[] = new Ainekset(array(
              'resepti_id' => $row['resepti_id'],
              'raaka_aine_id' => $row['raaka_aine_id'],
              'raaka_aine_nimi' => $row['raaka_aine_nimi'],
              'mittayksikko' => $row['mittayksikko'],
              'maara' => $row['maara']
            ));
        }
        return $ainekset;
      }
      return null;
  }

  public static function haeMakrot($aines) {
    $raaka_aine = new Raaka_aine(array(
        'id' => $aines->raaka_aine_id,
        'nimi' => $aines->raaka_aine_nimi
      ));
    $raaka_aine->haeMakrot();

    return $raaka_aine;
  }

  public static function laskeRavintoarvot($id) {
    $raaka_aineet = array();
    $ainekset = self::ainekset($id);
    if ($ainekset) {
      foreach ($ainekset as $aines) {
        $raaka_aine = self::haeMakrot($aines);
        if ($aines->mittayksikko != 'gramma') {
          $query = DB::connection()->prepare('SELECT lyhenne FROM Yksikko_muunnokset WHERE kuvaus = :mittayksikko');
          $query->execute(array('mittayksikko' => $aines->mittayksikko));
          $row = $query->fetch();
          $lyhenne = $row['lyhenne'];
          $query = DB::connection()->prepare('SELECT kerroin FROM yksikot WHERE raaka_aine_id = :raaka_aine_id AND lyhenne = :mittayksikko');
          $query->execute(array('raaka_aine_id' => $aines->raaka_aine_id, 'mittayksikko' => $lyhenne));
          $row = $query->fetch();
          $kokonaismaara = $row['kerroin'] * $aines->maara;
        } else {
          $kokonaismaara = $aines->maara;
        }
        $raaka_aine->maara = $kokonaismaara;
        $raaka_aineet[] = $raaka_aine;
      }
    }

    $makrot = new Makrot(array(
        'energia' => 0,
        'rasva' => 0,
        'proteiini' => 0,
        'kuidut' => 0,
        'hiilarit' => 0
    ));
    if($raaka_aineet) {
      foreach ($raaka_aineet as $ra) {
        $makrot->energia = $makrot->energia + ($ra->maara * $ra->energia);
        $makrot->rasva = $makrot->rasva + ($ra->maara * $ra->rasva);
        $makrot->proteiini = $makrot->proteiini + ($ra->maara * $ra->proteiini);
        $makrot->kuidut = $makrot->kuidut + ($ra->maara * $ra->proteiini);
        $makrot->hiilarit = $makrot->hiilarit + ($ra->maara * $ra->hiilarit);
      }
    }

    return $makrot;
  }

  public static function haeRaaka_aineet() {
    $query = DB::connection()->prepare('SELECT nimi FROM Raaka_aineet');
    $query->execute();
    $rows = $query->fetchAll();

    return $rows;
  }

  public function poista($id){
    $query = DB::connection()->prepare('DELETE FROM Resepti WHERE id = :id RETURNING kayttaja_id');
    $query->execute(array('id' => $id));
    $row = $query->fetch();
    $this->kayttaja_id = $row['kayttaja_id'];
  }

  public static function naytaOmat($kid) {
    $query = DB::connection()->prepare('SELECT * FROM Resepti WHERE kayttaja_id= :kid');
    $query->execute(array('kid' => $kid));
    $rows = $query->fetchAll();
    $reseptit = array();

    foreach ($rows as $row) {
      $reseptit[] = new Resepti(array(
        'id' => $row['id'],
        'kayttaja_id' => $row['kayttaja_id'],
        'nimi' => $row['nimi'],
        'kategoria' => $row['kategoria'],
        'kuvaus' => $row['kuvaus'],
        'valm_aika' => $row['valm_aika'],
        'annoksia' => $row['annoksia'],
      ));

    }
    return $reseptit;
  }

  public function lisaaResepti() {
    $query = DB::connection()->prepare('INSERT INTO Resepti (kayttaja_id, nimi, kategoria, kuvaus, valm_aika, annoksia, lisatty) VALUES (:kayttaja_id, :nimi, :kategoria, :kuvaus, :valm_aika, :annoksia, CURRENT_DATE) RETURNING id');
    $query->execute(array('kayttaja_id' => $this->kayttaja_id, 'nimi' => $this->nimi, 'kategoria' => $this->kategoria, 'kuvaus' => $this->kuvaus, 'valm_aika' => $this->valm_aika, 'annoksia' => $this->annoksia));
    $row = $query->fetch();
    $this->id = $row['id'];
    $pgArray = self::to_pg_array($this->ohje);
    $query = DB::connection()->prepare('UPDATE Resepti SET ohje = :ohje WHERE id = :id');
    $query->execute(array('ohje' => $pgArray, 'id' => $this->id));
    return $this->id;
  }

  public function muokkaa($id) {
    $pgArray = self::to_pg_array($this->ohje);
    $query = DB::connection()->prepare('UPDATE Resepti SET nimi = :nimi, kategoria = :kategoria, kuvaus = :kuvaus, valm_aika = :valm_aika, annoksia = :annoksia, ohje = :ohje WHERE id = :id');
    $query->execute(array('nimi' => $this->nimi, 'kategoria' => $this->kategoria, 'kuvaus' => $this->kuvaus, 'valm_aika' => $this->valm_aika, 'annoksia' => $this->annoksia, 'id' => $id, 'ohje' => $pgArray));
  }

  public static function tallennaKuva($kuva, $id) {
    $query = DB::connection()->prepare('UPDATE Resepti SET kuva = :kuva WHERE id = :id');
    $query->execute(array('kuva' => $kuva, 'id' => $id));
  }

  /* Stack Overflow copypasta */

  public function to_pg_array($set) {
    settype($set, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($set as $t) {
        if (is_array($t)) {
            $result[] = to_pg_array($t);
        } else {
            $t = str_replace('"', '\\"', $t); // escape double quote
            if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' . $t . '"';
            $result[] = $t;
        }
    }
    return '{' . implode(",", $result) . '}'; // format
  }
}
