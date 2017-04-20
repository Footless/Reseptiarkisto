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
    self::check_logged_in();
    $raaka_aineet = Resepti::getIngs();
    View::make('resepti/lisaa_resepti.html', array('raaka_aineet' => $raaka_aineet));
  }

  public static function edit($id) {
    self::check_logged_in();
    $raaka_aineet = Resepti::getIngs();
    $resepti = Resepti::show($id);
    View::make('resepti/muokkaa-reseptia.html', array('raaka_aineet' => $raaka_aineet, 'resepti' => $resepti));
  }

  public static function deleteConfirm($id) {
    self::check_logged_in();
    $resepti = Resepti::show($id);
    View::make('resepti/poista.html', array('resepti' => $resepti));
  }

  public static function delete($id) {
    self::check_logged_in();
    $params = $_POST;
    $resepti = new Resepti(array(
      'id' => $params['id']
    ));

    $resepti->delete($id);

    Redirect::to('/resepti/omat-reseptit/' . $resepti->kayttaja_id, array('message' => 'Resepti poistettu'));
  }

  public static function showPersonal($id) {
    self::check_logged_in();
    $reseptit = Resepti::showPersonal($id);
    View::make('resepti/omat_reseptit.html', array('reseptit' => $reseptit));
  }

  public function addRecipe() {
    self::check_logged_in();
    $params = $_POST;
    $resepti = new Resepti(array(
      'kayttaja_id' => $params['kayttaja_id'],
      'nimi' => $params['nimi'],
      'kategoria' => $params['kategoria'],
      'kuvaus' => $params['kuvaus'],
      'ohje[]' => $params['ohjeet'],
      'valm_aika' => $params['valm_aika'],
      'annoksia' => $params['annoksia'],
    ));
    $resepti->addRecipe();
    Redirect::to('/resepti/omat-reseptit/' . $resepti->kayttaja_id);
  }
}
