<?php
  namespace mywishlist\controller;
  require_once 'vendor/autoload.php';

  use \mywishlist\models\WishList as WishList;
  use \mywishlist\view\ListView as ListView;
  use \mywishlist\view\GlobalView as GlobalView;

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
  }
