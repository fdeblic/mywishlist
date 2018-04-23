<?php
namespace mywishlist\controller;
require_once 'vendor/autoload.php';

use \mywishlist\models\Account as Account;
use \mywishlist\view\AccountView as AccountView;

class AccountController {
  // Verifies if the user is connected
  public static function isConnected() {
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

  // Génère le formulaire mis en haut ou le message "Bonjour [login]"
  public static function generateAccountHeader() {
    $content = "";
    //$app = new \Slim\Slim();
    $app = \Slim\Slim::getInstance();

    if (AccountController::isConnected()) {
      $content = "<p id='connectionMsg'> Bonjour " . AccountController::getLogin() . "<br>
        <a id='disconnectLink' href='" . $app->urlFor('acc_disconnect') . "'>Déconnexion</a></p>";
    } else {
      $content = "<form id='connectionForm' method='post' action='" . $app->urlFor("acc_auth") . "'>
        <input required placeholder='Login' type='text' name='acc_login'>
        <input required placeholder='******' type='password' name='acc_password'>
        <input type='submit' value='Connexion'>
      </form>";
    }

    $_SESSION['acc_content'] = $content;
  }

  public function createAccountForm() {
    $view = new AccountView();
    $view->printAccountEditor(null);
  }

  public static function getLogin() {
    if (isset($_SESSION['user_login']))
      return '<b>' . $_SESSION['user_login'] . '</b>';
    else
      return "";
  }

  public function connect() {
    $vue = new AccountView();

    if (!isset($_POST['acc_login']))
      $vue->error("entrez un login");

    if (!isset($_POST['acc_password']))
      $vue->error("entrez votre mot de passe");

    $login = $_POST['acc_login'];
    $password = $_POST['acc_password'];

    $acc = Account::where('login', '=', strtolower($login))->first();

    if ($acc == null)
      $vue->error("login inconnu");

    if (crypt($password, 'sel de mer') === $acc->password) {

      $_SESSION['user_connected'] = true;
      $_SESSION['user_login'] = $acc->login;

      $vue->addHeadMessage("Vous êtes connecté !", 'good');
      $vue->render();
    } else {
      $vue->error("mauvais mot de passe !");
    }
  }

  public function disconnect() {
    $_SESSION['user_connected'] = false;
    $_SESSION['user_login'] = "";

    $vue = new AccountView();
    $vue->addHeadMessage("Vous êtes à présent déconnecté(e)", "good");
    $vue->render();
  }
}

 ?>
