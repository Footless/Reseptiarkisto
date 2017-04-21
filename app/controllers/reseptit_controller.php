<?php

class ReseptitController extends BaseController{

  public static function index($i){
    $reseptit = Resepti::kaikki($i);
    View::make('resepti/reseptit.html', array('reseptit' => $reseptit));
  }

  public static function nayta($id) {
    $resepti = Resepti::nayta($id);
    $ohjeet = Resepti::ohjeet($id);
    View::make('resepti/resepti.html', array('resepti' => $resepti, 'ohjeet' => $ohjeet));
  }

  public static function haeRaaka_aineet() {
    self::check_logged_in();
    $raaka_aineet = Resepti::haeRaaka_aineet();
    View::make('resepti/lisaa_resepti.html', array('raaka_aineet' => $raaka_aineet));
  }

  public static function muokkaa($id) {
    self::check_logged_in();
    $raaka_aineet = Resepti::haeRaaka_aineet();
    $resepti = Resepti::nayta($id);
    View::make('resepti/muokkaa-reseptia.html', array('raaka_aineet' => $raaka_aineet, 'resepti' => $resepti));
  }

  public static function poistonVarmistus($id) {
    self::check_logged_in();
    $resepti = Resepti::nayta($id);
    View::make('resepti/poista.html', array('resepti' => $resepti));
  }

  public static function poista($id) {
    self::check_logged_in();
    $params = $_POST;
    $resepti = new Resepti(array(
      'id' => $params['id']
    ));

    $resepti->poista($id);

    Redirect::to('/resepti/omat-reseptit/' . $resepti->kayttaja_id, array('message' => 'Resepti poistettu'));
  }

  public static function naytaOmat($id) {
    self::check_logged_in();
    $reseptit = Resepti::naytaOmat($id);
    View::make('resepti/omat_reseptit.html', array('reseptit' => $reseptit));
  }

  public function lisaaResepti() {
    self::check_logged_in();
    $params = $_POST;
    $resepti = new Resepti(array(
      'kayttaja_id' => $params['kayttaja_id'],
      'nimi' => $params['nimi'],
      'kategoria' => $params['kategoria'],
      'kuvaus' => $params['kuvaus'],
      'ohje' => $params['ohjeet'],
      'valm_aika' => $params['valm_aika'],
      'annoksia' => $params['annoksia'],
    ));
    $id = $resepti->lisaaResepti();
    Redirect::to('/resepti/omat-reseptit/' . $resepti->kayttaja_id);
  }
}
