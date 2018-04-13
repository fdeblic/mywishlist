<?php
  require_once 'vendor/autoload.php';
  use mywishlist\view\GlobalView as GlobalView;
  use mywishlist\controller\ListController as ListController;
  use mywishlist\controller\MainController as MainController;
  use mywishlist\controller\ItemController as ItemController;

  use Illuminate\Database\Capsule\Manager as DB;

  $db = new DB();
  $db->addConnection(parse_ini_file('src/conf/db.config.ini'));
  $db->setAsGlobal();
  $db->bootEloquent();

  $app = new \Slim\Slim();
  $app->config(['routes.case_sensitive' => false]);

    /**
    * Partie pour l'accueil
    */
  $app->get('/', function() {
    MainController::displayHome();
  });

  /**
   * Partie pour les listes
   */
  $app->get('/liste', function() {
    // Displays all the existing wishlists
    ListController::dispAllList();
  });

  $app->get('/liste/creer', function() {
    // Creates a new list
    ListController::editList(null);
  });

  $app->post('/liste/creer', function() {
    // Creates a wishlist with the data sent with POST
    ListController::createList();
  });

  $app->get('/liste/:id', function($id){
      //Displays the list obtained with id
      ListController::displayList($id);
  });

  //TODO OKAYDAC
  //Ajout
  // $app->post('/liste/items/:id', function($id))

  /**
   * Partie pour les items
   */

  $app->get('/items/:id', function($id){
      //Display item obtained with id
      ItemController::displayItem($id);
  });

  /**
  *Upload image
  */
  $app->get('/image', function(){
      MainController::getFormUploadImg( );
  });

  /**
   * Partie pour les comptes utilisateurs
   */
  $app->run();
?>
