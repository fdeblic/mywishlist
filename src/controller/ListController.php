<?php
  namespace mywishlist\controller;
  require_once 'vendor/autoload.php';

  use \mywishlist\models\Liste as Liste;
  use \mywishlist\view\ListView as ListView;

  class ListController {
    public static function dispAllList() {
      // Récupère toutes les listes existantes dans la base de données
      $lists = Liste::select('*')->get();

      // Affiche les listes via la vue
      ListView::displayLists($lists);
    }
  }
 ?>
