<?php

  $routes->get('/', function() {
    HelloWorldController::index();
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

  $routes->get('/kayttajat/', function() {
    KayttajatController::all();
  });

  $routes->get('/kayttajat/:id', function($id) {
    KayttajatController::find($id);
  });

  $routes->post('/kayttajat/edit/', function() {
    KayttajatController::edit();
  });

/* resepti kontrollerit */

$routes->get('/reseptit/', function() {
  ReseptitController::index();
});

$routes->get('/resepti/:id', function($id) {
  ReseptitController::show($id);
});

$routes->get('/lisaa-resepti/', function() {
  ReseptitController::getIngs();
});
