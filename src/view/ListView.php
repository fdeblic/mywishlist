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

    /* Génère le contenu HTML pour afficher une
    liste passée en paramètre */
    function renderListCreated($list) {
        if ($list == null)
          error("Votre liste n'a pas pu être créée");

        $_SESSION['content']  = "<h1> La liste <i>$list->titre</i> a bien été créée ! </h1>";
        parent::render();
    }

    /* Génère le contenu HTML pour afficher une
    liste passée en paramètre */
    function renderFormulaireList($list) {
        $form = "";
        $titre = '';
        $descr = '';
        $expiration = '1996-05-23';
        if ($list != null) {
            $titre = $list->titre;
            $descr = $list->description;
            $expiration = $list->expiration;
        }
        $form =
        "<form action='./liste/creer' method='POST'>
          <input id='list_title' name='list_title' type='text' value='$titre' placeholder='Titre de la liste'>
          <input id='list_descr' name='list_descr' type='text' value='$descr' placeholder='Description...'>
          <input id='list_expiration' name='list_expiration' type='date' value='$expiration'>
          <input type='submit' value='Créer la liste'>
        </form>";

        $_SESSION['content']  = $form;
        parent::render();
    }
  }
