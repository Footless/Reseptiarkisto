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
        self::lataaKuva($_FILES['kuva']['tmp_name']);
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
      echo pg_unescape_bytea($kuva);
      //echo base64_decode($kuva);
      //fpassthru($kuva);
    } else {
      echo "joku menee vituiksi";
    }
  }

  private static function lataaKuva($kuva) {
//    $tyyppi = exif_imagetype($kuva);
//    if ($tyyppi == 1 || $tyyppi == 2 || $tyyppi == 3) {
//      $kuva = file_get_contents($_FILES['kuva']['tmp_name']);
//      $kuvaString = pg_escape_bytea($kuva);
//      $resepti->tallennaKuva($kuvaString, $resepti_id);
//    }
    $target_dir = "assets/img/";
    $target_file = $target_dir . basename($kuva);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    if (file_exists($target_file)) {
      $uploadOk = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
      $uploadOk = 0;
    }
    if ($uploadOk == 1) {
      if (move_uploaded_file($kuva, $target_file)) {
        echo "success";
      }
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
    $v->rule('numeric', array('valm_aika', 'annoksia'))->message('{field} saa sisältää ainostaan kirjaimia ja numeroita.');
    if ($v->validate()) {
      return null;
    } else {
      return $v->errors();
    }
  }
}
