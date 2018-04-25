<?php
  namespace mywishlist\controller;
  require_once 'vendor/autoload.php';

  use \mywishlist\models\Message as Message;
  use \mywishlist\view\MessageView as MessageView;
  use \mywishlist\models\WishList as WishList;
  use \mywishlist\models\Account as Account;

  class MessageController {

    public static function createMessage($list_id){
      $view = new MessageView();
      $list = Wishlist::where('no','=',$list_id)->first();

      if (!isset($_SESSION['user_login']))
        $view->notConnectedError();

      if (!isset($_POST['message_body']))
        $view->error("veuillez entrer un message");

      if(!isset($list))
        $view->error("La liste n'existe pas.");

      $user = Account::where('login','=',$_SESSION['user_login'])->first();
      $message = new Message();
      $message->id_creator = $user->id_account;
      $message->body = filter_var($_POST['message_body'],FILTER_SANITIZE_STRING);
      $message->list_id = $list_id;
      $message->save();
      $view->renderMessageCreated($message);
    }

    public static function getFormMessage($id){

        //Affiche la liste via la vue
        $vue = new MessageView();
        $vue->renderFormMessage($id);

    }
  }
