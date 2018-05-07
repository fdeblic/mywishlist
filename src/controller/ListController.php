<?php
  namespace mywishlist\controller;
  require_once 'vendor/autoload.php';

  use \mywishlist\models\WishList as WishList;
  use \mywishlist\models\Account as Account;
  use \mywishlist\view\ListView as ListView;

  class ListController {
    // Affiche les listes existantes
    public function dispAllList() {
      // Récupère toutes les listes existantes dans la base de données
      $lists = WishList::where('public','=', 1);
      if (AccountController::isConnected()){
          $user = Account::where('login','=',$_SESSION['user_login'])->first();
          $lists = $lists->orWhere('user_id','=', $user->id_account);
      }
      $lists = $lists->get();
      // Affiche les listes via la vue
      $view = new ListView();
      $view->renderLists($lists);
    }

    public function displayList($id){
        $list = WishList::where('no','=',$id)->first();

        //Affiche la liste via la vue
        $view = new ListView();
        $view->renderList($list);

    }

    public function createList(){
      $view = new ListView();

      // Vérifie les données envoyées
      if ( !AccountController::isConnected())
        $view->notConnectedError();

      // Transcrit la date reçue
      $expiration = date('Y-m-d', strtotime($_POST['list_expiration']));
      if ($expiration == null)
        $view->error("date incorrecte");

      // Crée la nouvelle liste
      $user = Account::where('login','=',$_SESSION['user_login'])->first();
      $wishlist = new WishList();
      $wishlist->user_id = $user->id_account;
      $wishlist->titre =  filter_var($_POST['list_title'],FILTER_SANITIZE_STRING);
      $wishlist->description = filter_var($_POST['list_descr'],FILTER_SANITIZE_STRING);
      $wishlist->expiration = $expiration;
      $wishlist->public = isset($_POST['list_public']) ? 1 : 0 ;
      $wishlist->token = crypt(
        $_POST['list_title'] . $_POST['list_descr'] . $_POST['list_expiration'],
        $_SESSION['user_login'] . "sel de mer"
      );

      if($wishlist->save()){
        $view->addHeadMessage("Votre liste a bien été créée", 'good');
        $view->renderList($wishlist);
      }
      else{
        $view->addHeadMessage("Votre liste n'a pu être créée", 'bad');
        $this->getFormList(null);
      }
    }


    //Editer la liste par les données passées en post
    public function editList($id){
      $view = new ListView();

      // Vérifie les données envoyées
      if ( !AccountController::isConnected() )
        $view->notConnectedError();

      // Transcrit la date reçue
      $expiration = date('Y-m-d', strtotime($_POST['list_expiration']));
      if ($expiration == null)
        $view->error("date incorrecte");

      // Crée la nouvelle liste
      $user = Account::where('login','=',$_SESSION['user_login'])->first();
      $wishlist = WishList::where('no','=',$id)->first();
      $wishlist->titre =  filter_var($_POST['list_title'],FILTER_SANITIZE_STRING);
      $wishlist->description = filter_var($_POST['list_descr'],FILTER_SANITIZE_STRING);
      $wishlist->expiration = $expiration;
      $wishlist->public = isset($_POST['list_public']) ? 1 : 0;
      if($wishlist->save()){
        $view->addHeadMessage("Votre liste a bien été modifiée", 'good');
        $view->renderList($wishlist);
      }
      else{
        $view->addHeadMessage("Votre liste n'a pu être modifiée", 'bad');
        $this->getFormList(null);
      }
    }

    public function deleteList($id){
        $view = new ListView();
        $wishlist = Wishlist::where('no','=',$id)->delete();
        //$view->addHeadMessage("Votre liste a bien été supprimée", 'good');
        $this->dispAllList();
    }

    public function getFormList($id){
        //Affiche la liste via la vue
        if (!isset($id)){
            $list = new WishList();
        }
        else{
            $list = WishList::where('no','=',$id)->first();
        }
        $vue = new ListView();
        $vue->renderFormList($list);

    }


    public function dispPublicCreators() {
      $vue = new ListView();
      $creators = Account::whereIn(
        'id_account', WishList::select('user_id')->where('public','=',true)->get()->toArray()
        //WishList::select('no')->where('public','=',true)->get();
      )->get();


      $vue->renderCreators($creators);
    }
  }
