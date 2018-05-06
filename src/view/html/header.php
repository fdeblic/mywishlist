
<!-- Header -->
<header>
  <h1> MyWishList </h1>
  <ul id="menu-navig">
<<<<<<< HEAD
    <a href="./"><li> Accueil </li></a>
    <a href="<?php echo \Slim\Slim::getInstance()->urlFor('list_getPubLists'); ?>"><li>Catalogue </li></a>
=======
    <li><a href="./"> Accueil </a> </li>
    <li><a href="<?php echo \Slim\Slim::getInstance()->urlFor('list_getPubLists'); ?>">Catalogue </a></li>
    <li><a href="<?php echo \Slim\Slim::getInstance()->urlFor('list_getCreators'); ?>">Créateurs</a></li>
>>>>>>> e6661404d29c4a15cd10f754062453b34b35e9f0
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
