<?php
namespace mywishlist\view;

  class ListView extends GlobalView {

    function __construct() {
    }

    /* Génère le contenu HTML pour afficher une
    liste des listes passées en paramètre */
    function renderLists($lists) {
      $content  = "\t<h1> Listes : </h1>\n";
      $content .= "<ol>\n";
      foreach ($lists as $list) {
        $content .= "\t
        <li>
            <a href=\"./liste/$list->no\">
            $list->titre
            </a>
        </li>\n";
      }
      $content .= "</ol>";

      $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
      parent::render();
    }

    /* Génère le contenu HTML pour afficher une
    liste passée en paramètre */
    function renderList($list) {
        if ($list == null){
            $content = "<h3>Oups !</h3>\n";
            $content .= "<p>La liste n'existe pas !</p>\n";
            $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
            parent::render();
            return;
        }
        $content  = "\t<h1> $list->titre</h1>\n";
        $content .= "<ol>\n";
        foreach($list->items as $item){
            $content .= "\t<li> $item->nom </li>\n";
        }
        $content .= "</ol>";

        $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
        parent::render();
    }


  }
