<?php
namespace mywishlist\view;

  class MainView extends GlobalView {

    function __construct() {
    }

    /* Génère le contenu HTML pour afficher une
    liste des listes passées en paramètre */
    function render() {
        $content = "<h1>Bienvenue en terre sainte.</h1>";
        $content .= "<p>
            Notre catalogue est à votre disposition.
            Vous pouvez avoir accès aux listes publiques,
            mais également aux listes privées auxquelles vous auriez été invités.
            Pour cela, merci de vous connecter.
        </p>";
        $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
        parent::render();
    }


 }
