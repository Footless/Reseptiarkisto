<?php
class Resepti extends BaseModel{
  public $id, $nimi;

  public function __construct($attributes){
    parent::__construct($attributes);
  }
}
