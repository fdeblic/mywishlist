<?php
namespace mywishlist\controller;
require_once 'vendor/autoload.php';

use \mywishlist\models\Account as Account;

class AccountController {
  // Verifies if the user is connected
  public static function isConnected() {
    if (!isset($_SESSION['user_connected']) || $_SESSION['user_connected'] == false) {
      return false;
    } else {
      return true;
    }
  }

  public static function insertNewAccount() {
    print "insert";
  }

  public static function createAccountForm() {
    print "create";
  }


}

 ?>
