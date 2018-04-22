<?php
namespace mywishlist\controller;
require_once 'vendor/autoload.php';

use \mywishlist\models\Account as Account;
use \mywishlist\view\AccountView as AccountView;

class AccountController {
  // Verifies if the user is connected
  public function isConnected() {
    if (!isset($_SESSION['user_connected']) || $_SESSION['user_connected'] == false) {
      return false;
    } else {
      return true;
    }
  }

  public function insertNewAccount() {
    $acc = new Account();
    $vue = new AccountView();

    if (!isset($_POST['acc_nom']))
      $vue->error("entrez un nom");

    if (!isset($_POST['acc_prenom']))
      $vue->error("entrez un prénom");

    if (!isset($_POST['acc_login']) || strlen($_POST['acc_login'])<5)
      $vue->error("entrez un login suffisamment long");

    if (Account::where('login','=', strtolower($_POST['acc_login']))->first() != null)
      $vue->error("Ce login est déjà pris");

    // Toutes les données vérifiées
    $acc->nom = $_POST['acc_nom'];
    $acc->prenom = $_POST['acc_prenom'];
    $acc->login = strtolower($_POST['acc_login']);
    $acc->password = crypt($_POST['acc_password'], "sel de mer");
    $acc->admin = false;
    $acc->participant = true;

    if ($acc->save()) {
      $vue->renderAccountCreated($acc);
    } else {
      $vue->error("impossible de créer le compte");
    }
  }

  public function createAccountForm() {
    $view = new AccountView();
    $view->printAccountEditor(null);
  }

  public function connect($login, $password) {
    $vue = new AccountView();
    $acc = Account::where('login', '=', strtolower($login))->first();

    if ($acc == null) $vue->error("login inconnu");


    if (crypt($password, 'sel de mer') === $acc->password) {
      print "login OK";
    } else {
      print "bad password";
    }
    //$view->printAccountEditor(null);
  }


}

 ?>
