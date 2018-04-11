<?php
  namespace mywishlist\view;

  class GlobalView {
    private $isAdmin = false;

    function __construct() {
    }

    public function render() {
      include('src/view/html/index.php');
    }
  }
