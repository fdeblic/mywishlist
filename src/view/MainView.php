<?php
namespace mywishlist\view;

  class MainView extends GlobalView {

    function __construct() {
      parent::__construct();
    }

    /**
     *Génère le contenu HTML pour afficher la page d'accueil
     */
    function render() {
        $content = "<h1>Bienvenue en terre sainte.</h1>\n";
        $content .=
"<p>
    Notre catalogue est à votre disposition.
    Vous pouvez avoir accès aux listes publiques,
    mais également aux listes privées auxquelles vous auriez été invités.
    Pour cela, merci de vous connecter.
</p>";
        $content = str_replace ("\n", "\n  ", $content);
        $this->addContent($content);
        parent::render();
    }
 }
