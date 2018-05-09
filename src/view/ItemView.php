<?php
namespace mywishlist\view;
use \mywishlist\models\WishList as WishList;

  class ItemView extends GlobalView{
    function __construct() {
      parent::__construct();
    }

    /* Génère le contenu HTML pour
    afficher un item passé en paramètre */
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
      $urlReserv = '';



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
          <p>Montant : <input type='number' name='amount' placeholder='Montant (1 à $max €)' min='1' max='$max' required></p>
          <input type='submit' value='Participer'>
        </form>";
      } else {
        $content .= "<p><a href='$urlReserv'>Réserver l'item</a></p>";
      }
      $content .= "<p><a href='$urlEdit'>Modifier l'item</a></p>";
      $content .= "<p><a href='$urlDelete'>Supprimer l'item </a></p>";
      $content .= "<p><a href='$url'>Retour à la liste</a></p>";
      $content .= "<div class='clear'></div>";


      $content = str_replace ("\n", "\n\t", $content)."\n";
      $this->addContent($content);
      parent::render();
    }

    /* Génère le contenu HTML pour afficher un
    item passé en paramètre */
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

    /* Génère le contenu HTML pour afficher un
    item édité passé en paramètre */
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

    function renderFormItem($item, $list){
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


  }

?>
