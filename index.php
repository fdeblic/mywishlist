<?php
  require_once 'vendor/autoload.php';
  use mywishlist\view\GlobalView as GlobalView;
  use mywishlist\controller\ListController as ListController;
  use mywishlist\controller\MainController as MainController;
  use mywishlist\controller\ItemController as ItemController;
  use mywishlist\controller\AccountController as AccountController;

  use Illuminate\Database\Capsule\Manager as DB;

  $db = new DB();
  $db->addConnection(parse_ini_file('src/conf/db.config.ini'));
  $db->setAsGlobal();
  $db->bootEloquent();

  $app = new \Slim\Slim();
  $_SESSION['app'] = $app; // Pour le reste des scripts

  $app->config(['routes.case_sensitive' => false]);

    /**
    * Partie pour l'accueil
    */
  $app->get('/', function() {
    MainController::displayHome();
  })->name('home');

  /**
   * Partie pour les listes
   */
  $app->get('/lists', function() {
    // Displays all the existing wishlists
    ListController::dispAllList();
})->name('list_getPubLists');

  $app->get('/lists/create', function() {
    // Creates a new list
    ListController::editList(null);
})->name('list_createGet');

  $app->post('/lists/create', function() {
    // Creates a wishlist with the data sent with POST
    ListController::createList();
})->name('list_createPost');

  $app->get('/lists/:id', function($id){
      //Displays the list obtained with id
      ListController::displayList($id);
  })->name('list_aff');

  /**
   * Partie pour les items
   */

  $app->get('/items/:id', function($id){
      //Display item obtained with id
      ItemController::displayItem($id);
  })->name('item_aff');

  $app->get('/item/creer/:id', function($id){
      // Create a new item
      ItemController::editItem(null,$id);
  })->name('list_addItemGet');

  $app->post('/item/creer/:id', function($id){
      // Create an item with the data sent with POST
      ItemController::createItem($id);
  })->name('list_addItemPost');


  /**
  *Upload image
  */
  $app->get('/image', function(){
      MainController::getFormUploadImg( );
  })->name('pot_addImg');

  /**
   * Partie pour les comptes utilisateurs
   */
   $app->get('/account/new', function() {
     $ctrl = new AccountController();
     $ctrl->createAccountForm();
   })->name('acc_create_get');

   $app->post('/account/new', function() {
     $ctrl = new AccountController();
     $ctrl->insertNewAccount();
   })->name('acc_create_post');

   $app->run();
?>
