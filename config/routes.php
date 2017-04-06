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

  $routes->post('/kayttajat/kirjaudu/', function() {
    KayttajatController::kirjaudu();
  });

  $routes->get('/kayttajat/', function() {
    KayttajatController::all();
  });

  $routes->post('/kayttajat/', function() {
    KayttajatController::kayttajatEdit();
  });

  $routes->get('/kayttajat/:id', function($id) {
    KayttajatController::find($id);
  });

  $routes->post('/kayttajat/edit/', function() {
    KayttajatController::edit();
  });

  $routes->post('/kayttajat/kirjaudu-ulos/', function() {
    KayttajatController::kirjauduUlos();
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

$routes->get('/resepti/:id/muokkaa/', function($id) {
  ReseptitController::edit($id);
});

$routes->get('/resepti/:id/poista/', function($id) {
  ReseptitController::deleteConfirm($id);
});

$routes->post('/resepti/:id/poista/', function($id) {
  ReseptitController::delete($id);
});
