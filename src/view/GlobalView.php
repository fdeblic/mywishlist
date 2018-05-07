<?php
namespace mywishlist\view;

class GlobalView {

  /*
    Constructeur
  */
  function __construct() {
    if (!isset($_SESSION['messages']))
      $_SESSION['messages'] = '';
    if (!isset($_SESSION['content']))
      $_SESSION['content'] = '';
  }

  public function render() {
    \mywishlist\controller\AccountController::generateAccountHeader();
    $_SESSION['content'] = $_SESSION['messages'] . $_SESSION['content'];
    include('src/view/html/index.php');
    $_SESSION['content'] = "";
    $_SESSION['messages'] = "";
  }

  public function addContent($content) {
    $_SESSION['content'] .= $content . "\r\n";
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
    $_SESSION['content'] = "";
    $this->render();
    die();
  }
}
