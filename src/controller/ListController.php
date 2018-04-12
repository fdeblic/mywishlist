<?php
  namespace mywishlist\controller;
  require_once 'vendor/autoload.php';

  use \mywishlist\models\WishList as WishList;
  use \mywishlist\view\ListView as ListView;

  class ListController {
    // Affiche les listes existantes
    public static function dispAllList() {
      // Récupère toutes les listes existantes dans la base de données
      $lists = WishList::select('*')->get();

      // Affiche les listes via la vue
      $vue = new ListView();
      $vue->renderLists($lists);
    }

    public static function displayList($id){
        $list = WishList::where('no','=',$id)->first();

        //Affiche la liste via la vue
        $vue = new ListView();
        $vue->renderList($list);

    }

    public static function createList(){
      $view = new ListView();

      // TODO Retirer lorsque les comptes seront fonctionnels
      $_SESSION['user_id'] = 42;

      // Vérifie les données envoyées
      if (!isset($_SESSION['user_id']))
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
        $view->error("date correcte");

      // Crée la nouvelle liste
      $wishlist = new WishList();
      $wishlist->user_id = $_SESSION['user_id'];
      $wishlist->titre = $_POST['list_title'];
      $wishlist->description = $_POST['list_descr'];
      $wishlist->expiration = $expiration;
      $wishlist->token = crypt(
        $_POST['list_title'] . $_POST['list_descr'] . $_POST['list_expiration'],
        $_SESSION['user_id'] . "sel de mer"
      );

      $wishlist->save();
      $view->renderListCreated($wishlist);
    }

    public static function editList($list){
        //Affiche la liste via la vue
        $vue = new ListView();
        $vue->renderFormulaireList($list);

    }
  }
