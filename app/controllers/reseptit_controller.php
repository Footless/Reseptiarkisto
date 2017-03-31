<?php

class ReseptitController extends BaseController{

  public static function index(){
    $reseptit = Resepti::all();
    View::make('reseptit.html', array('reseptit' => $reseptit));
  }

  public static function show($id) {
    $resepti = Resepti::show($id);
    View::make('resepti.html', array('resepti' => $resepti));
  }

  public static function getIngs() {
    $raaka_aineet = Resepti::getIngs();
    View::make('lisaa_resepti.html', array('raaka_aineet' => $raaka_aineet));
  }
}
