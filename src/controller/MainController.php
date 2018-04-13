<?php
  namespace mywishlist\controller;
  require_once 'vendor/autoload.php';

  use \mywishlist\models\WishList as WishList;
  use \mywishlist\view\ListView as ListView;
  use \mywishlist\view\GlobalView as GlobalView;
  use \mywishlist\view\MainView as MainView;

  class MainController {
    // Affiche les listes existantes
    public static function displayHome(){
        $vue = new MainView();
        $vue->render();
    }

    // Affiche la page d'upload d'une image
    public static function getFormUploadImg(){
        $vue = new MainView();
        $vue->generateImgForm();
    }
  }
