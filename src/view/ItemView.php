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

    function renderFormItem($item){
      //TODO
    }

  }

?>
