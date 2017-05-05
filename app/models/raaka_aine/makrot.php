<?php
class Makrot extends BaseModel{
  public $energia, $rasva, $proteiini, $kuidut, $hiilarit;

  public function __construct($attributes){
    parent::__construct($attributes);
  }
}
