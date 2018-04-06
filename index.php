<?php
  require_once 'vendor/autoload.php';
  use mywishlist\view\GlobalView as GlobalView;
  use mywishlist\controller\ListController as ListController;

  use Illuminate\Database\Capsule\Manager as DB;

  $db = new DB();
  $db->addConnection(parse_ini_file('src/conf/db.config.ini'));
  $db->setAsGlobal();
  $db->bootEloquent();

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
    // Displays all the existing wishlists
    ListController::dispAllList();
  });

  $app->run();
?>
