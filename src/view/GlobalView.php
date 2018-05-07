<?php
namespace mywishlist\view;

class GlobalView {

  /*
    Constructeur
  */
  function __construct() {
    if (!isset($_SESSION['messages']))
      $_SESSION['messages'] = '';
    if (!isset($_SESSION['globalViewContent']))
      $_SESSION['globalViewContent'] = '';
  }

  /*
    Renvoie le code d'un bouton vers une url en POST ou GET uniquement
  */
  public function genererBouton($nom, $url, $methode, $className) {
    $methode = strtoupper($methode);
    if ($methode != 'GET' && $methode != 'POST')
      return '[methode incorrecte]';
    return "<form action='$url' method='$methode'>
      <input type='submit' value='$nom' class='$className'>
    </form>";
  }

  public function render() {
    // Génère les parties variables
    \mywishlist\controller\AccountController::generateAccountHeader();
    $_SESSION['globalViewContent'] = $_SESSION['messages'] . $_SESSION['globalViewContent'];

    // Création de la page
    include('src/view/html/index.php');

    // Reset des contenus pour la prochaine page
    $_SESSION['globalViewContent'] = "";
    $_SESSION['messages'] = "";
  }

  public function addContent($content) {
    $_SESSION['globalViewContent'] .= $content . "\r\n";
  }

  public static function getContent() {
    if (isset($_SESSION['globalViewContent']))
      return $_SESSION['globalViewContent'];
    else
      return "(pas de contenu généré)";
  }

  public function addHeadMessage($text, $type) {
    switch ($type) {
      case 'bad':
      $_SESSION['messages'] .= "<p class='headMsg_bad'> $text </p>";
      break;
      case 'good':
      $_SESSION['messages'] .= "<p class='headMsg_good'> $text </p>";
      break;
      case 'neutral':
      default:
      $_SESSION['messages'] .= "<p class='headMsg'> $text </p>";
      break;
    }
  }

  public function notConnectedError() {
    $this->error("veuillez vous connecter...");
  }

  public function error($errorMessage) {
    $this->addHeadMessage("Erreur : $errorMessage", "bad");
    $_SESSION['globalViewContent'] = "";
    $this->render();
    die();
  }
}
