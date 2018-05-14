<?php
  namespace mywishlist\controller;
  require_once 'vendor/autoload.php';

  use \mywishlist\models\WishList as WishList;
  use \mywishlist\view\ListView as ListView;
  use \mywishlist\view\GlobalView as GlobalView;
  use \mywishlist\view\MainView as MainView;
  use \mywishlist\view\ImageView as ImageView;

  class MainController {
    /**
     *Affiche les listes existantes
     */
    public static function displayHome(){
        $vue = new MainView();
        $vue->render();
    }
    
  }
