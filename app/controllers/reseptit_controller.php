<?php

class ReseptitController extends BaseController{

  public static function index($i){
    $reseptit = Resepti::kaikki($i);
    $user = self::get_user_logged_in();
    if ($user) {
        $suosikit = Kayttaja::haeSuosikit($user->id);
    } else {
      $suosikit = null;
    }
    View::make('resepti/reseptit.html', array('reseptit' => $reseptit, 'suosikit' => $suosikit));
  }

  public static function nayta($id) {
    $resepti = Resepti::nayta($id);
    $ohjeet = Resepti::ohjeet($id);
    $ainekset = Resepti::ainekset($id);
    $ravintoarvot = Resepti::laskeRavintoarvot($id);
    View::make('resepti/resepti.html', array('resepti' => $resepti, 'ohjeet' => $ohjeet, 'ainekset' => $ainekset, 'ravintoarvot' => $ravintoarvot));
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
    $ohjeet = Resepti::ohjeet($id);
    $ainekset = Resepti::ainekset($id);

    View::make('resepti/muokkaa-reseptia.html', array('raaka_aineet' => $raaka_aineet, 'resepti' => $resepti, 'ohjeet' => $ohjeet, 'ainekset' => $ainekset));
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
    $user = self::get_user_logged_in();
    if ($user) {
        $suosikit = Kayttaja::haeSuosikit($user->id);
    } else {
      $suosikit = null;
    }
    View::make('resepti/omat_reseptit.html', array('reseptit' => $reseptit, 'suosikit' => $suosikit));
  }

  public function lisaaResepti() {
    self::check_logged_in();
    $params = $_POST;
    $errors = self::validoi($params);
    $arrPituus = count($params['resepti_raaka_aineet']);
    $raaka_aineet = array();
    for ($i=0; $i < $arrPituus; $i++) {
      if ($params['resepti_raaka_aineet'][$i] == '' || $params['resepti_maarat'][$i] == '') {
        break;
      }
      $raaka_aineet[] = new Ainekset(array(
        'raaka_aine_nimi' => $params['resepti_raaka_aineet'][$i],
        'mittayksikko' => $params['mitta_yksikko'][$i],
        'maara' => $params['resepti_maarat'][$i]
      ));
    }
    $syote = array(
      'nimi' => $params['nimi'],
      'kuvaus' => $params['kuvaus'],
      'valm_aika' => $params['valm_aika'],
      'annoksia' => $params['annoksia'],
      'raaka_aineet' => $raaka_aineet,
      'ohjeet' => $params['ohjeet']
    );
    if (!$errors) {
      $resepti = new Resepti(array(
        'kayttaja_id' => $params['kayttaja_id'],
        'nimi' => $params['nimi'],
        'kategoria' => $params['kategoria'],
        'kuvaus' => $params['kuvaus'],
        'ohje' => $params['ohjeet'],
        'valm_aika' => $params['valm_aika'],
        'annoksia' => $params['annoksia']
      ));
      $resepti_id = $resepti->lisaaResepti();
      if ($_FILES['kuva']['tmp_name']) {
        $file_raw = file_get_contents($_FILES['kuva']['tmp_name']);
        //$kuva = pg_escape_bytea($file_raw);
        $kuva = pg_escape_bytea($_FILES['kuva']['name']);
        $resepti->tallennaKuva($kuva, $resepti_id);
        //self::lataaKuva($_FILES['kuva']['tmp_name']);
      }
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
    } else {
      View::make('resepti/lisaa_resepti.html', array('errors' => $errors, 'syote' => $syote));
    }

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

  public function tallennaMuokkaukset($id) {
    self::check_logged_in();
    $params = $_POST;
    $errors = self::validoi($params);
    if (!$errors) {
      $resepti = new Resepti(array(
        'kayttaja_id' => $params['kayttaja_id'],
        'nimi' => $params['nimi'],
        'kategoria' => $params['kategoria'],
        'kuvaus' => $params['kuvaus'],
        'ohje' => $params['ohjeet'],
        'valm_aika' => $params['valm_aika'],
        'annoksia' => $params['annoksia'],
      ));
      $resepti_id = $resepti->muokkaa($id);
      $arrPituus = count($params['resepti_raaka_aineet']);
      $raaka_aineet = array();
      for ($i=0; $i < $arrPituus; $i++) {
        if ($params['resepti_raaka_aineet'][$i] == '' || $params['resepti_maarat'][$i] == '') {
          break;
        }
        $raaka_aineet[] = new Ainekset(array(
          'resepti_id' => $id,
          'raaka_aine_nimi' => $params['resepti_raaka_aineet'][$i],
          'mittayksikko' => $params['mitta_yksikko'][$i],
          'maara' => $params['resepti_maarat'][$i]
        ));
      }
      Ainekset::poista($id);
      foreach ($raaka_aineet as $raaka_aine) {
        $raaka_aine->etsi_raaka_aine();
        $raaka_aine->tallenna();
      }

      Redirect::to('/resepti/omat-reseptit/' . $resepti->kayttaja_id);
    } else {
      Redirect::to('/resepti/lisaa-resepti/', array('errors' => $errors, 'syote' => $syote));
    }

  }

  public static function naytaKuva($id) {
    $query = DB::connection()->prepare('SELECT kuva FROM Resepti WHERE id = :id LIMIT 1');
    $query->execute(array('id' => $id));
    $row = $query->fetch();
    if ($row) {
      $kuva = $row['kuva'];

      header("Content-type: image/jpeg");
      //echo pg_unescape_bytea($row['kuva']);
      fpassthru($row['kuva']);
    } else {
      echo "joku menee vituiksi";
    }
  }

  public function validoi($params) {
    $v = new Valitron\Validator($params);
    $v->rule('required', array('nimi', 'kuvaus', 'valm_aika', 'annoksia', 'ohjeet'))->message('{field} vaaditaan!');
    $v->labels(array(
      'nimi' => 'Annoksen nimi',
      'kuvaus' => 'Annoksen kuvaus',
      'valm_aika' => 'Valmistusaika',
      'annoksia' => 'Annosten määrä',
      'ohjeet' => 'Valmistusohjeet'
    ));
    $v->rule('regex', array('nimi', 'kuvaus'), "/^[a-zA-Z\s,.'-\pL]+$/u")->message('{field} saa sisältää ainoastaan kirjaimia.');
    $v->rule('numeric', array('valm_aika', 'annoksia'))->message('{field} saa sisältää ainostaan numeroita.');
    if ($v->validate()) {
      return null;
    } else {
      return $v->errors();
    }
  }
}
