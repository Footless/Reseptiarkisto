<?php

  class KayttajatController extends BaseController{

    public static function rekisteroidy(){
      View::make('rekisteroidy.html');
    }

    public static function tallenna(){
      $params = $_POST;
      $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajanimi = :kayttajanimi LIMIT 1');
      $query->execute(array('kayttajanimi' => $params['kayttajanimi']));
      $row = $query->fetch();
      if ($_POST['salasana'] !== $_POST['salasana_toisto']) {
        Redirect::to('/kayttajat/rekisteroidy', array('message' => 'Rekisteröityminen epäonnistui! Tarkista salasana.'));
      } else if ($row) {
        Redirect::to('/kayttajat/rekisteroidy', array('message' => 'Rekisteröityminen epäonnistui! Valitsemasi käyttäjänimi on jo käytössä.'));
      } else {
        $kayttaja = new Kayttaja(array(
          'kayttajanimi' => $params['kayttajanimi'],
          'salasana' => $params['salasana']
        ));

        $kayttaja->save();
      }
      Redirect::to('/', array('message' => 'Rekisteröityminen onnistui!'));
    }
  }
