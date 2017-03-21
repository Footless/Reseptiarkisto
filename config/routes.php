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
