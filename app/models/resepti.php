<?php
class Resepti extends BaseModel{
  public $id, $kayttaja_id, $nimi, $kategoria, $kuvaus, $ohje, $valm_aika, $annoksia, $kayttajanimi;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function all() {
    $query = DB::connection()->prepare('SELECT Resepti.id as id, kayttaja_id, nimi, kategoria, kuvaus, ohje, valm_aika, annoksia, kayttajanimi FROM Resepti LEFT JOIN Kayttaja ON Kayttaja.id=Resepti.kayttaja_id');
    $query->execute();
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

  public static function show($id){
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

  public static function getIngs() {
    $query = DB::connection()->prepare('SELECT nimi FROM Raaka_aineet');
    $query->execute();
    $rows = $query->fetchAll();

    return $rows;
  }

  public function delete($id){
    $query = DB::connection()->prepare('DELETE FROM Resepti WHERE id = :id');
    $query->execute(array('id' => $id));
    return null;
  }
}