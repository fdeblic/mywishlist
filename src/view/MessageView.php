<?php
namespace mywishlist\view;

  class MessageView extends GlobalView {

    function __construct() {
      parent::__construct();
    }


    /* Génère le contenu HTML pour afficher une
    liste passée en paramètre */
    function renderMessageCreated($message) {
        if ($message == null)
          error("Votre message n'a pas pu être créé");

        $url_list = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$message->list_id, 'token'=>$message->liste->token]);
        $content = "<h1> Le message a bien été publié ! </h1>";
        $content .= "<p><a href=\"$url_list\">Retour à la liste</a></p>";
        $_SESSION['content']  = $content;
        parent::render();
    }

    /* Génère le contenu HTML pour afficher une
    liste passée en paramètre */
    function renderFormMessage($id, $token) {
        $url = \Slim\Slim::getInstance()->urlFor('list_addMsgPost',['id'=>$id, 'token'=>$token]);
        $form =
        "<form action='$url' method='POST'>
          <p>Entrez une news pour votre liste</p>
          <textarea id='message_body' name='message_body' rows='10' cols='50' placeholder='Entrez votre message'></textarea>
          <input type='submit' value='Envoyer le message'>
        </form>";

        $_SESSION['content']  = $form;
        parent::render();
    }
  }
