<?php
  require_once 'vendor/autoload.php';
  use mywishlist\view\GlobalView as GlobalView;
  use mywishlist\controller\ListController as ListController;
  use mywishlist\controller\MainController as MainController;
  use mywishlist\controller\ItemController as ItemController;
  use mywishlist\controller\MessageController as MessageController;
  use mywishlist\controller\AccountController as AccountController;
  use mywishlist\models\Item as Item;

  use Illuminate\Database\Capsule\Manager as DB;

  $db = new DB();
  $db->addConnection(parse_ini_file('src/conf/db.config.ini'));
  $db->setAsGlobal();
  $db->bootEloquent();

  session_start();
  $app = new \Slim\Slim();
  $app->config(['routes.case_sensitive' => false]);

  $_SESSION['content'] = "";

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
    $controller = new ListController();
    $controller->dispAllList();
  })->name('list_getPubLists');


  $app->get('/lists/create', function() {
    // Creates a new list
    $controller = new ListController();
    $controller->getFormList(null);
  })->name('list_createGet');

  $app->post('/lists/creer', function() {
    // Creates a wishlist with the data sent with POST
    $controller = new ListController();
    $controller->createList();
  })->name('list_createPost');

  $app->get('/lists/:id', function($id){
      //Displays the list obtained with id
      $controller = new ListController();
      $controller->displayList($id);
  })->name('list_aff');

  //Edit list
  $app->post('/list/:id/edit', function($id){
      // Edit an list obtained by id
      $controller = new ListController();
      $controller->editList($id);
  })->name('list_editPost');

  $app->get('/list/:id/edit', function($id){
      // Edit an list obtained by id
      $controller = new ListController();
      $controller->getFormList($id);
  })->name('list_editGet');


  $app->post('/list/:id/msg', function($list_id){
      $controller = new MessageController();
      $controller->createMessage($list_id);
  })->name('list_addMsgPost');

  $app->get('/list/:id/msg', function($list_id){
      $controller = new MessageController();
      $controller->getFormMessage($list_id);
  })->name('list_addMsgGet');

  /**
   * Partie pour les items
   */

  $app->get('/items/:id', function($id){
      //Display item obtained with id
      $c = new ItemController();
      $c->displayItem($id);

  })->name('item_aff');

  $app->get('/item/:id/creer', function($id){
      // Create a new item
      $c = new ItemController();
      $c->getFormItem(null,$id);

  })->name('list_addItemGet');

  $app->post('/item/:id/creer', function($id){
      // Create an item with the data sent with POST
      $c = new ItemController();
      $c->createItem($id);
  })->name('list_addItemPost');

  $app->get('/item/:id/del', function($id){
    //Delete an item obtained by id
    $controller  = new ItemController();
    $controller->delItem($id);
  })->name('item_del');

  $app->post('/item/:id/edit', function($id){
      // Edit an item obtained by id
     $controller = new ItemController();
      $controller->editItem($id);
  })->name('item_editPost');

  $app->get('/item/:id/edit', function($id){
      // Edit an item obtained by id
      $controller = new ItemController();
      $controller->getFormItem($id,null);
  })->name('item_editGet');


  /**
  * Affichage de la page d'upload
  */
  $app->get('/image', function(){
      $controller = new MainController();
      $controller->getFormUploadImg( );
  })->name('pot_addImg_get');

  /**
  * Upload d'une image
  */
  $app->post('/image', function(){
      $controller = new MainController();
      $controller->uploadImg();
  })->name('pot_addImg_post');

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

   $app->post('/auth', function() {
     $ctrl = new AccountController();
     $ctrl->connect();
   }, function(){} )->name('acc_auth');

   $app->get('/disconnect', function() {
     $ctrl = new AccountController();
     $ctrl->disconnect();
   }, function(){} )->name('acc_disconnect');

   $app->run();

   // Session middleware
  $app->add(function (Request $request, Response $response, $next) {
      session_start();
      return $next($request, $response);
  });
?>
