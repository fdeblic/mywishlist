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
  }


?>
