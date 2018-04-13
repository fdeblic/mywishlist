<?php
namespace mywishlist\view;

  class ItemView extends GlobalView{
    /* Génère le contenu HTML pour
    afficher un item passé en paramètre */
    function renderItem($item){
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
      $content .= "<p><a href=\"./liste/$item->liste_id\">Retour à la liste</a></p>";
      $content .= "<div class=\"clear\"></div>";


      $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
      parent::render();
    }

    /* Génère le contenu HTML pour afficher un
    item passé en paramètre */
    function renderItemCreated($item) {
        if ($item == null)
          error("Votre item n'a pas pu être créé");

        $_SESSION['content']  = "<h1> L'item <i>$item->nom</i> a bien été créé ! </h1>";
        $_SESSION['content'] .= "
        <p>
            <a href=\"./liste/$item->liste_id\">
                Retour à la liste.
            </a>
        </p>";
        parent::render();
    }

    function renderFormItem($item, $id){
        $form = "";
        $nom = '';
        $descr = '';
        $tarif = '';
        if ($item != null) {
            $nom = $item->nom;
            $descr = $item->description;
            $tarif = $item->tarif;
        }
        $form =
        "<form action='./item/creer/$id' method='POST'>
          <input id='item_nom' name='item_nom' type='text' value='$nom' placeholder=\"Nom de l'item\">
          <textarea id='item_descr' name='item_descr' rows=\"10\" cols=\"50\" value='$descr' placeholder='Description'></textarea>
          <input id='item_tarif' name='item_tarif' type='text' value='$tarif' placeholder='Tarif'>
          <input type='submit' value=\"Créer l'item\">
        </form>";

        $_SESSION['content']  = $form;
        parent::render();
    }

  }

?>
