
<!-- Header -->
<header>
  <h1> MyWishList </h1>
  <ul id="menu-navig">
    <a href="./"><li> Accueil </li></a>
    <a href="<?php echo \Slim\Slim::getInstance()->urlFor('list_getPubLists'); ?>"><li>Catalogue </li></a>
    <li> À propos </li>
    <?php if (\mywishlist\controller\AccountController::isConnected())
      echo '<a href="' . \Slim\Slim::getInstance()->urlFor('acc_edit_get') . '"><li>Mon compte</li></a>';
    ?>
  </ul>
  <?php
    if (isset($_SESSION['acc_content']))
      echo $_SESSION['acc_content'];
  ?>
  <hr>
</header>
