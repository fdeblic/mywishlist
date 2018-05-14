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

      if (!isset($item)){
          $content .= "<h3> Oups ! </h3>";
          $content .= "<p> L'objet sélectionné n'existe pas !</p>";
          $content = str_replace ("\n", "\n\t", $content)."\n";
          $this->addContent($content);
          parent::render();
          return;
      }

      $url = $app->urlFor('list_aff',['id'=>$item->liste_id, 'token'=>$item->liste->token]);
      $urlDelete = $app->urlFor('item_del',['id'=>$item->id, 'token'=>$item->token]);
      $urlEdit = $app->urlFor('item_editGet',['id'=>$item->id, 'token'=>$item->token]);
      $urlDelImg = $app->urlFor('item_delImg',['id'=>$item->id, 'token'=>$item->token]);
      $urlPot = $app->urlFor('item_participate_post',['id'=>$item->id]);
      $urlReserv = $app->urlFor('item_reserv_get',['id'=>$item->id, 'token'=>$item->token]);



      if (isset($item->img)){
          $content .= "<img src=\"./img/$item->img\" alt=\"$item->nom\" >";
      }
      $content .= "<h1> $item->nom </h1>";
      $content .= "<p class=\"description-item\">$item->descr</p>";
      $content .= "<p>Tarif : $item->tarif</p>";
      if (isset($item->url)) $content .= "
      <p>Lien :
         <a href='$item->url' target=_blank>
          $item->url
         </a>
      </p>";
      if (isset($item->img)) $content .= "<p><a href='$urlDelImg'>Supprimer l'image</a></p>";
      if ($item->cagnotte) {
        $login = '';
        $max = $item->maxParticipation();
        if (isset($_SESSION['user_login']))
          $login = $_SESSION['user_login'];


        $content .= "
        <form action='$urlPot' method='POST'>
          <p>Participer à la cagnotte :</p>
          <p>Pseudo : <input type='text' name='name' placeholder='Votre nom' value='$login' required></p>
          <p>Montant restant : $max </p>
          <p>Montant : <input type='number' name='amount' placeholder='Montant (1 à $max €)' min='1' max='$max' required></p>
          <input type='submit' value='Participer'>
        </form>";
      } else {
        $content .= isset($item->booking_user) ?
        "<p> Cet item a déjà été réservé.</p>"
        :
        "<p>
        <a href='$urlReserv'>Réserver l'item
        </a>
        </p>";
      }

      $user = AccountController::getCurrentUser();
      $wishlist = $item->liste;

      /* Si l'utilisateur existe et est le créateur
      * ou s'il est admin
      * Alors il peut modifier l'item (ou le supprimer)
      */
     if(isset($user)){
         if ($wishlist->user_id == $user->id_account || $user->admin == 1){
             $content .= "<p><a href='$urlEdit'>Modifier l'item</a></p>";
         }
     }
      $content .= "<p><a href='$urlDelete'>Supprimer l'item </a></p>";
      $content .= "<p><a href='$url'>Retour à la liste</a></p>";
      $content .= "<div class='clear'></div>";


      $content = str_replace ("\n", "\n\t", $content)."\n";
      $this->addContent($content);
      parent::render();
    }


    /**
     * Génère le contenu HTML pour afficher un
     * item créé
     * @param $item l'item créé à afficher
     */
    function renderItemCreated($item) {
        $url = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$item->liste_id, 'token'=>$item->liste->token]);
        if ($item == null)
          error("Votre item n'a pas pu être créé");

        $this->addContent("<h1> L'item <i>$item->nom</i> a bien été créé ! </h1>");
        $this->addContent("
        <p>
            <a href='$url'>
                Retour à la liste.
            </a>
        </p>");
        parent::render();
    }

    /**
     * Génère le contenu HTML pour éditer un
     * item passé en paramètre
     * @param $item l'item à éditer
     */
    function renderEditItem($item) {
        $url = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$item->liste_id, 'token'=>$item->liste->token]);
        if ($item == null)
          error("Votre item n'a pas pu être modifié");
        $this->addContent("<h1> L'item <i>$item->nom</i> a bien été modifié ! </h1>");
        $this->addContent("
        <p>
            <a href='$url'>
                Retour à la liste.
            </a>
        </p>");
        parent::render();
    }

    /**
     * Génère le formulaire HTML pour éditer un
     * item passé en paramètre
     * @param $item l'item à éditer
     * @param @list la liste qui va recevoir l'item
     */
    function renderFormItem($item, $list){
        $user = AccountController::getCurrentUser();
        if ($user == null || $list->user_id != $user->id_account || $user->admin == 1){
          $this->addHeadMessage("Vous ne pouvez pas modifier cet item", 'bad');
          $this->renderItem($item);
          return;
        }

        $form = "";
        $nom = '';
        $descr = '';
        $tarif = '';
        $pot = false;
        $url_item = '';
        $img = '';
        $img_del = '';
        if (isset($item)) {
            $nom = $item->nom;
            $descr = $item->descr;
            $tarif = $item->tarif;
            $pot = $item->cagnotte;
            $url_item = $item->url;
            $img = $item->img;
            $url = \Slim\Slim::getInstance()->urlFor("item_editPost",[
              'id' => $item->id,
              'token' => $item->token]);
        } else {
            $url = \Slim\Slim::getInstance()->urlFor('list_addItemPost', [
              'id' => $list->no,
              'token' => $list->token]);
        }


        $valueSubmit = isset($item->id) ? "Modifier l'item" : "Créer l'item";

        $form =
        "<form action='$url' method='POST' enctype='multipart/form-data'>
          <input id='item_nom' name='item_nom' type='text' value='$nom' placeholder=\"Nom de l'item\">
          <textarea id='item_descr' name='item_descr' rows='10' cols='50' placeholder='Description'>$descr</textarea>
          <input id='item_tarif' name='item_tarif' type='text' value='$tarif' placeholder='Tarif'>
          <input type='text' name='url_item' value='$url_item' placeholder='Lien'\>
          <p><input id='item_pot' name='item_pot' type='radio' value='reserv' ".($pot?'':'checked').">Item à réserver
          <input id='item_pot' name='item_pot' type='radio' value='pot' ".($pot?'checked ':'').">Cagnotte sur l'item</p>
          <input id='item_img' name='item_img' type='file' value='$img' placeholder='Image'>";
        if (isset($item->img)){ $form .="<p> Supprimer l'image
          <input id='img_del' name='img_del' type='checkbox' value='del' ".($img_del?'':'')."> </p>";}
        $form .="<input type='submit' value='$valueSubmit'>
        </form>";

        $this->addContent($form);
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
            <input id='booking_user' name='booking_user' type='text' placeholder='Votre nom' required/>
            <textarea id='booking_message' name='booking_message' rows='10' cols='50' placeholder='Votre message'></textarea>
            <input type='submit' value='Réserver' />
        </form>";
        $this->addContent($form);
        parent::render();
    }

  }

?>
