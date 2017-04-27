<?php
class Raaka_aine extends BaseModel{
  public $id, $nimi;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public function haeYksikot() {
    $query = DB::connection()->prepare('SELECT id FROM Raaka_aineet WHERE nimi = :nimi LIMIT 1');
    $query->execute(array('nimi' => $this->nimi));
    $row = $query->fetch();
    if($row) {
//      $this->id = $row['id'];
      $query = DB::connection()->prepare('SELECT kuvaus FROM Yksikko_muunnokset AS ym, Yksikot AS y WHERE y.lyhenne = ym.lyhenne AND y.raaka_aine_id = :id');
      $query->execute(array('id' => $row['id']));
      $rows = $query->fetchAll();
      $rows[] = array('kuvaus' => 'gramma');
      return json_encode($rows);
    } else {
      return null;
    }
  }

}
