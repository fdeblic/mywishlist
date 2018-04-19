<?php
  namespace mywishlist\view;

  class AccountView extends GlobalView {
    function render() {
      echo "AccountView:render()";
    }

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
      <form>
        <input type='hidden' name='acc_id_account' value='$id_account'>
        <input type='text' placeholder='Nom' name='acc_nom' value='$nom'>
        <input type='text' placeholder='Prénom' name='acc_prenom' value='$prenom'>
        <input type='text' placeholder='Login' name='acc_login' value='$login'>
        <input type='password' placeholder='********' name='acc_password'>
        <input type='submit' value='Créer'>
      </form>";

      parent::render();
    }
  }
?>
