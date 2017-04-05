<?php
class Kayttaja extends BaseModel{
  public $id, $kayttajanimi, $salasana, $admin;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function all() {
    $query = DB::connection()->prepare('SELECT * FROM Kayttaja ORDER BY id');
    $query->execute();
    $rows = $query->fetchAll();
    $kayttajat = array();

    foreach ($rows as $row) {
      $kayttajat[] = new Kayttaja(array(
        'id' => $row['id'],
        'kayttajanimi' => $row['kayttajanimi'],
        'salasana' => $row['salasana'],
        'admin' => $row['admin']
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
        'salasana' => $row['salasana'],
        'admin' => $row['admin']
      ));
      return $kayttaja;
    }
    return null;
  }

  public function save() {
    $query = DB::connection()->prepare('INSERT INTO Kayttaja (kayttajanimi, salasana) VALUES (:kayttajanimi, :salasana)');
    $query->execute(array('kayttajanimi' => $this->kayttajanimi, 'salasana' => $this->salasana));
  }

  public function edit() {
    $query = DB::connection()->prepare('UPDATE Kayttaja SET kayttajanimi = :kayttajanimi, admin = :admin WHERE id = :id');
    $query->execute(array('id' => $this->id, 'kayttajanimi' => $this->kayttajanimi, 'admin' => $this->admin));
  }
}
