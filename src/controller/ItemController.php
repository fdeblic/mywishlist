<?php
namespace mywishlist\controller;
require_once 'vendor/autoload.php';

  use \mywishlist\models\Item as Item;
  use \mywishlist\view\ItemView as ItemView;

  class ItemController{

      public static function displayItem($id){
          $item = Item::where('id','=',$id)->first();

          //Affiche l'item via la vue
          $vue = new ItemView();
          $vue->renderItem($item);
      }

      public static function createItem(){
        //TODO
        $view = new ItemView();

        // TODO Retirer lorsque les comptes seront fonctionnels
        $_SESSION['user_id'] = 42;

        // Vérifie les données envoyées
        if (!isset($_SESSION['user_id']))  $view->notConnectedError();
        if (!isset($_POST['item_nom']))    $view->error("veuillez entrer un nom");
        if (!isset($_POST['item_descr']))  $view->error("veuillez entrer une description");
        if (!isset($_POST['item_tarif']))  $view->error("veuillez entrer un tarif");
        



      }
  }


?>
