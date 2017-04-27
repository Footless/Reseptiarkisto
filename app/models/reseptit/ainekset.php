<?php
class Ainekset extends BaseModel{
  public $resepti_id, $raaka_aine_id, $raaka_aine_nimi, $mittayksikko, $maara;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public function tallenna() {
    $query = DB::connection()->prepare('INSERT INTO Ainekset values (:resepti_id, :raaka_aine_id, :raaka_aine_nimi, :mittayksikko, :maara)');
    $query->execute(array('resepti_id' => $this->resepti_id, 'raaka_aine_id' => $this->raaka_aine_id, 'raaka_aine_nimi' => $this->raaka_aine_nimi, 'mittayksikko' => $this->mittayksikko, 'maara' => $this->maara));
  }

  public function etsi_raaka_aine() {
    $query = DB::connection()->prepare('SELECT id FROM Raaka_aineet WHERE nimi = :raaka_aine_nimi');
    $query->execute(array('raaka_aine_nimi' => $this->raaka_aine_nimi));
    $row = $query->fetch();
    if ($row) {
      $this->raaka_aine_id = $row['id'];
    } else {
      $this->raaka_aine_id = 0;
    }
    return $this->raaka_aine_id;
  }

  public static function poista($id) {
    $query = DB::connection()->prepare('DELETE FROM Ainekset WHERE resepti_id = :id');
    $query->execute(array('id' => $id));
  }
}
