<?php
class Raaka_aine extends BaseModel{
  public $id, $nimi, $energia, $rasva, $proteiini, $kuidut, $hiilarit, $maara;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public function haeYksikot() {
    $query = DB::connection()->prepare('SELECT id FROM Raaka_aineet WHERE nimi = :nimi LIMIT 1');
    $query->execute(array('nimi' => $this->nimi));
    $row = $query->fetch();
    if($row) {
      $query = DB::connection()->prepare('SELECT kuvaus FROM Yksikko_muunnokset AS ym, Yksikot AS y WHERE y.lyhenne = ym.lyhenne AND y.raaka_aine_id = :id');
      $query->execute(array('id' => $row['id']));
      $rows = $query->fetchAll();
      $rows[] = array('kuvaus' => 'gramma');
      return json_encode($rows);
    } else {
      return null;
    }
  }

  public function haeMakrot() {
    $query = DB::connection()->prepare('SELECT * FROM ravintoarvot WHERE raaka_aine_id = :id');
    $query->execute(array('id' => $this->id));
    $row = $query->fetch();
    if ($row) {
      $this->energia = $row['energia'] / 100;
      $this->rasva = $row['rasva'] / 100;
      $this->proteiini = $row['proteiini'] / 100;
      $this->kuidut = $row['kuidut'] / 100;
      $this->hiilarit = $row['hiilarit'] / 100;
    } else {
      $this->energia = 0;
      $this->rasva = 0;
      $this->proteiini = 0;
      $this->kuidut = 0;
      $this->hiilarit = 0;
    }
  }
}
