<?php
namespace mywishlist\controller;
require_once 'vendor/autoload.php';

  use \mywishlist\models\Item as Item;
  use \mywishlist\view\ItemView as ItemView;

  class ItemController{

      public function displayItem($id){
          $item = Item::where('id','=',$id)->first();

          //Affiche l'item via la vue
          $vue = new ItemView();
          $vue->renderItem($item);
      }

      public function createItem($id){
        $view = new ItemView();

        // Vérifie les données envoyées
        if (!isset($_POST['item_nom'])) $view->error("veuillez entrer un nom");
        if (!isset($_POST['item_descr'])) $view->error("veuillez entrer une description");
        if (!filter_var($_POST['item_tarif'], FILTER_VALIDATE_FLOAT)) $view->error("Votre tarif est invalide.");

        $item = new Item();

        $nom = filter_var($_POST['item_nom'],FILTER_SANITIZE_STRING);
        $descr = filter_var($_POST['item_descr'],FILTER_SANITIZE_STRING);


        $item->liste_id = $id;
        if (strlen($nom)> 0) $item->nom = $nom;
        if (strlen($descr) > 0) $item->descr = $descr;
        $item->tarif = $_POST['item_tarif'];

        $item->save();
        $view->renderItemCreated($item);
      }

      public function editItem($item, $id){
          //Affiche l'item via la vue
          $view = new ItemView();
          $view->renderFormItem($item, $id);
      }

      public function delItem($id){
        $view = new ItemView();

        $item = Item::where('id','=',$id)->first();
        $itemdelete = $item->delete();

        $view = new ItemView();
        $view->renderDelItem($item);
      }
  }


?>
