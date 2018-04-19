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

    public function uploadImg(){
        $vue = new MainView();
        $img = $_FILES['image'];

        // Différents tests si l'image envoyée est correcte
        if(!isset($img))
            $vue->error("Image manquante");

        if($img['error']!=0)
            $vue->error("Erreur dans l'envoi de l'image");

        if($img['size']>1E6) // 1Mo
            $vue->error("Fichier trop lourd");

        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($img['tmp_name']);
        if(!in_array($detectedType, $allowedTypes))
            $vue->error("Le fichier n'est pas une image");

        if(file_exists("./img/".$img['name']))
            $vue->error("Le fichier est déjà présent");

        if(!move_uploaded_file($img['tmp_name'],"./img/".$img['name']))
            $vue->error("Echec de l'upload");

        echo("Image bien uploadée :".$img['name']);
        $vue->render();

    }
  }
