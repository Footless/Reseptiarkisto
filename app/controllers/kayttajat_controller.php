<?php

  class KayttajatController extends BaseController{

    public static function rekisteroidy(){
      View::make('kayttaja/rekisteroidy.html');
    }

    public function kirjaudu() {
      $params = $_POST;

      $kayttaja = Kayttaja::authenticate($params['kayttajanimi'], $params['salasana']);

      if (!$kayttaja) {
        Redirect::to('/', array('message' => 'Väärä salasana tai käyttäjätunnus', 'username' => $params['kayttajanimi']));
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
      $v = new Valitron\Validator($_POST);
      $v->rule('required', array('sposti', 'kayttajanimi', 'etunimi', 'sukunimi', 'salasana'))->message('{field} vaaditaan!');
      $v->labels(array(
        'sposti' => 'Sähköpostiosoite',
        'etunimi' => 'Etunimi',
        'sukunimi' => 'Sukunimi',
        'kayttajanimi' => 'Käyttäjänimi'
      ));
      $v->rule('equals', 'salasana', 'salasana_toisto')->message('Salasana ei täsmännyt');
      $v->rule('alpha', array('etunimi', 'sukunimi'))->message('Nimessä saa olla vain kirjaimia');
      $v->rule('email', 'sposti')->message($params['sposti'] . ' ei ole kelvollinen sähköpostiosoite.');
      $v->rule('alphaNum', array('kayttajanimi', 'salasana'))->message('{field} saa sisältää ainostaan kirjaimia ja numeroita.');
      $v->rule('lengthMin', array('kayttajanimi', 'salasana'), 4)->message('{field} täytyy olla vähintään viisi merkkiä pitkä');
      if ($v->validate()) {
        $kayttaja = new Kayttaja(array(
          'kayttajanimi' => $params['kayttajanimi'],
          'salasana' => $params['salasana'],
          'etunimi' => $params['etunimi'],
          'sukunimi' => $params['sukunimi'],
          'sposti' => $params['sposti']
        ));

        $kayttaja->save();
        Redirect::to('/', array('message' => 'Rekisteröityminen onnistui!'));
      } else if ($row) {
        Redirect::to('/kayttajat/rekisteroidy/', array('message' => 'Rekisteröityminen epäonnistui! Valitsemasi käyttäjänimi on jo käytössä.'));
      } else {
        View::make('/kayttaja/rekisteroidy.html', array('errors' => $v->errors(), 'syote' => $syote));
      }
    }

    public static function all() {
      $kayttajat = Kayttaja::all();
      View::make('kayttaja/kayttajat.html', array('kayttajat' => $kayttajat));
    }

    public static function find($id) {
      $kayttaja = Kayttaja::find($id);
      View::make('kayttaja/kayttaja.html', array('kayttaja' => $kayttaja));
    }

    public function edit() {
      $params = $_POST;
      $kayttaja = new Kayttaja(array(
        'id' => $params['id'],
        'kayttajanimi' => $params['kayttajanimi'],
        'etunimi' => $params['etunimi'],
        'sukunimi' => $params['sukunimi'],
        'sposti' => $params['sposti']
      ));
      $kayttaja->editOne();

      Redirect::to('/kayttajat/' . $kayttaja->id, array('message' => 'Muutokset tallennettu'));
    }

    public function kayttajatEdit() {
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
          $kayttaja->delete();
        } else {
          $kayttaja->edit();
        }
      }
      Redirect::to('/kayttajat/', array('message' => 'Muutokset tallennettu'));
    }

    public function kirjauduUlos() {
      $id = $_POST['id'];

      $kayttaja = new Kayttaja(array(
        'id' => $id
      ));
      $kayttaja->logOut();

      Redirect::to('/', array('message' => 'Olet kirjautunut ulos'));
    }
  }