
<!-- Header -->
<header>
  <h1> MyWishList </h1>
  <ul id="menu-navig">
    <li><a href="./"> Accueil </a> </li>
    <li><a href="<?php echo \Slim\Slim::getInstance()->urlFor('list_getPubLists'); ?>">Catalogue </a></li>
    <li> À propos </li>
    <?php if (\mywishlist\controller\AccountController::isConnected())
      echo '<li><a href="' . \Slim\Slim::getInstance()->urlFor('acc_edit_get') . '">Mon compte </a></li>';
    ?>
  </ul>
  <?php
    if (isset($_SESSION['acc_content']))
      echo $_SESSION['acc_content'];
  ?>
  <hr>
</header>
