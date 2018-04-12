<?php
namespace mywishlist\view;

  class ItemView {
    static function render(){
      echo 'function render()';
    }

    static function displayItems($items){
      echo 'function displayItems()';
    }

    /* Génère le contenu HTML pour
    afficher un item passé en paramètre */
    static function displayItem($item){
      if ($item == null){
          $content = "<h3> Oups ! </h3>\n";
          $content = "<p> L'objet sélectionné n'existe pas !</p>\n";
          $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
          parent::render();
          return;
      }
      $content = "\t<h1> $item->titre </h1>\n"
      $content .= "<ol>\n";
      $content .= "\t<li> $item->nom </li>\n";
      $content .= "\t<li> $item->nom </li>\n";
      $content .= "</ol>";

      $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
      parent::render();

    }

  }

?>
