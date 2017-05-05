<?php

  $routes->get('/', function() {
    KayttajatController::index();
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
    KayttajatController::kaikki();
  });

  $routes->post('/kayttajat/', function() {
    KayttajatController::muokkaaKayttajia();
  });

  $routes->get('/kayttajat/:id', function($id) {
    KayttajatController::etsi($id);
  });

  $routes->post('/kayttajat/edit/', function() {
    KayttajatController::muokkaa();
  });

  $routes->post('/kayttajat/kirjaudu-ulos/', function() {
    KayttajatController::kirjauduUlos();
  });

  $routes->post('/kayttaja/lisaaSuosikki/', function() {
    KayttajatController::lisaaSuosikki();
  });

  $routes->post('/kayttaja/poistaSuosikki/', function() {
    KayttajatController::poistaSuosikki();
  });

/* resepti kontrollerit */

$routes->get('/resepti/:id', function($id) {
  ReseptitController::nayta($id);
});

$routes->get('/resepti/lisaa-resepti/', function() {
  ReseptitController::haeRaaka_aineet();
});

$routes->post('/resepti/lisaa-resepti/yksikot/', function() {
  ReseptitController::haeYksikot();
});

$routes->post('/resepti/lisaa-resepti/', function() {
  ReseptitController::lisaaResepti();
});

$routes->get('/resepti/:id/muokkaa/', function($id) {
  ReseptitController::muokkaa($id);
});

$routes->post('/resepti/:id/muokkaa/', function($id) {
  ReseptitController::tallennaMuokkaukset($id);
});

$routes->get('/resepti/:id/poista/', function($id) {
  ReseptitController::poistonVarmistus($id);
});

$routes->post('/resepti/:id/poista/', function($id) {
  ReseptitController::poista($id);
});

$routes->get('/resepti/alkuruoat/', function() {
  ReseptitController::index(1);
});

$routes->get('/resepti/paaruoat/', function() {
  ReseptitController::index(2);
});

$routes->get('/resepti/jalkiruoat/', function() {
  ReseptitController::index(3);
});

$routes->get('/resepti/omat-reseptit/:id', function($id) {
  ReseptitController::naytaOmat($id);
});

$routes->get('/kuva/:id', function($id) {
  ReseptitController::naytaKuva($id);
});
