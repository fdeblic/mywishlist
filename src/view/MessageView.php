<?php
namespace mywishlist\view;

  class MessageView extends GlobalView {

    function __construct() {
      parent::__construct();
    }


    /**
     *Génère le contenu HTML pour confirmer la création du message
     *@param $message le message créé
     */
    function renderMessageCreated($message) {
        if ($message == null)
          error("Votre message n'a pas pu être créé");

        $url_list = \Slim\Slim::getInstance()->urlFor('list_aff',['id'=>$message->list_id, 'token'=>$message->liste->token]);
        $content = "<h1> Le message a bien été publié ! </h1>";
        $content .= "<p><a href='$url_list'>Retour à la liste</a></p>";
        $this->addContent($content);
        parent::render();
    }

    /**
     *Génère le formulaire de création de message
     *@param $id l'id de la liste
     *@param $token le token de la liste
     */
    function renderFormMessage($id, $token) {
        $url = \Slim\Slim::getInstance()->urlFor('list_addMsgPost',['id'=>$id, 'token'=>$token]);
        $form  = "  <!-- Message form -->\n";
        $form .= "<form action='$url' method='POST'>\n";
        $form .= "  <h1>Entrez un message pour votre liste !</h1>\n";
        $form .= "  <textarea id='message_body' name='message_body' rows='10' cols='50' placeholder='Entrez votre message...'></textarea>\n";
        $form .= "  <input type='submit' value='Envoyer le message'>\n";
        $form .= "</form>";
        $this->addContent(str_replace("\n", "\n  ", $form));
        parent::render();
    }
  }
