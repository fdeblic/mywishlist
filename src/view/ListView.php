<?php
namespace mywishlist\view;
require_once 'vendor/autoload.php';
use \mywishlist\controller\AccountController as AccountController;


  class ListView extends GlobalView {

    function __construct() {
      parent::__construct();
    }

    /**
     *Génère le contenu HTML pour afficher une liste des listes passées en paramètre
     *@param $publicLists les listes à afficher
     */
    function renderLists($publicLists, $ownLists) {
      $urlCreateList = \Slim\Slim::getInstance()->urlFor('list_createGet');
      setlocale(LC_TIME, "fr_FR");
      $content = "\n";
      if (count($ownLists) == 0 && count($publicLists) == 0)
        $content = "  <h1> Pas de listes publiques </h1>\n";

      if (isset($ownLists) && count($ownLists) != 0) {
        $content .= "<h2> Listes : </h2>\n";
        $content .= "<ul>\n";
        foreach ($ownLists as $list) {
          if(strtotime($list->expiration) - time() >= 0){
            $url_list = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$list->no, 'token'=>$list->token]);
            $date = ucwords(utf8_encode(strftime('%d %B %Y', strtotime($list->expiration))));
            $content .= "  <li> <a href='$url_list'>$list->titre</a> <span class=\"expiration_date\"> Fin le : $date </span> </li>\n";
          }
        }
        $content .= "</ul>\n";
        $content .= "<h2> Listes terminées : </h2>\n";
        $content .= "<ul>\n";
        foreach ($ownLists as $list) {
          if(time() - strtotime($list->expiration) >= 0){
            $url_list = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$list->no, 'token'=>$list->token]);
            $date = ucwords(utf8_encode(strftime('%d %B %Y', strtotime($list->expiration))));
            $content .= "  <li> <a href='$url_list'>$list->titre</a> <span class=\"expiration_date\"> Fin le : $date </span></li>\n";
          }
        }
        $content .= "</ul>\n";
      }

      if (count($publicLists) == 0) {
        $content .= "  <p> (pas de liste publique) </p>\n";
      } else {
        $content .= "<h2> Listes publiques : </h2>\n";
        $content .= "<ul>\n";
        foreach ($publicLists as $list) {
          if(strtotime($list->expiration) - time() >= 0){
            $url_list = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$list->no, 'token'=>$list->token]);
            $date = ucwords(utf8_encode(strftime('%d %B %Y', strtotime($list->expiration))));
            $content .= "  <li> <a href='$url_list'>$list->titre</a> <span class=\"expiration_date\">Fin le : $date  </span> </li>\n";
          }
        }
        $content .= "</ul>\n";
      }
      if (AccountController::isConnected()){
        $content .= "<a href='$urlCreateList'> Créer une liste </a>";
      }
      $content = str_replace("\n", "\n  ", $content);
      $this->addContent($content);
      parent::render();
    }

    /**
     *Génère le contenu HTML pour afficher une liste en paramètre
     *@param $lists la liste à afficher
     *@param $user l'utilisateur demandant l'action
     */
    function renderList($list, $user) {
      $app = \Slim\Slim::getInstance();

      // Peut éditer si créateur ou admin
      $userCanEdit = isset($user) && ($list->user_id == $user->id_account || $user->admin == 1);

      // Si la liste est nulle
      if ($list == null) {
        $this->addHeadMessage("Erreur : la liste n'existe pas", 'bad');
        parent::render();
        return;
      }

      // URL utilisées
      $url_addItem = $app->urlFor('list_addItemGet',['id'=>$list->no, 'token'=>$list->token]);
      $url_share = $app->urlFor('list_aff', ['id'=>$list->no, 'token'=>$list->token]);
      $url_addMessage = $app->urlFor('list_addMsgPost',['id'=>$list->no, 'token'=>$list->token]);
      $url_deleteList = $app->urlFor('list_delete',['id'=>$list->no, 'token'=>$list->token]);
      $url_modifyList = $app->urlFor('list_editGet',['id'=>$list->no, 'token'=>$list->token]);
      $urlFb = 'https://www.facebook.com/sharer/sharer.php?u=' . $_SERVER['SERVER_NAME'] . $url_share;
      $urlTw = 'https://twitter.com/home?status=' . $_SERVER['SERVER_NAME'] . $url_share;
      $urlGgPlus = 'https://plus.google.com/share?url=' . $_SERVER['SERVER_NAME'] . $url_share;

      $content  = "<h1> $list->titre </h1>\n";
      $content .= "<p> $list->description</p>\n";
      $content .= "<!-- Items -->\n";
      $content .= "<ol>\n";

      // Affiche les items
      foreach ($list->items as $item) {
        // URLs
        $url_rendItem = $app->urlFor('item_aff',['id'=>$item->id, 'token'=>$item->token]);
        $url_delItem  = $app->urlFor('item_del',['id'=>$item->id, 'token'=>$item->token]);
        $etatItem = "(Non réservé)";
        // Cagnotte ou réservation
        if ($item->cagnotte) {
          // Cagnotte
          $percent = round($item->currentPotAmount() / $item->tarif * 100);
          $etatItem = "(Cagnotte : $percent%)";
        } else {
            $etatItem = "(Non réservé)";
            // Affiche l'état de la réservation
            if (isset($item->booking_user)) {
              if (strtotime($item->liste->expiration) > strtotime('now') || !$userCanEdit)
                $etatItem = "(Réservé)";
              else
                $etatItem = "(Réservé par " . $item->booking_user . " : " . $item->message_booking . ")";
            }
        }
        // Affiche l'item
        $content .= "  <li>\n";
        $content .= "    <a href='$url_rendItem'>$item->nom</a>\n";
        $content .= "    <span> $etatItem </span>\n";
        if ($userCanEdit)
          if(!isset($item->booking_user))
            $content .="    <ul><li><a href='$url_delItem' onclick=\"return confirm('Etes-vous sûr de vouloir supprimer l\'item?');\">Supprimer</a> </li></ul>\n";
        $content .= "  </li>\n";
      }

      $content .= "</ol>\n\n";

      $content .= "\n<!-- List modifiers -->\n";
      if ($userCanEdit) {
        $content .= "<a href='$url_addItem'> Créer un item </a><br>\n";
        $content .= "<a href='$url_addMessage'> Ajouter un message </a><br>\n";
        $content .= "<a href='$url_modifyList'> Modifier la liste </a><br>\n";
        $content .= "<a href='$url_deleteList'  onclick=\"return confirm('Etes-vous sûr de vouloir supprimer cette liste ?');\"> Supprimer la liste </a><br>\n";

      }

      // Liens de partage
      $content .= "\n<!-- Sharing links -->\n";
      $content .= "<p> Partager la liste :\n";
      $content .= "  <a title='Partager sur Facebook' href='$urlFb'>Facebook</a>\n";
      $content .= "  <a title='Partager sur Twitter' href='$urlTw'>Twitter</a>\n";
      $content .= "  <a title='Partager sur Google+' href='$urlGgPlus'>Google+</a>\n";
      $content .= "</p>\n";

      // Affiche les messages de la liste
      $messages = $list->messages()->orderBy('created_at','DESC')->get();
      $content .= "\n<!-- News feed -->\n";
      $content .= "<div>";

      foreach($messages as $message){
          $creator = $message->creator;
          $date_string = date("d/m/y", strtotime($message->created_at));
          $content .= "\n  <div class='message'>\n";
          $content .= "    <p class='mess-creator'>\n";
          $content .= "      <span>$creator->nom $creator->prenom, $date_string</span>\n";
          $content .= "    </p>\n";
          $content .= "    <p class='mess-body'> $message->body </p>\n";
          $content .= "  </div>\n";
      }
      $content .= "</div>";

      $content = str_replace("\n", "\n  ", $content)."\n";
      $this->addContent($content);
      parent::render();
    }


    /**
     *Génère le contenu HTML pour afficher une liste passée en paramètre
     *@param $list la liste à afficher
     */
    function renderFormList($list) {
      $editing = isset($list->no);
        if ($editing)
            $url = \Slim\Slim::getInstance()->urlFor('list_editPost',['id'=>$list->no, 'token'=>$list->token]);
        else
            $url = \Slim\Slim::getInstance()->urlFor('list_createPost');

        $submit = $editing ? "Modifier la liste" : "Créer la liste";
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

        $form  = "\n<!-- List editor -->\n";
        $form .= "<form action='$url' method='POST'>\n";
        $form .= "  <input id='list_title' name='list_title' type='text' value='$titre' placeholder='Titre de la liste' pattern=\".{5,50}\" maxlength='50' required >\n";
        $form .= "  <textarea id='list_descr' name='list_descr' rows='10' cols='50' placeholder='Description' required>$descr</textarea>\n";
        $form .= "  <div class='form-date'>\n";
        $form .= "    <p> Date d'expiration </p>\n";
        $form .= "    <input id='list_expiration' name='list_expiration' type='date' value='$expiration' required>\n";
        $form .= "  </div>\n";
        if ($editing) {
          $form .= "  <label><input type='checkbox' name='list_public' $checked>Liste publique</label>\n";
        }
        $form .= "  <input type='submit' value='$submit'>\n";
        $form .= "</form>";

        $form = str_replace("\n", "\n  ", $form)."\n";
        $this->addContent($form);
        parent::render();
    }

    /**
     *Génère le contenu HTML pour afficher les créateurs de listes publiques
     */
    public function renderCreators($creators) {
      $content  = "\n<!-- Public list creators -->\n";
      $content .= "<h1> Créateurs de listes publiques : </h1>\n";
      $content .= "<ul>\n";

      if (count($creators) == 0) {
        $content .= "  <li> (pas de liste publique) </li>\n";
      } else {
        foreach ($creators as $key => $creator) {
          $content.= '  <li> ' . $creator->prenom . " </li>\n";
        }
      }

      $content .= "</ul>\n";
      $content = str_replace("\n", "\n  ", $content);
      $this->addContent($content);
      parent::render();
    }
  }
