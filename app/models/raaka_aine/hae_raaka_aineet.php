<?php

  $params = $_POST;
  $raaka_aine = new Raaka_aine(array(
    'nimi' => $params['nimi']
  ));
  $json = $raaka_aine->haeYksikot();
  if ($json) {
    echo $json;
  }


 ?>
