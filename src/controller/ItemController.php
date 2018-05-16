<?php
namespace mywishlist\controller;

  use \mywishlist\models\Item as Item;
  use \mywishlist\models\WishList as WishList;
  use \mywishlist\view\ItemView as ItemView;
  use \mywishlist\view\ListView as ListView;
  use \mywishlist\models\Account as Account;
  use \mywishlist\controller\AccountController as AccountController;
  use Illuminate\Database\QueryException;

  class ItemController{

      /**
       * Afficher un item
       * @param  $idItem    l'id de l'item
       * @param  $tokenItem le token de l'item
       */
      function displayItem($idItem, $tokenItem){
          $item = Item::where(['id' => $idItem , 'token' => $tokenItem])->first();

          //Affiche l'item via la vue
          $vue = new ItemView();
          $vue->renderItem($item);
      }

      /**
       * Créer un item
       * @param  $list_id   l'id de la liste
       * @param  $listToken le token de la liste
       */
      function createItem($list_id, $listToken){
        $view = new ItemView();
        $item = new Item();

        // Vérifie les données envoyées
        if (!isset($_POST['itemName'])) $view->error("veuillez entrer un nom");
        if (!isset($_POST['itemDescr'])) $view->error("veuillez entrer une description");
        if (!isset($_POST['itemTarif'])) $view->error("Le tarif est manquant");
        if (!isset($_POST['itemPotOrReserv'])) $view->error("Le champ réservation ou cagnotte est manquant");
        if (!filter_var($_POST['itemTarif'], FILTER_VALIDATE_FLOAT)) $view->error("Votre tarif est invalide");
        if (strlen($_POST['itemName']) < 3) $view->error("Le nom doit faire au minimum trois lettres");
        if (strlen($_POST['itemDescr']) < 10) $view->error("Décrivez un minimum l'item...");

        // Récupère les données
        $nom = filter_var($_POST['itemName'], FILTER_SANITIZE_STRING);
        $descr = filter_var($_POST['itemDescr'], FILTER_SANITIZE_STRING);
        $tarif = filter_var($_POST['itemTarif'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $url = isset($_POST['itemUrl']) ? filter_var($_POST['itemUrl'], FILTER_SANITIZE_URL) : '';
        $cagnotte = $_POST['itemPotOrReserv'] == 'pot' ? true : false;
        $img = NULL;

        // Ajoute le http:// si besoin
        if (strlen($url) != 0 && strpos($url,'http://') === false)
            $url = 'http://' . $url;

        // Récupère l'image
        $imgFile = $_FILES['itemImgFile'];
        if(is_uploaded_file($imgFile['tmp_name'])){
            if($imgFile['error']!=0)
                $view->error("Erreur dans l'envoi de l'image");
            if($imgFile['size']>1E6) // 1Mo
                $view->error("Fichier trop lourd");
            $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($imgFile['tmp_name']);
            if(!in_array($detectedType, $allowedTypes))
                $view->error("Le fichier n'est pas une image");
            if(!move_uploaded_file($imgFile['tmp_name'],"./img/item/".$imgFile['name']))
                $view->error("Echec de l'upload");
            $img = $imgFile['name'];
        }

        // Sauvegarde les données
        $item->nom = $nom;
        $item->descr = $descr;
        $item->tarif = $tarif;
        $item->url = $url;
        $item->cagnotte = $cagnotte;
        $item->img = $img;
        $item->booking_user = NULL;
        $item->message_booking = NULL;
        $item->token = stripslashes(crypt($item->nom . $item->liste_id, 'sel de mer'));
        $item->liste_id = $list_id;

        try {
            $item->save();
            $view->addHeadMessage("L'item a bien été enregistré", 'good');
            $view->renderItem($item);
        } catch (QueryException $e) {
            $view->addHeadMessage("Erreur lors de l'enregistrement", 'bad');
            $view->renderFormItem($item, null);
        }
      }

      /**
       * Récupérer le formulaire de création d'un item
       * @param  $idList    l'id de la liste de l'item
       * @param  $tokenList le token de la liste de l'item
       */
      function getFormCreateItem($idList, $tokenList) {
        $view = new ItemView();
        $list = WishList::where('no','=',$idList)->where('token','=',$tokenList)->first();
        if (!isset($list))
          $view->error('liste introuvable');

        $user = AccountController::getCurrentUser();
        if (!isset($user) || $list->user_id != $user->id_account && $user->admin == false) {
          $view = new ListView();
          $view->addHeadMessage("Vous ne pouvez pas créer d'item sur cette liste", 'bad');
          $view->renderList($list, $user);
          return;
        }

        $view->renderFormItem(null, $list);
      }

      function getFormItem($idItem, $tokenItem){
        //Affiche l'item via la vue
        $view = new ItemView();
        $list = null;

        if (!isset($idItem)){
           $item = new Item();
        }
        else{
           $item = Item::where(['id' => $idItem , 'token' => $tokenItem])->first();
           $list = $item->liste;
        }
        $view->renderFormItem($item, $list);
      }

      /**
       * Editer l'item
       * @param  $id    L'id de l'item à éditer
       * @param  $token Le token de l'item à éditer
       */
      function editItem($id, $token){
          //Affiche l'item via la vue
          $view = new ItemView();
          $item = Item::where('id','=',$id)->where('token','=',$token)->first();

          $user = AccountController::getCurrentUser();
          $wishlist = $item->liste;
          if ($user == null || $wishlist->user_id != $user->id_account || $user->admin == 1){
            $view->addHeadMessage("Vous ne pouvez pas modifier cet item", 'bad');
            $view->renderItem($item);
            return;
          }
          if(isset($item->booking_user)){
            $view->addHeadMessage("Vous ne pouvez pas modifier un item déjà réservé.", 'bad');
            $view->renderItem($item);
            return;
          }


          if (!isset($item)) $view->error("item non trouvé");
          if (!isset($_POST['itemName'])) $view->error("veuillez entrer un nom");
          if (!isset($_POST['itemDescr'])) $view->error("veuillez entrer une description");
          if (!filter_var($_POST['itemTarif'], FILTER_VALIDATE_FLOAT)) $view->error("Votre tarif est invalide.");

          $imgFile = $_FILES['itemImgFile'];

          if(is_uploaded_file($imgFile['tmp_name'])){
              if($imgFile['error']!=0)
                  $view->error("Erreur dans l'envoi de l'image");

              if($imgFile['size']>1E6) // 1Mo
                  $view->error("Fichier trop lourd");

              $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
              $detectedType = exif_imagetype($imgFile['tmp_name']);
              if(!in_array($detectedType, $allowedTypes))
                  $view->error("Le fichier n'est pas une image");

              $newfilename = $id.".".$token;
              if(!move_uploaded_file($imgFile['tmp_name'],"./img/item/".$newfilename))
                  $view->error("Echec de l'upload");

              $item->img = $newfilename;
          }

          if(isset($_POST['itemDelete'])) $item->img = NULL;

          $nom = filter_var($_POST['itemName'],FILTER_SANITIZE_STRING);
          $descr = filter_var($_POST['itemDescr'],FILTER_SANITIZE_STRING);
          if(isset($_POST['itemUrl']))  $itemUrl = filter_var($_POST['itemUrl'],FILTER_VALIDATE_URL);
          $pot = $_POST['itemPotOrReserv'] == 'pot' ? true : false;


          if (strlen($nom)> 0) $item->nom = $nom;
          if (strlen($descr) > 0) $item->descr = $descr;
          $item->tarif = $_POST['itemTarif'];
          if(isset($_POST['itemUrl']) && filter_var($_POST['itemUrl'],FILTER_VALIDATE_URL)) $item->url = filter_var($itemUrl,FILTER_SANITIZE_URL);
          $item->cagnotte = $pot;
          try {
            $item->save();
            $view->addHeadMessage("Votre item a bien été modifié", 'good');
            $view->renderItem($item);
          }
          catch (QueryException $e) {
            $view->addHeadMessage("Votre item n'a pu être modifié", 'bad');
            $this->getFormItem($item);
          }
      }

      /**
       * Récupérer le formulaire de réservation
       * @param  $idItem    L'id de l'item à réserver
       * @param  $tokenItem Le token de l'item à réserver
       */
      function getItemBookingForm($idItem, $tokenItem){
          $view= new ItemView();
          $item = Item::where(['id' => $idItem , 'token' => $tokenItem])->first();
          $view->renderBookItemForm($item);

      }

      /**
       * Validation des données de réservation
       * @param  $idItem    L'id de l'item à réserver
       * @param  $tokenItem Le token de l'item à réserver
       */
      function bookItem($idItem, $tokenItem){
          $item = Item::where(['id' => $idItem , 'token' => $tokenItem])->first();
          $view= new ItemView();

          if (!isset($item)) $view->error('Item inexistant');
          if (!isset($_POST['booking_user']) || strlen($_POST['booking_user']) < 1){
              $view->addHeadMessage('Vous devez entrer votre nom','bad');
              $view->renderBookItemForm($item);
              return;
          }

          $name =  filter_var($_POST['booking_user'],FILTER_SANITIZE_STRING);
          $message =  filter_var($_POST['booking_message'],FILTER_SANITIZE_STRING);

          if(isset($item->booking_user)){
              $view->addHeadMessage("L'item est déjà réservé", 'bad');
              $view->renderItem($item);
              return;
          }

          $item->booking_user =  $name ;
          $item->message_booking = $message;

          try {
              $item->save();
              $view->addHeadMessage("L'item a bien été réservé",'good');
              $view->renderItem($item);
          }
          catch (QueryException $e) {
              $view->addHeadMessage("L'item n'a pu être réservé", 'bad');
              $view->renderItem($item);
          }
      }

      /**
       * Supprimer un item
       * @param  $idItem    L'id de l'item à supprimer
       * @param  $tokenItem Le token de l'item à supprimer
       */
      function delItem($idItem, $tokenItem) {
        $view = new ListView();
        $user = AccountController::getCurrentUser();
        $item = Item::where(['id' => $idItem , 'token' => $tokenItem])->first();
        $wishlist = $item->liste;
        if ($user == null || $wishlist->user_id != $user->id_account || $user->admin ==1) {
            $view = new ItemView();
            $view->addHeadMessage("Vous ne pouvez pas supprimer cet item", 'bad');
            $view->renderItem($item);
            return;
        }

        if(isset($item->booking_user)){
          $view = new ItemView();
          $view->addHeadMessage("Vous ne pouvez pas modifier un item déjà réservé.", 'bad');
          $view->renderItem($item);
          return;
        }

        if (!isset($item))
          $view->error('Item inexistant');

        $list = Wishlist::where('no','=',$item->liste_id)->first();

        if (isset($item) && $item->delete()) {
          $view->addHeadMessage('Votre item a bien été supprimé', 'good');
          $view->renderList($list,$user);
        } else {
          $view = new ItemView();
          $view->addHeadMessage("Votre item n'a pas pu être supprimé", 'bad');
          $view->renderFormItem($item, $list);
        }
      }

      /**
       * Supprimer l'image
       * @param  $idItem    L'id de l'item dont l'image va être supprimée
       * @param  $tokenItem Le token de l'item dont l'image va être supprimée
       */
      function delImg($idItem, $tokenItem){
        $view = new ItemView();

        $item =  Item::where(['id' => $idItem , 'token' => $tokenItem])->first();

        $user = AccountController::getCurrentUser();
        $wishlist = $item->liste;
        if ($user == null || $wishlist->user_id != $user->id_account || $user->admin ==1) {
            $view->addHeadMessage("Vous ne pouvez pas supprimer l'image de cet item", 'bad');
            $view->renderItem($item);
            return;
        }

        $item->img = NULL;
        try {
            $item->save();
            $view->addHeadMessage("Votre image a bien été supprimée.","good");
            $view->renderItem($item);
        }
        catch (QueryException $e) {
            $view->addHeadMessage("Votre image n'a pas pu être supprimée.","bad");
            $view->renderItem($item);
        }
      }
  }


?>
