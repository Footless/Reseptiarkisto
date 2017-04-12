<?php

class ReseptitController extends BaseController{

  public static function index($i){
    $reseptit = Resepti::all($i);
    View::make('resepti/reseptit.html', array('reseptit' => $reseptit));
  }

  public static function show($id) {
    $resepti = Resepti::show($id);
    $ohjeet = Resepti::ohjeet($id);
    View::make('resepti/resepti.html', array('resepti' => $resepti, 'ohjeet' => $ohjeet));
  }

  public static function getIngs() {
    $raaka_aineet = Resepti::getIngs();
    View::make('resepti/lisaa_resepti.html', array('raaka_aineet' => $raaka_aineet));
  }

  public static function edit($id) {
    $raaka_aineet = Resepti::getIngs();
    $resepti = Resepti::show($id);
    View::make('resepti/muokkaa-reseptia.html', array('raaka_aineet' => $raaka_aineet, 'resepti' => $resepti));
  }

  public static function deleteConfirm($id) {
    $resepti = Resepti::show($id);
    View::make('resepti/poista.html', array('resepti' => $resepti));
  }

  public static function delete($id) {
    $params = $_POST;
    $resepti = new Resepti(array(
      'id' => $params['id']
    ));

    $resepti->delete($id);

    Redirect::to('/reseptit/', array('message' => 'Resepti poistettu'));
  }

  public static function showPersonal($id) {
    $reseptit = Resepti::showPersonal($id);
    View::make('resepti/omat_reseptit.html', array('reseptit' => $reseptit));
  }
}
