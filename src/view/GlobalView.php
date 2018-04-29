<?php
  namespace mywishlist\view;
  require_once 'vendor/autoload.php';

  class GlobalView {
    protected $isAdmin = false;
    protected $HTML_PATH = "src/view/html/";
    protected $messages = "";

    public function render() {
      \mywishlist\controller\AccountController::generateAccountHeader();
      $_SESSION['content'] = $this->messages . $_SESSION['content'];
      include($this->HTML_PATH . 'index.php');
      $_SESSION['content'] = "";
    }

    public function addHeadMessage($text, $type) {
      switch ($type) {
        case 'bad':
          $this->messages .= "<p class='headMsg_bad'> $text </p>";
        break;
        case 'good':
          $this->messages .= "<p class='headMsg_good'> $text </p>";
        break;
        case 'neutral':
        default:
          $this->messages .= "<p class='headMsg'> $text </p>";
        break;
      }
    }

    public function notConnectedError() {
      $this->error("veuillez vous connecter...");
    }

    public function error($errorMessage) {
      $this->addHeadMessage("Erreur : $errorMessage", "bad");
      $_SESSION['content'] = "";
      //$_SESSION['content'] = "<p class='errorMsg'> Erreur : $errorMessage </p>";
      $this->render();
      die();
    }
  }
