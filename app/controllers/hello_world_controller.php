<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('home.html');
    }

    public static function reseptit(){
      View::make('reseptit.html');
    }

    public static function rekisteroidy(){
      View::make('rekisteroidy.html');
    }

    public static function resepti(){
      View::make('resepti.html');
    }

    public static function lisaaResepti(){
      View::make('lisaa_resepti.html');
    }
  }
