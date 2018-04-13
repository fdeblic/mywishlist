<?php
namespace mywishlist\view;

  class ListView extends GlobalView {

    function __construct() {
    }

    /* Génère le contenu HTML pour afficher une
    liste des listes passées en paramètre */
    function renderLists($lists) {
      $content  = "<h1> Listes : </h1>";
      $content .= "<ol>";
      foreach ($lists as $list) {
        $content .= "
        <li>
            <a href=\"./liste/$list->no\">
            $list->titre
            </a>
        </li>";
      }
      $content .= "</ol>";

      $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
      parent::render();
    }

    /* Génère le contenu HTML pour afficher une
    liste passée en paramètre */
    function renderList($list) {
        if ($list == null){
            $content = "<h3>Oups !</h3>";
            $content .= "<p>La liste n'existe pas !</p>";
            $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
            parent::render();
            return;
        }
        $content  = "<h1> $list->titre</h1>";
        $content .= "<ol>";
        foreach($list->items as $item){
            $content .= "
            <li>
                <a href=\"./items/$item->id\">
                    $item->nom
                </a>
            </li>";
        }
        $content .= "</ol>";

        $content .= "
        <p>
            <a href=\"./item/creer/$list->no\">Créer un item</a>
        </p>";


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
    function renderFormList($list) {
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
          <textarea id='list_descr' name='list_descr' rows=\"10\" cols=\"50\" value='$descr' placeholder='Description'></textarea>
          <div class='form-date'>
            <p> Date d'expiration </p>
            <input id='list_expiration' name='list_expiration' type='date' value='$expiration'>
          </div>
          <input type='submit' value='Créer la liste'>
        </form>";

        $_SESSION['content']  = $form;
        parent::render();
    }
  }
