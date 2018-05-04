<?php
namespace mywishlist\controller;

class PotController {
  public function createPot() {
    echo "createPot";
  }

  public function participatePot($id) {
    $vue = new ItemView();
    $vue->addHeadMessage("OK", "good");
    $vue->renderItem();
  }
}

?>
