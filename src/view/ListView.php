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
        $content .= "\t<li> ". $list->titre . " </li>\n";
      }
      $content .= "</ol>";

      $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
      parent::render();
    }

  }
