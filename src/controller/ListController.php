<?php
namespace mywishlist\controller;
require_once 'vendor/autoload.php';

use \mywishlist\models\WishList as WishList;
use \mywishlist\models\Account as Account;
use \mywishlist\view\ListView as ListView;
use Illuminate\Database\QueryException;

class ListController {
  /**
   *Affiche les listes existantes
   */
  public function dispAllList() {
    $view = new ListView();
    $publicLists = WishList::where('public','=', 1)->get();
    if (AccountController::isConnected()){
      $user = AccountController::getCurrentUser();
      if ($user->admin)
        $ownLists = WishList::select('*')->orderBy('expiration','ASC')->get();
      else
        $ownLists = WishList::where('user_id','=', $user->id_account)->orderBy('expiration','ASC')->get();
      $view->renderLists($publicLists, $ownLists);
    } else {
      $view->renderLists($publicLists, null);
    }
  }

  /**
   *Affiche une liste
   */
  public function displayList($id, $token) {
    $list = WishList::where('no','=',$id)->where('token','=',$token)->first();
    $user = AccountController::getCurrentUser();
    //Affiche la liste via la vue
    $view = new ListView();
    $view->renderList($list,$user);

  }

  /**
   *Créer une liste
   */
  public function createList(){
    $view = new ListView();
    $user = AccountController::getCurrentUser();
    // Vérifie les données envoyées
    if ( !AccountController::isConnected())
    $view->notConnectedError();

    // Transcrit la date reçue
    $expiration = date('Y-m-d', strtotime($_POST['list_expiration']));
    if ($expiration == null)
      $view->error("date incorrecte");
    if(time() - strtotime($expiration) >= 0)
      $view->error("Vous ne pouvez sélectionner une date passée.");

    // Crée la nouvelle liste
    $wishlist = new WishList();
    $wishlist->user_id = $user->id_account;
    $wishlist->titre =  filter_var($_POST['list_title'],FILTER_SANITIZE_STRING);
    $wishlist->description = filter_var($_POST['list_descr'],FILTER_SANITIZE_STRING);
    $wishlist->expiration = $expiration;
    $wishlist->public =  false;
    $wishlist->token = stripslashes (crypt(
      $_POST['list_title'] . $_POST['list_descr'] . $_POST['list_expiration'],
      $_SESSION['user_login'] . "sel de mer"
    ));

    try {
      if ($wishlist->save()) {
        $view->addHeadMessage("Votre liste a bien été créée", 'good');
        $view->renderList($wishlist,$user);
      } else {
        $view->addHeadMessage("Votre liste n'a pu être créée", 'bad');
        $this->getFormList(null);
      }
    }
    catch (QueryException $e) {
      $vue->addHeadMessage("Une erreur est survenue à la sauvegarde...", "bad");
    }
  }


  /**
   *Editer la liste avec les données passées en post
   *@param $id l'id de la liste
   *@param $token le token de la liste
   */
  public function editList($id, $token) {
    $view = new ListView();
    $user = AccountController::getCurrentUser();
    // Vérifie les données envoyées
    if ( !AccountController::isConnected() )
    $view->notConnectedError();

    $wishlist = WishList::where('no','=',$id)->where('token','=',$token)->first();
    if (!isset($wishlist) || $wishlist->user_id != $user->id_account && $user->admin == false){
      $view->addHeadMessage("Vous ne pouvez pas modifier cette liste", 'bad');
      $view->renderList($wishlist,$user);
      return;
    }

    // Transcrit la date reçue
    $expiration = date('Y-m-d', strtotime($_POST['list_expiration']));
    if ($expiration == null) $view->error("date incorrecte");

    // Crée la nouvelle liste

    if ($wishlist == null) $view->error('cette liste n\'existe pas');

    $wishlist->titre =  filter_var($_POST['list_title'],FILTER_SANITIZE_STRING);
    $wishlist->description = filter_var($_POST['list_descr'],FILTER_SANITIZE_STRING);
    $wishlist->expiration = $expiration;
    $wishlist->public = isset($_POST['list_public']) ? true : false;
    try {
      if ($wishlist->save()) {
        $view->addHeadMessage("Votre liste a bien été modifiée", 'good');
        $view->renderList($wishlist,$user);
      } else {
        $view->addHeadMessage("Votre liste n'a pu être modifiée", 'bad');
        $this->getFormList(null);
      }
    }
    catch (QueryException $e) {
      $vue->addHeadMessage("Une erreur est survenue à la sauvegarde...", "bad");
    }
  }

  /**
   *Supprime la liste
   *@param $id l'id de la liste
   *@param $token le token de la liste
   */
  public function deleteList($id, $token) {
    $view = new ListView();
    $wishlist = Wishlist::where('no','=',$id)->where('token','=',$token)->first();
    if ($wishlist == null) $view->error("la liste n'existe pas");

    $user = AccountController::getCurrentUser();
    if ($wishlist->user_id != $user->id_account || $user->admin == 1){
      $view->addHeadMessage("Vous ne pouvez pas supprimer cette liste", 'bad');
      $view->renderList($wishlist,$user);
    }
    if ($wishlist->delete()) {
      $view->addHeadMessage("Votre liste a bien été supprimée", 'good');
      $this->dispAllList();
    }
    else {
      $view->error('impossible de supprimer la liste');
    }
  }

  /**
   *Affiche la liste via la vue
   *@param $id l'id de la liste
   *@param $token le token de la liste
   */
  public function getFormList($id, $token) {

    if (!isset($id)){
      $list = new WishList();
    }
    else{
      $list = WishList::where('no','=',$id)->where('token','=',$token)->first();
    }
    $vue = new ListView();
    $vue->renderFormList($list);

  }

  /**
   *Gère la disponibilité de la liste au public
   */
  public function dispPublicCreators() {
    $vue = new ListView();
    $creators = Account::whereIn(
      'id_account', WishList::select('user_id')->where('public','=',true)->get()->toArray()
      )->get();


      $vue->renderCreators($creators);
    }

  public function setListPublic($id, $token) {
    $vue = new ListView();
    $list = WishList::where('no','=',$id)->where('token','=',$token)->first();
    $user = AccountController::getCurrentUser();

    if($list->public == false){
      $list->public=true;
      $vue->addHeadMessage(" Votre liste est devenue publique.", 'good');
    }
    else if($list->public==true)
    $vue->addHeadMessage(" Votre liste est déjà publique.", 'bad');

    $list->save();
    $vue->renderList($list, $user);
    }
  }
