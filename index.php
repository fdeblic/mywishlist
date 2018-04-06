<?php
  require_once 'vendor/autoload.php';
  use mywishlist\view\GlobalView;
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

  $app->get('/liste', function() {
    \mywishlist\view\GlobalView::render();
  });

  $app->run();
?>
