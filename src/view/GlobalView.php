<?php
  namespace mywishlist\view;

  abstract class GlobalView {
    protected $isAdmin = false;
    protected $HTML_PATH = "src/view/html/";

    public function render() {
      include($this->HTML_PATH . 'index.php');
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
