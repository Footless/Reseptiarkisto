<?php

  class KayttajatController extends BaseController{

    public static function index(){
      $reseptit = Resepti::kaikkiReseptit();
      $suosituimmat = Resepti::suosituimmat();
      $uusimmat = Resepti::uusimmatReseptit();
      $user = self::get_user_logged_in();
      if ($user) {
          $suosikit = Kayttaja::haeSuosikit($user->id);
      } else {
        $suosikit = null;
      }
   	  View::make('home.html', array('reseptit' => $reseptit, 'suosikit' => $suosikit, 'suosituimmat' => $suosituimmat, 'uusimmat' => $uusimmat));
    }

    public static function rekisteroidy(){
      View::make('kayttaja/rekisteroidy.html');
    }

    public function kirjaudu() {
      $params = $_POST;

      $kayttaja = Kayttaja::todenna($params['kayttajanimi'], $params['salasana']);

      if (!$kayttaja) {
        Redirect::to('/', array('error' => 'Väärä salasana tai käyttäjätunnus', 'username' => $params['kayttajanimi']));
      }else{
        $_SESSION['user'] = $kayttaja->id;
        Redirect::to('/', array('message' => 'Tervetuloa takaisin '. $kayttaja->etunimi . '!'));
      }
    }

    public static function tallenna(){
      $params = $_POST;
      $syote = array(
        'kayttajanimi' => $params['kayttajanimi'],
        'etunimi' => $params['etunimi'],
        'sukunimi' => $params['sukunimi'],
        'sposti' => $params['sposti']
      );
      $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajanimi = :kayttajanimi LIMIT 1');
      $query->execute(array('kayttajanimi' => $params['kayttajanimi']));
      $row = $query->fetch();
      if ($row) {
        Redirect::to('/kayttajat/rekisteroidy/', array('message' => 'Rekisteröityminen epäonnistui! Valitsemasi käyttäjänimi on jo käytössä.', 'syote' => $syote));
      }
      $errors = self::validoi($params);
      if (!$errors) {
        $kayttaja = new Kayttaja(array(
          'kayttajanimi' => $params['kayttajanimi'],
          'salasana' => $params['salasana'],
          'etunimi' => $params['etunimi'],
          'sukunimi' => $params['sukunimi'],
          'sposti' => $params['sposti']
        ));
        $kayttaja->tallenna();
        Redirect::to('/', array('message' => 'Rekisteröityminen onnistui!'));
      }  else {
        View::make('/kayttaja/rekisteroidy.html', array('errors' => $errors, 'syote' => $syote));
      }
    }

    public function muokkaa() {
      self::check_logged_in();
      $params = $_POST;
      $kayttaja = array(
        'id' => $params['id'],
        'kayttajanimi' => $params['kayttajanimi'],
        'etunimi' => $params['etunimi'],
        'sukunimi' => $params['sukunimi'],
        'sposti' => $params['sposti'],
        'salasana' => $params['salasana'],
        'salasana_toisto' => $params['salasana_toisto']
      );

      $errors = self::validoi($params);
      if(!$errors) {
        $kayttaja = new Kayttaja(array(
          'id' => $params['id'],
          'kayttajanimi' => $params['kayttajanimi'],
          'etunimi' => $params['etunimi'],
          'sukunimi' => $params['sukunimi'],
          'sposti' => $params['sposti']
        ));
        $kayttaja->muokkaaYhta();
        Redirect::to('/kayttajat/' . $kayttaja->id, array('message' => 'Muutokset tallennettu'));
      } else {
        View::make('kayttaja/kayttaja.html', array('errors' => $errors, 'kayttaja' => $kayttaja));
      }

    }

    public function validoi($params) {
      $v = new Valitron\Validator($params);
      $v->rule('required', array('sposti', 'kayttajanimi', 'etunimi', 'sukunimi', 'salasana'))->message('{field} vaaditaan!');
      $v->labels(array(
        'sposti' => 'Sähköpostiosoite',
        'etunimi' => 'Etunimi',
        'sukunimi' => 'Sukunimi',
        'kayttajanimi' => 'Käyttäjänimi'
      ));
      $v->rule('equals', 'salasana', 'salasana_toisto')->message('Salasana ei täsmännyt');
      //$v->rule('alpha', array('etunimi', 'sukunimi'))->message('Nimessä saa olla vain kirjaimia');
      $v->rule('email', 'sposti')->message($params['sposti'] . ' ei ole kelvollinen sähköpostiosoite.');
      $v->rule('alphaNum', array('kayttajanimi', 'salasana'))->message('{field} saa sisältää ainostaan kirjaimia ja numeroita.');
      $v->rule('lengthMin', array('kayttajanimi', 'salasana'), 4)->message('{field} täytyy olla vähintään viisi merkkiä pitkä');
      if ($v->validate()) {
        return null;
      } else {
        return $v->errors();
      }
    }

    public static function kaikki() {
      self::check_logged_in();
      self::check_if_admin();
      $kayttajat = Kayttaja::kaikki();
      View::make('kayttaja/kayttajat.html', array('kayttajat' => $kayttajat));
    }

    public static function etsi($id) {
      self::check_logged_in();
      $kayttaja = Kayttaja::etsi($id);
      View::make('kayttaja/kayttaja.html', array('kayttaja' => $kayttaja));
    }

    public function muokkaaKayttajia() {
      self::check_logged_in();
      $id = $_POST['id'];
      $kayttajanimi = $_POST['kayttajanimi'];
      $delete = $_POST['delete'];
      $admin = $_POST['admin'];

      foreach ($id as $key => $i) {
        $kayttajat[] = new Kayttaja(array(
          'id' => $i,
          'kayttajanimi' => $kayttajanimi[$key],
          'admin' => $admin[$key],
          'delete' => $delete[$key]
        ));
      }
      foreach ($kayttajat as $kayttaja) {
        if ($kayttaja->delete == 1) {
          $kayttaja->poista();
        } else {
          $kayttaja->muokkaa();
        }
      }
      Redirect::to('/kayttajat/', array('message' => 'Muutokset tallennettu'));
    }

    public function kirjauduUlos() {
      $id = $_POST['id'];

      $kayttaja = new Kayttaja(array(
        'id' => $id
      ));
      $kayttaja->kirjauduUlos();

      Redirect::to('/', array('message' => 'Olet kirjautunut ulos'));
    }

    public function lisaaSuosikki() {
      $user = self::get_user_logged_in();
      $params = $_POST;
      $id = $params['id'];
      Kayttaja::lisaaSuosikki($id, $user->id);
    }

    public function poistaSuosikki() {
      $user = self::get_user_logged_in();
      $params = $_POST;
      $id = $params['id'];
      Kayttaja::poistaSuosikki($id, $user->id);
    }
  }
