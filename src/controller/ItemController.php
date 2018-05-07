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
        $item = new Item();

        // Vérifie les données envoyées
        if (!isset($_POST['item_nom'])) $view->error("veuillez entrer un nom");
        if (!isset($_POST['item_descr'])) $view->error("veuillez entrer une description");
        if (!filter_var($_POST['item_tarif'], FILTER_VALIDATE_FLOAT)) $view->error("Votre tarif est invalide.");

        $img = $_FILES['item_img'];

        if(is_uploaded_file($img['tmp_name'])){
            if($img['error']!=0)
                $view->error("Erreur dans l'envoi de l'image");

            if($img['size']>1E6) // 1Mo
                $view->error("Fichier trop lourd");

            $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($img['tmp_name']);
            if(!in_array($detectedType, $allowedTypes))
                $view->error("Le fichier n'est pas une image");

            /*if(file_exists("./img/".$img['name']))
                $view->error("Le fichier est déjà présent");*/

            if(!move_uploaded_file($img['tmp_name'],"./img/".$img['name']))
                $view->error("Echec de l'upload");

            $item->img = $img['name'];

        }


        $nom = filter_var($_POST['item_nom'],FILTER_SANITIZE_STRING);
        $descr = filter_var($_POST['item_descr'],FILTER_SANITIZE_STRING);
        if(isset($_POST['url_item']))  $url_item = filter_var($_POST['url_item'],FILTER_VALIDATE_URL);
        $pot = $_POST['item_pot'] == 'pot' ? true : false;


        $item->liste_id = $id;
        if (strlen($nom)> 0) $item->nom = $nom;
        if (strlen($descr) > 0) $item->descr = $descr;

        $item->tarif = $_POST['item_tarif'];
        if(isset($_POST['url_item'])) $item->url = filter_var($url_item,FILTER_SANITIZE_URL);
        $item->cagnotte = $pot;


        $item->save();
        $view->renderItemCreated($item);
      }

      public function getFormItem($id_item, $id_list){
          //Affiche l'item via la vue
          $view = new ItemView();
          if (!isset($id_item)){
             $item = new Item();
         }
         else{
             $item = Item::where('id','=',$id_item)->first();
             $id_list = $item->list_id;
         }
          $view->renderFormItem($item,$id_list);
      }

      public function editItem($id){
          //Affiche l'item via la vue
          $view = new ItemView();
          $item = Item::where('id','=',$id)->first();

          if (!isset($_POST['item_nom'])) $view->error("veuillez entrer un nom");
          if (!isset($_POST['item_descr'])) $view->error("veuillez entrer une description");
          if (!filter_var($_POST['item_tarif'], FILTER_VALIDATE_FLOAT)) $view->error("Votre tarif est invalide.");

          $img = $_FILES['item_img'];

          if(is_uploaded_file($img['tmp_name'])){
              if($img['error']!=0)
                  $view->error("Erreur dans l'envoi de l'image");

              if($img['size']>1E6) // 1Mo
                  $view->error("Fichier trop lourd");

              $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
              $detectedType = exif_imagetype($img['tmp_name']);
              if(!in_array($detectedType, $allowedTypes))
                  $view->error("Le fichier n'est pas une image");

              if(!move_uploaded_file($img['tmp_name'],"./img/".$img['name']))
                  $view->error("Echec de l'upload");

              $item->img = $img['name'];
          }

          $nom = filter_var($_POST['item_nom'],FILTER_SANITIZE_STRING);
          $descr = filter_var($_POST['item_descr'],FILTER_SANITIZE_STRING);
          if(isset($_POST['url_item']))  $url_item = filter_var($_POST['url_item'],FILTER_VALIDATE_URL);
          $pot = $_POST['item_pot'] == 'pot' ? true : false;


          if (strlen($nom)> 0) $item->nom = $nom;
          if (strlen($descr) > 0) $item->descr = $descr;
          $item->tarif = $_POST['item_tarif'];
          if(isset($_POST['url_item'])) $item->url = filter_var($url_item,FILTER_SANITIZE_URL);
          $item->cagnotte = $pot;
          $item->save();

          $view->renderEditItem($item,$id);
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
