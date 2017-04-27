<?php
class Kayttaja extends BaseModel{
  public $id, $kayttajanimi, $salasana, $admin, $delete, $etunimi, $sukunimi, $sposti;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function kaikki() {
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

  public static function etsi($id) {
    $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
    $query->execute(array('id' => $id));
    $row = $query->fetch();

    if($row){
      $kayttaja = new Kayttaja(array(
        'id' => $row['id'],
        'kayttajanimi' => $row['kayttajanimi'],
        'salasana' => $row['salasana'],
        'admin' => $row['admin'],
        'etunimi' => $row['etunimi'],
        'sukunimi' => $row['sukunimi'],
        'sposti' => $row['sposti']
      ));
      return $kayttaja;
    }
    return null;
  }

  public static function todenna($kayttajanimi, $salasana) {
    $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajanimi = :kayttajanimi AND salasana = :salasana LIMIT 1 ');
    $query->execute(array('kayttajanimi' => $kayttajanimi, 'salasana' => $salasana));
    $row = $query->fetch();
    if ($row) {
      $kayttaja = new Kayttaja(array(
        'id' => $row['id'],
        'kayttajanimi' => $row['kayttajanimi'],
        'etunimi' => $row['etunimi'],
        'admin' => $row['admin']
      ));
      return $kayttaja;
    } else {
      return null;
    }
  }

  public function tallenna() {
    $query = DB::connection()->prepare('INSERT INTO Kayttaja (kayttajanimi, etunimi, sukunimi, sposti, salasana, admin) VALUES (:kayttajanimi, :etunimi, :sukunimi, :sposti, :salasana, FALSE)');
    $query->execute(array('kayttajanimi' => $this->kayttajanimi, 'salasana' => $this->salasana, 'etunimi' => $this->etunimi, 'sukunimi' => $this->sukunimi, 'sposti' => $this->sposti));
  }

  public function muokkaa() {
    $query = DB::connection()->prepare('UPDATE Kayttaja SET kayttajanimi = :kayttajanimi, admin = :admin WHERE id = :id');
    $query->execute(array('id' => $this->id, 'kayttajanimi' => $this->kayttajanimi, 'admin' => $this->admin));
  }

  public function muokkaaYhta() {
    $query = DB::connection()->prepare('UPDATE Kayttaja SET kayttajanimi = :kayttajanimi, etunimi = :etunimi, sukunimi = :sukunimi, sposti = :sposti WHERE id = :id');
    $query->execute(array('id' => $this->id, 'kayttajanimi' => $this->kayttajanimi, 'etunimi' => $this->etunimi, 'sukunimi' => $this->sukunimi, 'sposti' => $this->sposti));
  }

  public function poista() {
    $query = DB::connection()->prepare('UPDATE Resepti SET kayttaja_id = 9  WHERE kayttaja_id = :id');
    $query->execute(array('id' => $this->id));
    $query = DB::connection()->prepare('DELETE FROM Kayttaja WHERE id = :id');
    $query->execute(array('id' => $this->id));
  }

  public function kirjauduUlos() {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
    );
    }
    session_destroy();
  }
}
