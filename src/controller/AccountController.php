<?php
namespace mywishlist\controller;
require_once 'vendor/autoload.php';

use \mywishlist\models\Account as Account;
use \mywishlist\view\AccountView as AccountView;

class AccountController {
  // Verifies if the user is connected
  public function isConnected() {
    if (!isset($_SESSION['user_connected']) || $_SESSION['user_connected'] == false) {
      return false;
    } else {
      return true;
    }
  }

  public function insertNewAccount() {
    $acc = new Account();
    print "insert";
  }

  public function createAccountForm() {
    $view = new AccountView();
    $view->printAccountEditor(null);
  }


}

 ?>
