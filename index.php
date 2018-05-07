<?php
  require_once 'vendor/autoload.php';
  use mywishlist\view\GlobalView as GlobalView;
  use mywishlist\controller\ListController as ListController;
  use mywishlist\controller\MainController as MainController;
  use mywishlist\controller\ItemController as ItemController;
  use mywishlist\controller\MessageController as MessageController;
  use mywishlist\controller\AccountController as AccountController;
  use mywishlist\controller\PotController as PotController;
  use mywishlist\models\Item as Item;

  use Illuminate\Database\Capsule\Manager as DB;

  $db = new DB();
  $db->addConnection(parse_ini_file('src/conf/db.config.ini'));
  $db->setAsGlobal();
  $db->bootEloquent();

  session_start();
  $app = new \Slim\Slim();
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
    $controller = new ListController();
    $controller->dispAllList();
  })->name('list_getPubLists');

  $app->get('/list/creators', function() {
    $controller = new ListController();
    $controller->dispPublicCreators();
  })->name('list_getCreators');

  $app->get('/list/create', function() {
    // Creates a new list
    $controller = new ListController();
    $controller->getFormList(null, null);
  })->name('list_createGet');

  $app->post('/list/create', function() {
    // Creates a wishlist with the data sent with POST
    $controller = new ListController();
    $controller->createList();
  })->name('list_createPost');

  $app->get('/list/:id/:token', function($id, $token){
      //Displays the list obtained with id
      $controller = new ListController();
      $controller->displayList($id, $token);
  })->name('list_aff');

  //Edit list
  $app->post('/list/edit/:id/:token', function($id, $token){
      // Edit an list obtained by id
      $controller = new ListController();
      $controller->editList($id, $token);
  })->name('list_editPost');

  $app->get('/list/edit/:id/:token', function($id, $token){
      // Edit an list obtained by id
      $controller = new ListController();
      $controller->getFormList($id, $token);
  })->name('list_editGet');

  $app->get('/list/del/:id/:token', function($id, $token){
      $controller = new ListController();
      $controller->deleteList($id, $token);
  })->name('list_delete');

  /**
   * Partie pour les messages de liste
   */

  $app->post('/list/msg/:id/:token', function($id, $token){
      $controller = new MessageController();
      $controller->createMessage($id, $token);
  })->name('list_addMsgPost');

  $app->get('/list/msg/:id/:token', function($id, $token){
      $controller = new MessageController();
      $controller->getFormMessage($id, $token);
  })->name('list_addMsgGet');

  /**
   * Partie pour les items
   */

  $app->get('/item/:id/:token', function($id, $token){
      //Display item obtained with id
      $c = new ItemController();
      $c->displayItem($id, $token);
  })->name('item_aff');

  $app->get('/list/:id/:token/creerItem', function($idList, $tokenList){
      // Create a new item
      $c = new ItemController();
      $c->getFormCreateItem($idList, $tokenList);
  })->name('list_addItemGet');

  $app->post('/list/:id/:token/creerItem', function($idList, $tokenList){
      // Create an item with the data sent with POST
      $c = new ItemController();
      $c->createItem($idList, $tokenList);
  })->name('list_addItemPost');

  $app->get('/item/del/:id/:token', function($id, $token){
    //Delete an item obtained by id
    $controller  = new ItemController();
    $controller->delItem($id, $token);
  })->name('item_del');

  $app->post('/item/:id/:token/edit', function($id, $token){
      // Edit an item obtained by id
     $controller = new ItemController();
     $controller->editItem($id, $token);
  })->name('item_editPost');

  $app->get('/item/:id/:token/edit', function($idItem, $tokenItem){
      // Edit an item obtained by id
      $controller = new ItemController();
      $controller->getFormItem($idItem, $tokenItem);
  })->name('item_editGet');

  $app->get('/item/:id/delImg', function($id){
    // Delete an object's image obtained by id of the obect
    $controller = new ItemController();
    $controller->delImg($id);
  })->name('item_delImg');

  $app->post('/item/participate/:id/:token', function($id, $token){
    $controller = new PotController();
    $controller->participatePot($id);
  })->name('item_participate_post');

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

   $app->get('/account/edit', function() {
     $ctrl = new AccountController();
     $ctrl->edit('get');
   })->name('acc_edit_get');

   $app->post('/account/edit', function() {
     $ctrl = new AccountController();
     $ctrl->edit('post');
   })->name('acc_edit_post');

   $app->post('/account/new', function() {
     $ctrl = new AccountController();
     $ctrl->insertNewAccount();
   })->name('acc_create_post');

   $app->post('/account/delete', function() {
     $ctrl = new AccountController();
     $ctrl->delete();
   })->name('acc_delete');

   $app->post('/auth', function() {
     $ctrl = new AccountController();
     $ctrl->connect();
   }, function(){} )->name('acc_auth');

   $app->get('/disconnect', function() {
     $ctrl = new AccountController();
     $ctrl->disconnect();
   }, function(){} )->name('acc_disconnect');

   $app->run();
?>
