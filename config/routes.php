<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/reseptit/', function() {
    ReseptitController::index();
  });

  $routes->get('/resepti/:id', function($id) {
    ReseptitController::show($id);
  });

  $routes->get('/lisaa-resepti/', function() {
    HelloWorldController::lisaaResepti();
  });

  $routes->get('/hiekkalaatikko/', function() {
    HelloWorldController::sandbox();
  });

  /* käyttäjään kohdistuvat */

  $routes->post('/kayttajat/rekisteroidy/', function(){
    KayttajatController::tallenna();
  });

  $routes->get('/kayttajat/rekisteroidy/', function() {
    KayttajatController::rekisteroidy();
  });
