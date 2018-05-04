<?php
namespace mywishlist\view;

  class ItemView extends GlobalView{
    /* Génère le contenu HTML pour
    afficher un item passé en paramètre */
    function renderItem($item){
      $app = \Slim\Slim::getInstance();
        $url = $app->urlFor('list_aff',['id'=>$item->liste_id]);
        $urlDelete = $app->urlFor('item_del',['id'=>$item->id]);
        $urlEdit = $app->urlFor('item_editGet',['id'=>$item->id]);
        $content = "";
      if (!isset($item)){
          $content .= "<h3> Oups ! </h3>";
          $content .= "<p> L'objet sélectionné n'existe pas !</p>";
          $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
          parent::render();
          return;
      }


      if (isset($item->img)){
          $content .= "<img src=\"./img/$item->img\" alt=\"$item->nom\" >";
      }
      $content .= "<h1> $item->nom </h1>";
      $content .= "<p class=\"description-item\">$item->descr</p>";
      $content .= "<p>Tarif : $item->tarif</p>";
      $content .= "<p><a href='$url'>Retour à la liste</a></p>";
      $content .= "<p><a href='$urlEdit'>Modifier l'item</a></p>";
      $content .= "<p><a href='$urlDelete'>Supprimer l'item </a></p>";
      $content .= "<div class=\"clear\"></div>";


      $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
      parent::render();
    }

    /* Génère le contenu HTML pour afficher un
    item passé en paramètre */
    function renderItemCreated($item) {
        $url = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$item->liste_id]);
        if ($item == null)
          error("Votre item n'a pas pu être créé");

        $_SESSION['content']  = "<h1> L'item <i>$item->nom</i> a bien été créé ! </h1>";
        $_SESSION['content'] .= "
        <p>
            <a href='$url'>
                Retour à la liste.
            </a>
        </p>";
        parent::render();
    }

    /* Génère le contenu HTML pour afficher un
    item édité passé en paramètre */
    function renderEditItem($item) {
        $url = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$item->liste_id]);
        if ($item == null)
          error("Votre item n'a pas pu être modifié");

        $_SESSION['content']  = "<h1> L'item <i>$item->nom</i> a bien été modifié ! </h1>";
        $_SESSION['content'] .= "
        <p>
            <a href='$url'>
                Retour à la liste.
            </a>
        </p>";
        parent::render();
    }

    function renderFormItem($item,$list_id){
        $url = '';
        if (isset($item->id)) {
            $url = \Slim\Slim::getInstance()->urlFor("item_editPost",['id'=>$item->id]);
        }
        else {
            $url = \Slim\Slim::getInstance()->urlFor('list_addItemPost',['id'=>$list_id]);
        }
        $submit = isset($item->id) ? "Modifier l'item" : "Créer l'item";

        $form = "";
        $nom = '';
        $descr = '';
        $tarif = '';
        $pot = false;
        $img = '';

        if (isset($item)) {
            $nom = $item->nom;
            $descr = $item->descr;
            $tarif = $item->tarif;
            $pot = $item->cagnotte;
            $img = $item->img;

        }
        $form =
        "<form action='$url' method='POST' enctype='multipart/form-data'>
          <input id='item_nom' name='item_nom' type='text' value='$nom' placeholder=\"Nom de l'item\">
          <textarea id='item_descr' name='item_descr' rows=\"10\" cols=\"50\" placeholder='Description'>$descr</textarea>
          <input id='item_tarif' name='item_tarif' type='text' value='$tarif' placeholder='Tarif'>
          <p><input id='item_pot' name='item_pot' type='radio' value='reserv' ".($pot?'':'checked').">Item à réserver
          <input id='item_pot' name='item_pot' type='radio' value='pot' ".($pot?'checked ':'').">Cagnotte sur l'item</p>
          <input id='item_img' name='item_img' type='file' value='$img' placeholder='Image'>
          <input type='submit' value=\"$submit\">
        </form>";

        $_SESSION['content']  = $form;
        parent::render();
    }

    function renderDelItem($item){
      $url = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$item->liste_id]);
      if ($item == null)
        error("Votre item n'a pas pu être supprimé.");

      $_SESSION['content'] = "<h1> L'item a bien été supprimé. </h1";
      $_SESSION['content'] .= "
      <p>
          <a href='$url'>
              Retour à la liste.
          </a>
      </p>";
      parent::render();

    }

  }

?>
