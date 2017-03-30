<?php
class Kayttaja extends BaseModel{
  public $id, $kayttajanimi, $salasana;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function all() {
    $query = DB::connection()->prepare('SELECT * FROM Kayttaja');
    $query->execute();
    $rows = $query->fetchAll();
    $kayttajat = array();

    foreach ($rows as $row) {
      $kayttajat[] = new Kayttaja(array(
        'id' => $row['id'],
        'kayttajanimi' => $row['kayttajanimi'],
        'salasana' => $row['salasana']
      ));
    }
    return $kayttajat;
  }

  public static function find($id) {
    $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
    $query->execute(array('id' => $id));
    $row = $query->fetch();

    if($row){
      $kayttaja = new Kayttaja(array(
        'id' => $row['id'],
        'kayttajanimi' => $row['kayttajanimi'],
        'salasana' => $row['salasana']
      ));
      return kayttaja;
    }
    return null;
  }

  public function save() {
    $query = DB::connection()->prepare('INSERT INTO Kayttaja (kayttajanimi, salasana) VALUES (:kayttajanimi, :salasana)');
    $query->execute(array('kayttajanimi' => $this->kayttajanimi, 'salasana' => $this->salasana));
  }
}
