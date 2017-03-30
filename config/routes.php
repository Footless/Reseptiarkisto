<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/reseptit/', function() {
    ReseptitController::index();
  });

  $routes->get('/rekisteroidy/', function() {
    HelloWorldController::rekisteroidy();
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
