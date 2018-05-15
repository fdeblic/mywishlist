<?php
  namespace mywishlist\view;

  class AccountView extends GlobalView {

    /**
     *Constructeur
     */
    function __construct() {
      parent::__construct();
    }

    /**
     *Render de l'éditeur de compte
     *@param $account le compte à éditer
     */
    function renderAccountEditor($account) {
      $id_account = '';
      $nom = '';
      $prenom = '';
      $login = '';
      $admin = '';

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
      }

      $content  = "  <!-- Account editor -->\n";
      $content .= "<form method='post' action='".$url."'>\n";
      $content .= "  <input required type='text' placeholder='Login' name='acc_login' value='$login' ". ($connected ? 'disabled':'') .">\n";
      $content .= "  <input required type='hidden' name='acc_id_account' value='$id_account'>\n";
      $content .= "  <input required type='text' placeholder='Nom' name='acc_nom' value='$nom'>\n";
      $content .= "  <input required type='text' placeholder='Prénom' name='acc_prenom' value='$prenom'>\n";
      $content .= "  <input type='password' minlength='8' placeholder='Mot de passe' name='acc_password' ". (!$connected ? 'required':'') .">\n";
      $content .= "  <input type='password' minlength='8' placeholder='Confirmation mot de passe' name='acc_password_confirmation'". (!$connected ? 'required':'') .">\n";
      $content .= "  <input type='submit' value='Enregistrer'>\n";
      $content .= "</form>\n";

      if ($connected) {
        $content .= "\n<!-- Delete account button -->\n";
        $content .= "<form method='post' action='".$app->urlFor('acc_delete')."'>\n";
        $content .= "  <input type='submit' value='Supprimer mon compte'>\n";
        $content .= "</form>\n";
      }

      $this->addContent(str_replace ("\n", "\n  ", $content));
      parent::render();
    }

    /**
     *Génère le formulaire mis en haut ou le message "Bonjour [nom]"
     */
    public static function generateAccountHeader($connected, $name) {
      $content = "";
      $app = \Slim\Slim::getInstance();
      if ($connected) {
        $content  = "\n  <!-- Account -->\n";
        $content .= "  <p id='connectionMsg'>\n";
        $content .= "    Bonjour $name <br>\n";
        $content .= "    <a id='disconnectLink' href='" . $app->urlFor('acc_disconnect') . "'>Déconnexion</a>\n";
        $content .= "  </p>\n";
      } else {
        $content = "\n";
        $content .= "  <!-- Connection form -->\n";
        $content .= "  <form id='connectionForm' method='post' action='" . $app->urlFor("acc_auth") . "'>\n";
        $content .= "    <input required placeholder='Login' type='text' name='acc_login'>\n";
        $content .= "    <input required placeholder='******' type='password' name='acc_password'>\n";
        $content .= "    <input type='submit' value='Connexion'>\n";
        $content .= "    <a id='inscriptionLink' href='".$app->urlFor('acc_create_get')."'>Inscription</a>\n";
        $content .= "  </form>\n";
      }

      $_SESSION['acc_content'] = $content;
    }

    /**
     *Confirme la création du compte
     */
    public function renderAccountCreated($acc) {
      $this->addHeadMessage("Le compte '$acc->login' a bien été créé", 'good');
      parent::render();
    }
  }
?>
