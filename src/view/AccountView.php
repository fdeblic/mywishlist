<?php
  namespace mywishlist\view;

  class AccountView extends GlobalView {
    function printConnectionForm() {
      echo "[connection form]";
    }

    function renderAccountEditor($account) {
      $id_account = '';
      $nom = '';
      $prenom = '';
      $login = '';
      $admin = '';
      $participant = '';

      $url = '';
      $app = \Slim\Slim::getInstance();
      $connected = \mywishlist\controller\AccountController::isConnected();
      if ($connected)
        $url = $app->urlFor('acc_edit_post');
      else
        $url = $app->urlFor('acc_create_post');

      if ($account != null) {
        $id_account = $account->id_account;
        $nom = $account->nom;
        $prenom = $account->prenom;
        $login = 'Login : '.$account->login;
        $admin = $account->admin;
        $participant = $account->participant;
      }

      $_SESSION['content'] = "
      <form method='post' action='".$url."'>
        <input required type='text' placeholder='Login' name='acc_login' value='$login' ". ($connected ? 'disabled':'') .">
        <input required type='hidden' name='acc_id_account' value='$id_account'>
        <input required type='text' placeholder='Nom' name='acc_nom' value='$nom'>
        <input required type='text' placeholder='Prénom' name='acc_prenom' value='$prenom'>
        <input type='password' minlength='8' placeholder='Mot de passe' name='acc_password' ". (!$connected ? 'required':'') .">
        <input type='password' minlength='8' placeholder='Confirmation mot de passe' name='acc_password_confirmation'". (!$connected ? 'required':'') .">
        <input type='submit' value='Enregistrer'>
      </form>";

      if ($connected) {
        $_SESSION['content'] .= "<form method='post' action='".$app->urlFor('acc_delete')."'>
          <input type='submit' value='Supprimer mon compte'>
        </form>";
      }

      parent::render();
    }

    public function renderAccountCreated($acc) {
      $this->addHeadMessage("Le compte '$acc->login' a bien été créé", 'good');
      parent::render();
    }
  }
?>
