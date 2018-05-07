<?php
  namespace mywishlist\controller;
  require_once 'vendor/autoload.php';

  use \mywishlist\models\WishList as WishList;
  use \mywishlist\models\Account as Account;
  use \mywishlist\view\ListView as ListView;

  class ListController {
    // Affiche les listes existantes
    public function dispAllList() {
      // Récupère toutes les listes existantes dans la base de données
      $lists = WishList::where('public','=', 1);
      if (isset($_SESSION['user_login'])){
          $user = Account::where('login','=',$_SESSION['user_login'])->first();
          $lists = $lists->orWhere('user_id','=', $user->id_account);
      }
      $lists = $lists->get();
      // Affiche les listes via la vue
      $vue = new ListView();
      $vue->renderLists($lists);
    }

    public function displayList($id, $token) {
        $list = WishList::where('no','=',$id)->where('token','=',$token)->first();

        //Affiche la liste via la vue
        $vue = new ListView();
        $vue->renderList($list);

    }

    public function createList(){
      $view = new ListView();

      // Vérifie les données envoyées
      if (!isset($_SESSION['user_login']))
        $view->notConnectedError();
      if (!isset($_POST['list_title']))
        $view->error("veuillez entrer un titre");
      if (!isset($_POST['list_descr']))
        $view->error("veuillez entrer une description");
      if (!isset($_POST['list_expiration']))
        $view->error("veuillez entrer une date d'expiration");

      // Transcrit la date reçue
      $expiration = date('Y-m-d', strtotime($_POST['list_expiration']));
      if ($expiration == null)
        $view->error("date incorrecte");

      // Crée la nouvelle liste
      $user = Account::where('login','=',$_SESSION['user_login'])->first();
      $wishlist = new WishList();
      $wishlist->user_id = $user->id_account;
      $wishlist->titre =  filter_var($_POST['list_title'],FILTER_SANITIZE_STRING);
      $wishlist->description = filter_var($_POST['list_descr'],FILTER_SANITIZE_STRING);
      $wishlist->expiration = $expiration;
      $wishlist->public = isset($_POST['list_public']) ? 1 : 0 ;
      $wishlist->token = crypt(
        $_POST['list_title'] . $_POST['list_descr'] . $_POST['list_expiration'],
        $_SESSION['user_login'] . "sel de mer"
      );

      $wishlist->save();
      $view->renderListCreated($wishlist);
    }


    //Editer la liste par les données passées en post
    public function editList($id, $token) {
      $view = new ListView();

      // Vérifie les données envoyées
      if (!isset($_SESSION['user_login']))
        $view->notConnectedError();
      if (!isset($_POST['list_title']))
        $view->error("veuillez entrer un titre");
      if (!isset($_POST['list_descr']))
        $view->error("veuillez entrer une description");
      if (!isset($_POST['list_expiration']))
        $view->error("veuillez entrer une date d'expiration");

      // Transcrit la date reçue
      $expiration = date('Y-m-d', strtotime($_POST['list_expiration']));
      if ($expiration == null)
        $view->error("date incorrecte");

      // Crée la nouvelle liste
      $user = Account::where('login','=',$_SESSION['user_login'])->first();
      $wishlist = WishList::where('no','=',$id)->where('token','=',$token)->first();

      if ($wishlist == null)
        $view->error('cette liste n\'existe pas');

      $wishlist->titre =  filter_var($_POST['list_title'],FILTER_SANITIZE_STRING);
      $wishlist->description = filter_var($_POST['list_descr'],FILTER_SANITIZE_STRING);
      $wishlist->expiration = $expiration;
      $wishlist->public = isset($_POST['list_public']) ? 1 : 0;
      $wishlist->save();
      $view->renderListEdited($wishlist);
    }

    public function deleteList($id, $token) {
        $view = new ListView();
        $wishlist = Wishlist::where('no','=',$id)->where('token','=',$token)->first();

        if ($wishlist == null)
          $view->error("la liste n'exite pas");

        if ($wishlist->delete())
          $view->renderListDelete();
        else {
          $view->error('impossible de supprimer la liste');
        }
    }

    public function getFormList($id, $token) {
        //Affiche la liste via la vue
        if (!isset($id)){
            $list = new WishList();
        }
        else{
            $list = WishList::where('no','=',$id)->where('token','=',$token)->first();
        }
        $vue = new ListView();
        $vue->renderFormList($list);

    }


    public function dispPublicCreators() {
      $vue = new ListView();
      $creators = Account::whereIn(
        'id_account', WishList::select('user_id')->where('public','=',true)->get()->toArray()
        //WishList::select('no')->where('public','=',true)->get();
      )->get();


      $vue->renderCreators($creators);
    }
  }
