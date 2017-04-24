<?php
class Ainekset extends BaseModel{
  public $resepti_id, $raaka_aine_id, $raaka_aine_nimi, $mittayksikko, $maara;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public function tallenna($ainekset) {
    
  }
}
