<?php
  namespace mywishlist\view;
  require_once 'vendor/autoload.php';

  abstract class GlobalView {
    protected $isAdmin = false;
    protected $HTML_PATH = "src/view/html/";
    protected $messages = "";

    public function render() {
      \mywishlist\controller\AccountController::generateAccountHeader();
      include($this->HTML_PATH . 'index.php');
    }

    public function addHeadMessage($text, $type) {
      switch ($type) {
        case 'bad':
        case false:
          $messages .= "<p class='headMsg_bad'> $text </p>";
        break;
        case 'good':
        case true:
        break;
        case 'neutral':
        default:
        break;
      }
    }

    public function notConnectedError() {
      $this->error("veuillez vous connecter...");
    }

    public function error($errorMessage) {
      $_SESSION['content'] = "<p class='errorMsg'> Erreur : $errorMessage </p>";
      $this->render();
      die();
    }
  }
