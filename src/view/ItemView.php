<?php
namespace mywishlist\view;
use \mywishlist\controller\AccountController as AccountController;

  class ItemView extends GlobalView{
    function __construct() {
      parent::__construct();
    }

    /**
    * Génère le contenu HTML pour aficher un
    * item passé en paramètre
    * @param $item l'item à éditer
    */
    function renderItem($item){
      $app = \Slim\Slim::getInstance();
      $content = "";

      // Item null
      if (!isset($item)){
          $content .= "<h3> Oups ! </h3>";
          $content .= "<p> L'objet sélectionné n'existe pas !</p>";
          $content = str_replace ("\n", "\n  ", $content)."\n";
          $this->addContent($content);
          parent::render();
          return;
      }

      // Droit de modification si créateur ou admin
      $user = AccountController::getCurrentUser();
      $canEdit = isset($user) && ($item->liste->user_id == $user->id_account || $user->admin == true);

      // Les URL utilisées
      $urlList = $app->urlFor('list_aff',['id'=>$item->liste_id, 'token'=>$item->liste->token]);
      $urlDelete = $app->urlFor('item_del',['id'=>$item->id, 'token'=>$item->token]);
      $urlEdit = $app->urlFor('item_editGet',['id'=>$item->id, 'token'=>$item->token]);
      $urlDelImg = $app->urlFor('item_delImg',['id'=>$item->id, 'token'=>$item->token]);
      $urlPot = $app->urlFor('item_participate_post',['id'=>$item->id]);
      $urlReserv = $app->urlFor('item_reserv_get',['id'=>$item->id, 'token'=>$item->token]);

      // Affichage de l'item
      $content .= "\n<!-- Item -->\n";
      if (isset($item->img)) $content .= "<img src='./img/item/$item->img' alt='$item->nom'>\n";
      $content .= "<h1> $item->nom </h1>\n";
      $content .= "<p class='description-item'> $item->descr </p>\n";
      $content .= "<p> Tarif : $item->tarif € </p>\n";
      if (isset($item->url)) $content .= "<p> Plus d'infos : <a href='$item->url' target=_blank> $item->url </a></p>\n";

      $content .= "\n<!-- Actions -->\n";

      // Cagnotte ou réservation
      if ($item->cagnotte) {
        // Cagnotte
        $max = $item->maxPotParticipation();
        if ($max != 0) {
          $login = '';
          if (AccountController::isConnected()) $login = AccountController::getLogin();
          $content .= "\n<!-- Cagnotte -->\n";
          $content .= "<form action='$urlPot' method='POST'>\n";
          $content .= "  <p>Participer à la cagnotte :</p>\n";
          $content .= "  <p>Pseudo : <input type='text' name='name' placeholder='Votre nom' value='$login' required></p>\n";
          $content .= "  <p>Montant restant : $max € </p>\n";
          $content .= "  <p>Montant : <input type='number' name='amount' step='0.01' placeholder='Montant (1 à $max €)' min='1' max='$max' required></p>\n";
          $content .= "  <input type='submit' value='Participer' onclick=\"return confirm('Vous ne pourrez pas annuler votre participation par la suite.');\">\n";
          $content .= "</form>\n";
        } else {
          $content .= "<p> Cagnotte : complétée avec succès ! </p>\n";
        }
      }
      else {
        // Reservation
        if (isset($item->booking_user))
          $content .= "<p> Cet item est réservé par $item->booking_user </p>\n";
        else
          $content .= "<a href='$urlReserv'>Réserver l'item </a> <br>\n";
      }

      // Bouton de retour
      $content .= "<a href='$urlList'>Retour à la liste</a><br>\n";

      // Actions d'édition
      if ($canEdit) {
        $content .= "\n<!-- Edition -->\n";
        $content .= "<p> - Edition - </p>\n";
        if(isset($item->booking_user)){
          $content .= "<br> Un item réservé ne peut être modifié ou réservé.</br>\n";
          if (isset($item->img))
            $content.= "<br> Une image d'un item réservé ne peut être supprimée.</br>\n";
        }
        else{
          $content .= "<a href='$urlEdit'>Modifier l'item</a><br>\n";
          $content .= "<a href='$urlDelete'  onclick=\"return confirm('Etes-vous sûr de vouloir supprimer l\'item?');\">Supprimer l'item </a><br>\n";
          if (isset($item->img))
            $content .= "<a href='$urlDelImg'  onclick=\"return confirm('Etes-vous sûr de vouloir supprimer l\'image de cet item?');\">Supprimer l'image</a><br>\n";
      }

      $content = str_replace ("\n", "\n  ", $content);
      $this->addContent($content);
      parent::render();
    }
  }

    /**
     * Génère le formulaire HTML pour éditer un
     * item passé en paramètre
     * @param $item l'item à éditer
     * @param @list la liste qui va recevoir l'item
     */
    function renderFormItem($item, $list){
        $user = AccountController::getCurrentUser();
        if (!isset($user) || $list->user_id != $user->id_account && $user->admin == false){
          $this->addHeadMessage("Vous ne pouvez pas modifier cet item", 'bad');
          $this->renderItem($item);
          return;
        }

        $form = ''; $nom = ''; $descr = ''; $tarif = ''; $pot = false; $url_item = ''; $img = ''; $itemDelete = '';
        if (isset($item)) {
            $nom = $item->nom;
            $descr = $item->descr;
            $tarif = $item->tarif;
            $pot = $item->cagnotte;
            $url_item = $item->url;
            $img = $item->img;
            $url = \Slim\Slim::getInstance()->urlFor("item_editPost",['id' => $item->id, 'token' => $item->token]);
        } else {
            $url = \Slim\Slim::getInstance()->urlFor('list_addItemPost', ['id' => $list->no, 'token' => $list->token]);
        }


        $valueSubmit = isset($item->id) ? "Modifier l'item" : "Créer l'item";

        $form  = "<form action='$url' method='POST' enctype='multipart/form-data'>\n";
        $form .= "  <input id='name' name='name' type='text' value='$nom' placeholder=\"Nom de l'item\" pattern=\".{3,50}\" maxlength='50' required>\n";
        $form .= "  <textarea id='imgDescr' name='imgDescr' rows='10' cols='50' placeholder='Description'>$descr</textarea>\n";
        $form .= "  <input id='itemTarif' name='itemTarif' type='text' value='$tarif' placeholder='Tarif'>\n";
        $form .= "  <input type='url' name='url_item' value='$url_item' placeholder='Lien'>\n";
        $form .= "  <p>\n";
        $form .= "    <input id='itemPotOrReserv' name='itemPotOrReserv' type='radio' value='reserv' ".($pot?'':'checked').">Item à réserver\n";
        $form .= "    <input id='itemPotOrReserv' name='itemPotOrReserv' type='radio' value='pot' ".($pot?'checked ':'').">Cagnotte sur l'item\n";
        $form .= "  </p>\n";
        $form .= "  <input id='itemImgFile' name='itemImgFile' type='file' value='$img' placeholder='Image'>\n";
        if (isset($item->img)) {
          $form .= "  <p> Supprimer l'image <input id='itemDelete' name='itemDelete' type='checkbox'> </p>\n";
        }
        $form .= "  <input type='submit' value=\"$valueSubmit\">\n";
        $form .= "</form>\n";

        $this->addContent(str_replace ("\n", "\n  ", $form));
        parent::render();
    }

    /**
     * Génère le contenu HTML pour réserver un
     * item passé en paramètre
     * @param $item l'item à réserver
     */
    function renderBookItemForm($item){
        $form = '';
        $name = '';
        $message = '';

        $urlBookController = \Slim\Slim::getInstance()->urlFor("item_reserv_post",[
          'id' => $item->id,
          'token' => $item->token]);
        $form =
        "<form action='$urlBookController' method='POST' enctype='multipart/form-data'>
            <input id='booking_user' name='booking_user' type='text' placeholder='Votre nom' pattern=\".{3,30}\" maxlength='30' required/>
            <textarea id='booking_message' name='booking_message' rows='10' cols='50' placeholder='Votre message'></textarea>
            <input type='submit' value='Réserver' onclick=\"return confirm('Une fois l\'item réservé, vous ne pourrez pas annuler votre réservation. Etes-vous sûr de vouloir réserver cet item ?');\"/>
        </form>";
        $this->addContent($form);
        parent::render();
    }

  }

?>
