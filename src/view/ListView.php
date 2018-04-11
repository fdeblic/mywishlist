<?php
namespace mywishlist\view;

  class ListView extends GlobalView {

    function __construct() {
    }

    function renderLists($lists) {
      $content = "<p> Listes : </p>\n";
      $content .= "<ol>\n";
      foreach ($lists as $list) {
        $content .= "\t<li> ". $list->titre . " </li>\n";
      }
      $content .= "</ol>";

      $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
      parent::render();
    }

  }
