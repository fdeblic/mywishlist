<?php
namespace mywishlist\view;
require_once 'vendor/autoload.php';
use \mywishlist\models\Message as Message;

  class ListView extends GlobalView {

    function __construct() {
    }

    /* Génère le contenu HTML pour afficher une
    liste des listes passées en paramètre */
    function renderLists($lists) {
      $content  = "<h1> Listes : </h1>";
      $content .= "<ol>";
      foreach ($lists as $list) {
        $url = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$list->no]);
        $content .= "
        <li>
            <a href='$url'>
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
      $app = \Slim\Slim::getInstance();
        if ($list == null){
            $content = "<h3>Oups !</h3>";
            $content .= "<p>La liste n'existe pas !</p>";
            $_SESSION['content'] = str_replace ("\n", "\n\t", $content)."\n";
            parent::render();
            return;
        }
        $url_addItem = $app->urlFor('list_addItemGet',['id'=>$list->no]);
        $content  = "<h1> $list->titre</h1>";
        $content .= "<ol>";
        foreach($list->items as $item){
            $url_rendItem = $app->urlFor('item_aff',['id'=>$item->id]);
            $url_delItem = $app->urlFor('item_del',['id'=>$item->id]);
            $content .= "
            <li>
                <a href='$url_rendItem'>
                    $item->nom\t
                </a>
                <ul>
                  <li>
                    <a href='$url_delItem'>
                      Supprimer
                      </a>
                  </li>
                </ul>
            </li>";
        }
        $content .= "</ol>";

        $content .= "
        <p>
            <a href='$url_addItem'>Créer un item</a>
        </p>";

        $url_addMessage = $app->urlFor('list_addMsgPost',['id'=>$list->no]);
        $content .= "
        <p>
            <a href='$url_addMessage'>Ajouter un message</a>
        </p>
        ";

        $messages = Message::where('list_id','=',$list->no)->get();

        foreach($messages as $message){
            $creator = $message->creator;
            $date_string = date("d/m/y", strtotime($message->created_at));
            $content .= "
            <div class=\"message\">
                <p class=\"mess-creator\">
                    <span>$creator->nom $creator->prenom, $date_string</span>
                </p>
                <p class=\"mess-body\"> $message->body </p>
            </div>
            ";
        }

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
        $url = \Slim\Slim::getInstance()->urlFor('list_createPost');
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
        "<form action='$url' method='POST'>
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
