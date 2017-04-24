<?php

  class BaseController{

    public static function get_user_logged_in(){
      if(isset($_SESSION['user'])){
        $user_id = $_SESSION['user'];
        $user = Kayttaja::etsi($user_id);

        return $user;
      }
      return null;
    }

    public static function check_logged_in(){
      if(!isset($_SESSION['user'])){
        Redirect::to('/', array('message' => 'Kirjaudu sisään!'));
      }
    }

    public static function check_if_admin(){
      $user_id = $_SESSION['user'];
      $user = Kayttaja::etsi($user_id);
      if(!isset($_SESSION['user']) && !$kayttaja->admin){
        Redirect::to('/', array('message' => 'Sinulla ei ole oikeuksia tälle sivulle!'));
      }
    }

  }
