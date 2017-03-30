<?php

  class KayttajatController extends BaseController{

    public static function rekisteroidy(){
      View::make('rekisteroidy.html');
    }

    public static function tallenna(){
      $params = $_POST;
      $kayttaja = new Kayttaja(array(
        'kayttajanimi' => $params['kayttajanimi'],
        'salasana' => $params['salasana']
      ));

      $kayttaja->save();

      Redirect::to('/', array('message' => 'Rekisteröityminen onnistui!'));
    }
  }
