<?php
namespace mywishlist\controller;

use \mywishlist\models\Account as Account;
use \mywishlist\view\AccountView as AccountView;
use \mywishlist\view\GlobalView as GlobalView;
use \mywishlist\view\MainView as MainView;

class AccountController {
  private $errorMessage = "";

  // Vérifie si l'utilisateur est connecté
  public static function isConnected() {
    return isset($_SESSION['user_connected']) && $_SESSION['user_connected'] == true;
  }

  private function setConnected($bool) {
    $_SESSION['user_connected'] = $bool ? true : false;
  }


  /**
   *Créer un noueau compte
   */
  function insertNewAccount() {
    $acc = new Account();
    $vue = new AccountView();

    // Vérifie les données reçues
    if (!isset($_POST['acc_nom'])) $vue->error("entrez un nom");
    if (!isset($_POST['acc_prenom'])) $vue->error("entrez un prénom");
    if (Account::where('login','=', strtolower($_POST['acc_login']))->first() != null) $vue->error("Ce login est déjà pris");
    if (!isset($_POST['acc_login']) || strlen($_POST['acc_login']) < 5) $vue->error("entrez un login suffisamment long");
    if (!isset($_POST['acc_password']) || strlen($_POST['acc_password']) < 8) $vue->error("entrez un mot de passe suffisamment long");
    if (!isset($_POST['acc_password_confirmation']) || strlen($_POST['acc_password_confirmation']) < 8) $vue->error("vous devez confirmer votre mot de passe");
    if ($_POST['acc_password'] != $_POST['acc_password_confirmation']) $vue->error("les mots de passe entrés ne concordent pas");

    $acc->nom = $_POST['acc_nom'];
    $acc->prenom = $_POST['acc_prenom'];
    $acc->login = strtolower($_POST['acc_login']);
    $acc->password = crypt($_POST['acc_password'], "sel de mer");
    $acc->admin = false;

    // Enregistre le nouveau compte
    if ($acc->save()) {
      $vue = new MainView();
      $vue->addHeadMessage('Votre compte a bien été créé !', 'good');
      $vue->render($acc);
    } else {
      $vue->addHeadMessage('Impossible de créer le compte', 'bad');
      $vue->renderAccountEditor($acc);
    }
  }

  /**
   * Génère le header affichant l'utilisateur connecté
   */
  static function generateAccountHeader() {
    $content = "";
    AccountView::generateAccountHeader(AccountController::isConnected(), AccountController::getLogin());
  }

  /**
   *Créer un formulaire de compte
   */
  function createAccountForm() {
    $view = new AccountView();
    $view->renderAccountEditor(null);
  }

  /**
   *Récupérer le login de la personne qui est connectée
   */
  static function getLogin() {
    if (isset($_SESSION['user_login']))
      return $_SESSION['user_login'];
    else
      return "";
  }

  /**
   *Permet de se connecter
   */
  function connect() {
    $vue = new GlobalView();

    if (!isset($_POST['acc_login']))
      $vue->error("entrez un login");

    if (!isset($_POST['acc_password']))
      $vue->error("entrez votre mot de passe");

    $login = $_POST['acc_login'];
    $password = $_POST['acc_password'];

    $acc = Account::where('login', '=', strtolower($login))->first();

    if ($acc == null)
      $vue->error("login inconnu");

    if (crypt($password, 'sel de mer') != $acc->password)
      $vue->error("mauvais mot de passe !");

    $_SESSION['user_connected'] = true;
    $_SESSION['user_login'] = $acc->login;
    $_SESSION['user_id'] = $acc->id_account;

    GlobalView::addHeadMessage("Vous êtes connecté !", 'good');

    if (isset($_SERVER['HTTP_REFERER'])) {
      // Retourne à la page précédente
      \Slim\Slim::getInstance()->redirect($_SERVER['HTTP_REFERER'], 303);
    }
    else {
      $vue = new GlobalView();
      $vue->render();
    }
  }

  /**
   * Permet de se déconnecter
   */
  function disconnect() {
    $_SESSION['user_connected'] = false;
    $_SESSION['user_login'] = "";
    $_SESSION['user_id'] = 0;

    GlobalView::addHeadMessage("Vous êtes à présent déconnecté(e)", "good");

    if (isset($_SERVER['HTTP_REFERER'])) {
      // Retourne à la page précédente
      \Slim\Slim::getInstance()->redirect($_SERVER['HTTP_REFERER'], 303);
    }
    else {
      $vue = new GlobalView();
      $vue->render();
    }
  }

  /**
   * Permet d'éditer un compte
   *@param $method si la méthode est post ou get
   */
  function edit($method) {
    $vue = new AccountView();

    if ($this->isConnected() == false)
      $vue->notConnectedError();

    // Si méthode post
    if (strtolower($method) == 'post') {

      $acc = Account::where('id_account','=', $_SESSION['user_id'])->first();
      $vue = new AccountView();

      // Vérifie les données reçues
      if (!isset($_POST['acc_nom'])) $vue->error("entrez un nom");
      if (!isset($_POST['acc_prenom'])) $vue->error("entrez un prénom");
      if (isset($_POST['acc_password']) && strlen($_POST['acc_password'])>0) {
        if (!isset($_POST['acc_password']) || strlen($_POST['acc_password']) < 8) $vue->error("entrez un mot de passe suffisamment long");
        if (!isset($_POST['acc_password_confirmation']) || strlen($_POST['acc_password_confirmation']) < 8) $vue->error("vous devez confirmer votre mot de passe");
        if ($_POST['acc_password'] != $_POST['acc_password_confirmation']) $vue->error("les mots de passe entrés ne concordent pas");
        $acc->password = crypt($_POST['acc_password'], "sel de mer");

        $_SESSION['user_connected'] = false;
        $_SESSION['user_login'] = "";
        $_SESSION['user_id'] = 0;
        $vue->addHeadMessage("Vous avez changé votre mot de passe, veuillez vous reconnecter", "neutral");
      }

      $acc->nom = filter_var($_POST['acc_nom'], FILTER_SANITIZE_STRING);
      $acc->prenom = filter_var($_POST['acc_prenom'], FILTER_SANITIZE_STRING);

      // Enregistre le nouveau compte
      if ($acc->save()) {
        $vue->addHeadMessage("modifications enregistrées", "good");
        $vue->renderAccountEditor($acc);
      } else {
        $vue->error("impossible d'enregistrer les modifications");
      }
    } else {
      // Envoi du formulaire d'édition du compte
      $vue->renderAccountEditor(Account::where('id_account','=',$_SESSION['user_id'])->first());
    }
  }

  /**
   *Permet de supprimer un compte
   */
  function delete() {
    $vue = new AccountView();
    $acc = Account::where('id_account','=',$_SESSION['user_id'])->first();
    $acc->delete();
    $vue->addHeadMessage("Votre compte a bien été supprimé", 'good');
    // Déconexion
    $_SESSION['user_connected'] = false;
    $_SESSION['user_login'] = "";
    $_SESSION['user_id'] = 0;
    $vue->render();
  }

  /**
  * Retourne l'utilisateur
  * @return $user l'utilisateur
  */
  static function getCurrentUser(){
    if (!isset($_SESSION['user_id'])) return null;
    $user = Account::where('id_account','=',$_SESSION['user_id'])->first();
    return $user;
  }
}

?>
