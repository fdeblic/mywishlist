<?php
  namespace mywishlist\view;

  class AccountView extends GlobalView {
    function printConnectionForm() {
      echo "[connection form]";
    }

    function printAccountEditor($account) {
      $id_account = '';
      $nom = '';
      $prenom = '';
      $login = '';
      $password = '';
      $admin = '';
      $participant = '';

      if ($account != null) {
        $id_account = $account->id_account;
        $nom = $account->nom;
        $prenom = $account->prenom;
        $login = $account->login;
        $password = $account->password;
        $admin = $account->admin;
        $participant = $account->participant;
      }

      $_SESSION['content'] = "
      <form method='post' action='".\Slim\Slim::getInstance()->urlFor('acc_create_get')."'>
        <input required type='hidden' name='acc_id_account' value='$id_account'>
        <input required type='text' placeholder='Nom' name='acc_nom' value='$nom'>
        <input required type='text' placeholder='Prénom' name='acc_prenom' value='$prenom'>
        <input required type='text' placeholder='Login' name='acc_login' value='$login'>
        <input required type='password' placeholder='********' name='acc_password'>
        <input type='submit' value='Créer'>
      </form>";

      parent::render();
    }

    public function renderAccountCreated($acc) {
      addHeadMessage("Le compte '$acc->login' a bien été créé", 'good');
      //$_SESSION['content'] = "<p> Le compte '$acc->login' a bien été créé </p>";
      parent::render();
    }
  }
?>
