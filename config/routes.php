<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/reseptit/', function() {
    HelloWorldController::reseptit();
  });

  $routes->get('/rekisteroidy/', function() {
    HelloWorldController::rekisteroidy();
  });

  $routes->get('/resepti/', function() {
    HelloWorldController::resepti();
  });

  $routes->get('/lisaa-resepti/', function() {
    HelloWorldController::lisaaResepti();
  });
