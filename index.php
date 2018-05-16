<?php
  require_once 'vendor/autoload.php';

  // Contrôleurs
  use mywishlist\controller\ListController as ListController;
  use mywishlist\controller\MainController as MainController;
  use mywishlist\controller\ItemController as ItemController;
  use mywishlist\controller\MessageController as MessageController;
  use mywishlist\controller\AccountController as AccountController;
  use mywishlist\controller\PotController as PotController;

  // Base de données
  use Illuminate\Database\Capsule\Manager as DB;
  $db = new DB();
  $db->addConnection(parse_ini_file('src/conf/db.config.ini'));
  $db->setAsGlobal();
  $db->bootEloquent();

  // Démarre la session
  session_start();

  // Démarre Slim
  $app = new \Slim\Slim();
  $app->config(['routes.case_sensitive' => false]);

  if (AccountController::isConnected()) {
    $user = AccountController::getCurrentUser();
    $app->setCookie('user', $user->login, '12 months');
    $app->setCookie('pass', crypt($user->login, $user->password), '12 months');
  }

  if ($app->getCookie('user') && $app->getCookie('pass')) {
    AccountController::connectFromCookie($app->getCookie('user'), $app->getCookie('pass'));
  }

  /* --------
    ROUTES
  -------- */

  // Page : accueil
  $app->get('/', function() {
    MainController::displayHome();
  })->name('home');

  /*
    LISTES
  */

  // Page : affiche les listes publiques
  $app->get('/lists', function() {
    $ctrl = new ListController();
    $ctrl->dispAllList();
  })->name('list_getPubLists');

  // Page : affiche les créateurs de liste publique
  $app->get('/list/creators', function() {
    $ctrl = new ListController();
    $ctrl->dispPublicCreators();
  })->name('list_getCreators');

  // Page : créer une liste
  $app->get('/list/create', function() {
    $ctrl = new ListController();
    $ctrl->getFormList(null, null);
  })->name('list_createGet');

  // Enregistre une nouvelle liste
  $app->post('/list/create', function() {
    $ctrl = new ListController();
    $ctrl->createList();
  })->name('list_createPost');

  // Page : affiche une liste
  $app->get('/list/:id/:token', function($id, $token){
    $ctrl = new ListController();
    $ctrl->displayList($id, $token);
  })->name('list_aff');

  // Page : éditer une liste
  $app->get('/list/edit/:id/:token', function($id, $token){
    $ctrl = new ListController();
    $ctrl->getFormList($id, $token);
  })->name('list_editGet');

  // Enregistre les modifs d'une liste
  $app->post('/list/edit/:id/:token', function($id, $token){
    $ctrl = new ListController();
    $ctrl->editList($id, $token);
  })->name('list_editPost');

  // Supprime une liste
  $app->get('/list/del/:id/:token', function($id, $token){
    $ctrl = new ListController();
    $ctrl->deleteList($id, $token);
  })->name('list_delete');

  // Rend une liste publique
  $app->get('/list/public/:id/:token', function($id, $token){
   $ctrl = new ListController();
   $ctrl->setListPublic($id, $token);
 })->name('list_set_public');

  /*
    MESSAGES DE LISTE
  */

  // Page : nouveau message
  $app->get('/list/msg/:id/:token', function($id, $token){
    $ctrl = new MessageController();
    $ctrl->getFormMessage($id, $token);
  })->name('list_addMsgGet');

  // Ajouter un message à une liste
  $app->post('/list/msg/:id/:token', function($id, $token){
    $ctrl = new MessageController();
    $ctrl->createMessage($id, $token);
  })->name('list_addMsgPost');

  /*
    ITEMS
  */

  // Page : afficher un item
  $app->get('/item/:id/:token', function($idItem, $tokenItem){
    $ctrl = new ItemController();
    $ctrl->displayItem($idItem, $tokenItem);
  })->name('item_aff');

  // Page : créer un item
  $app->get('/list/:id/:token/creerItem', function($idList, $tokenList){
    $ctrl = new ItemController();
    $ctrl->getFormCreateItem($idList, $tokenList);
  })->name('list_addItemGet');

  // Créer un nouvel item
  $app->post('/list/:id/:token/creerItem', function($idList, $tokenList){
    $ctrl = new ItemController();
    $ctrl->createItem($idList, $tokenList);
  })->name('list_addItemPost');

  // Supprimer un item
  $app->get('/item/del/:id/:token', function($idItem, $tokenItem){
    $ctrl = new ItemController();
    $ctrl->delItem($idItem, $tokenItem);
  })->name('item_del');

  // Page : éditer un item
  $app->get('/item/:id/:token/edit', function($idItem, $tokenItem){
    $ctrl = new ItemController();
    $ctrl->getFormItem($idItem, $tokenItem);
  })->name('item_editGet');

  // Enregistrer un item
  $app->post('/item/:id/:token/edit', function($idItem, $tokenItem){
    $ctrl = new ItemController();
    $ctrl->editItem($idItem, $tokenItem);
  })->name('item_editPost');

  // Supprimer l'image d'un item
  $app->get('/item/:id/delImg/:token', function($idItem, $tokenItem){
    $ctrl = new ItemController();
    $ctrl->delImg($idItem, $tokenItem);
  })->name('item_delImg');

  // Participer à une cagnotte
  $app->post('/item/participate/:id/:token', function($idItem, $tokenItem){
    $ctrl = new PotController();
    $ctrl->participatePot($idItem);
  })->name('item_participate_post');

  //Réserver un item
  $app->get('/item/:id/:token/book', function($idItem, $tokenItem){
      $ctrl = new ItemController();
      $ctrl->getItemBookingForm($idItem, $tokenItem);
  })->name('item_reserv_get');

  // Envoi des données du formulaire de réservation
  $app->post('/item/:id/:token/book', function($idItem, $tokenItem){
      $ctrl = new ItemController();
      $ctrl->bookItem($idItem, $tokenItem);
  })->name('item_reserv_post');
  
  /*
    IMAGES
  */

  // Page : formulaire d'envoi d'image
  $app->get('/image', function(){
    $ctrl = new MainController();
    $ctrl->getFormUploadImg( );
  })->name('pot_addImg_get');

  // Enregistrer une image
  $app->post('/image', function(){
    $ctrl = new MainController();
    $ctrl->uploadImg();
  })->name('pot_addImg_post');

  /*
    COMPTES
  */

  // Page : créer un compte
  $app->get('/account/new', function() {
   $ctrl = new AccountController();
   $ctrl->createAccountForm();
  })->name('acc_create_get');

  // Enregistrer le nouveau compte
  $app->post('/account/new', function() {
   $ctrl = new AccountController();
   $ctrl->insertNewAccount();
  })->name('acc_create_post');

  // Page : modifier un compte
  $app->get('/account/edit', function() {
   $ctrl = new AccountController();
   $ctrl->edit('get');
  })->name('acc_edit_get');

  // Enregistrer les modifications
  $app->post('/account/edit', function() {
   $ctrl = new AccountController();
   $ctrl->edit('post');
  })->name('acc_edit_post');

  // Supprimer un compte
  $app->post('/account/delete', function() {
   $ctrl = new AccountController();
   $ctrl->delete();
  })->name('acc_delete');

  // S'authentifier
  $app->post('/auth', function() {
   $ctrl = new AccountController();
   $ctrl->connect();
  })->name('acc_auth');

  // Se déconnecter
  $app->get('/disconnect', function() {
   $ctrl = new AccountController();
   $ctrl->disconnect();
  })->name('acc_disconnect');

  // Se déconnecter
  $app->get('/reset', function() {
    try {
      DB::statement('CALL reset()');
      \mywishlist\view\GlobalView::addHeadMessage('Base de données remise à l\'origine', 'good');
    } catch (Exception $e) {
      \mywishlist\view\GlobalView::addHeadMessage('La base de données n\'a pas pu être remise à zéro<br>'.$e->getMessage(), 'bad');
    }
   MainController::displayHome();
 })->name('reset');

  $app->run();
