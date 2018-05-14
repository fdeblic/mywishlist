<?php
  namespace mywishlist\controller;
  require_once 'vendor/autoload.php';

  use \mywishlist\models\Message as Message;
  use \mywishlist\view\MessageView as MessageView;
  use \mywishlist\models\WishList as WishList;
  use \mywishlist\models\Account as Account;

  class MessageController {

    /**
     *Ajouter un message à une liste
     *@param $list_id id de la liste où ajouter le message
     *@param $token le token de la liste
     */
    public static function createMessage($list_id, $token){
      $view = new MessageView();
      $list = Wishlist::where('no','=',$list_id)->where('token','=',$token)->first();

      if($list == null || !isset($list))
        $view->error("La liste n'existe pas.");

      if (!isset($_SESSION['user_login']))
        $view->notConnectedError();

      if (!isset($_POST['message_body']))
        $view->error("veuillez entrer un message");

      $user = Account::where('login','=',$_SESSION['user_login'])->first();
      $message = new Message();
      $message->id_creator = $user->id_account;
      $message->body = filter_var($_POST['message_body'],FILTER_SANITIZE_STRING);
      $message->list_id = $list_id;
      $message->save();
      $view->renderMessageCreated($message);
    }

    /**
     *Affiche la liste via la vue 
     *@param $list_id id de la liste où ajouter le message
     *@param $token le token de la liste
     */
    public static function getFormMessage($id, $token){

        $vue = new MessageView();

        if (!AccountController::isConnected())
          $vue->notConnectedError();

        if (Wishlist::where('no','=',$id)->where('token','=',$token)->first() == null)
          $vue->error('la liste n\'existe pas');

        $vue->renderFormMessage($id, $token);

    }
  }
