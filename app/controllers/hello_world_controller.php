<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('home.html');
    }

    public static function reseptit(){
      View::make('reseptit.html');
    }

    public static function resepti(){
      View::make('resepti.html');
    }

    public static function sandbox(){
    $suosikit = Resepti::haeSuosikit(1);
    // Kint-luokan dump-metodi tulostaa muuttujan arvon
    Kint::dump($suosikit);
  }
  }
