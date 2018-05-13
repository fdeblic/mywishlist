<?php
namespace mywishlist\view;
require_once 'vendor/autoload.php';


  class ListView extends GlobalView {

    function __construct() {
      parent::__construct();
    }

    /* Génère le contenu HTML pour afficher une
    liste des listes passées en paramètre */
    function renderLists($lists) {
      $content  = "<h1> Listes : </h1>";
      $content .= "<ol>";
      foreach ($lists as $list) {
        $url_list = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$list->no, 'token'=>$list->token]);
        $url_addList = \Slim\Slim::getInstance()->urlFor('list_createGet');
        $content .= "
        <li>
            <a href='$url_list'>
            $list->titre
            </a>
        </li>";
      }
      $content .= "</ol>";

      $content .= "<a href=\"$url_addList\">Ajouter une liste</a>";

      $content = str_replace("\n", "\n\t", $content)."\n";
      $this->addContent($content);
      parent::render();
    }

    /* Génère le contenu HTML pour afficher une
    liste passée en paramètre */
    function renderList($list,$user) {
      $app = \Slim\Slim::getInstance();
        if ($list == null){
            $content = "<h3>Oups !</h3>";
            $content .= "<p>La liste n'existe pas !</p>";
            $content = str_replace("\n", "\n\t", $content)."\n";
            $this->addContent($content);
            parent::render();
            return;
        }
        $url_addItem = $app->urlFor('list_addItemGet',['id'=>$list->no, 'token'=>$list->token]);
        $url_share = $app->urlFor('list_aff', ['id'=>$list->no, 'token'=>$list->token]);
        $content  = "<h1> $list->titre</h1>";
        $content .= "<ol>";
        foreach($list->items as $item){
            $url_rendItem = $app->urlFor('item_aff',['id'=>$item->id, 'token'=>$item->token]);
            $url_delItem = $app->urlFor('item_del',['id'=>$item->id, 'token'=>$item->token]);
            $book_status="(Non réservé)";
            if(isset($item->booking_user))
                if(strtotime($item->liste->expiration)> strtotime('now')){
                    $book_status="(Réservé)";
                }
                else {
                        $book_status="(Réservé par " . $item->booking_user . ": " . $item->message_booking . ")";
                }
            $content .= "
            <li>
                <a href='$url_rendItem'>
                    $item->nom\t
                </a>
                <span>$book_status</span>
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

        $url_addMessage = $app->urlFor('list_addMsgPost',['id'=>$list->no, 'token'=>$list->token]);
        $url_deleteList = $app->urlFor('list_delete',['id'=>$list->no, 'token'=>$list->token]);
        $content .= "
        <p>
            <a href='$url_addMessage'>Ajouter un message</a>
        </p>
        ";
        $url_modifyList = $app->urlFor('list_editGet',['id'=>$list->no, 'token'=>$list->token]);
        /* Si l'utilisateur est le créateur
        * ou s'il est admin
        * Alors il peut modifier l'item (ou le supprimer)
        */
        if (isset($user)){
            if ($list->user_id == $user->id_account || $user->admin == 1) {
                $content .= "<p><a href=\"$url_modifyList\">Modifier la liste</a></p>";
                $content .= "<p><a href=\"$url_deleteList\">Supprimer la liste</a></p>";
            }
        }
        $content .= "<p> Partager la liste : copier et envoyer le lien suivant : <i><u>http://".$_SERVER['SERVER_NAME'].$url_share."</u></i></p>";

        $messages = $list->messages()->get();

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

        $content = str_replace("\n", "\n\t", $content)."\n";
        $this->addContent($content);
        parent::render();
    }


    /* Génère le contenu HTML pour afficher une
    liste passée en paramètre */
    function renderFormList($list) {
        $url = '';
        if (isset($list->no)){
            $url = \Slim\Slim::getInstance()->urlFor('list_editPost',['id'=>$list->no, 'token'=>$list->token]);
        }
        else{
            $url = \Slim\Slim::getInstance()->urlFor('list_createPost');
        }

        $submit = isset($list->no) ? "Modifier la liste" : "Créer la liste";
        $form = "";
        $titre = '';
        $descr = '';
        $public = 0;
        $expiration = '1996-05-23';
        if ($list != null) {
            $titre = $list->titre;
            $descr = $list->description;
            $expiration = $list->expiration;
            $public = $list->public;
        }
        $checked = $public ? 'checked' : '';
        $form =
        "<form action='$url' method='POST'>
          <input id='list_title' name='list_title' type='text' value='$titre' placeholder='Titre de la liste' required>
          <textarea id='list_descr' name='list_descr' rows=\"10\" cols=\"50\" placeholder='Description' required>$descr</textarea>
          <div class='form-date'>
            <p> Date d'expiration </p>
            <input id='list_expiration' name='list_expiration' type='date' value='$expiration' required>
          </div>
          <label><input type=\"checkbox\" name=\"list_public\" $checked>Liste publique</label>
          <input type='submit' value='$submit'>
        </form>";

        $content = str_replace("\n", "\n\t", $form)."\n";
        $this->addContent($form);
        parent::render();
    }

    public function renderCreators($creators) {
      $content = '<p> Créateurs de listes publiques </p>';
      foreach ($creators as $key => $creator) {
        $content.= '<li>' . $creator->prenom . "</li><br>\n";
      }
      $content = str_replace("\n", "\n\t", $content)."\n";
      $this->addContent($content);
      parent::render();
    }
  }
