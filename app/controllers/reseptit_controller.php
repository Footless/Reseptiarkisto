<?php

class ReseptitController extends BaseController{

  public static function index($i){
    $reseptit = Resepti::kaikki($i);
    View::make('resepti/reseptit.html', array('reseptit' => $reseptit));
  }

  public static function nayta($id) {
    $resepti = Resepti::nayta($id);
    $ohjeet = Resepti::ohjeet($id);
    $ainekset = Resepti::ainekset($id);
    View::make('resepti/resepti.html', array('resepti' => $resepti, 'ohjeet' => $ohjeet, 'ainekset' => $ainekset));
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
    $resepti_id = $resepti->lisaaResepti();
    $arrPituus = count($params['resepti_raaka_aineet']);
    $raaka_aineet = array();
    for ($i=0; $i < $arrPituus; $i++) {
      if ($params['resepti_raaka_aineet'][$i] == '' || $params['resepti_maarat'][$i] == '') {
        break;
      }
      $raaka_aineet[] = new Ainekset(array(
        'resepti_id' => $resepti_id,
        'raaka_aine_nimi' => $params['resepti_raaka_aineet'][$i],
        'mittayksikko' => $params['mitta_yksikko'][$i],
        'maara' => $params['resepti_maarat'][$i]
      ));
    }
    foreach ($raaka_aineet as $raaka_aine) {
      $raaka_aine->etsi_raaka_aine();
      $raaka_aine->tallenna();
    }

    Redirect::to('/resepti/omat-reseptit/' . $resepti->kayttaja_id);
  }

  public function haeYksikot() {
    $params = $_POST;
    $raaka_aine = new Raaka_aine(array(
      'nimi' => $params['nimi']
    ));

    $json = $raaka_aine->haeYksikot();
    if ($json) {
      echo $json;
    }
  }
}
