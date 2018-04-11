<?php
  namespace mywishlist\view;

  abstract class GlobalView {
    protected $isAdmin = false;
    protected $HTML_PATH = "src/view/html/";

    public function render() {
      include($this->HTML_PATH . 'index.php');
    }
  }
