<?php
  require_once 'vendor/autoload.php';
  $app = new \Slim\Slim();

  $app->get('/', function() {
    echo "Accueil";
  });

  $app->get('/test', function() {
    echo "Test fonctionnel !<br>";
  });

  $app->get('/test/:id', function($id) {
    echo "Test avec paramètre fonctionnel !<br>";
    echo "Vous avez entré : $id";
  });

  $app->run();
?>
