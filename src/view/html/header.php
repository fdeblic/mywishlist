
<!-- HEADER -->
<header>
  <div id='entete'>
    <h1> MyWishList </h1>
    <img src='img/icon/menu.png' alt='Menu' id='show-menu-link' class='icon'>
  </div>
  <?php
  if (isset($_SESSION['acc_content']))
  echo $_SESSION['acc_content'];
  ?>
  <!-- Menus -->
  <ul id="menu-navig">
    <li><a href="./"> Accueil </a></li>
    <li><a href="<?php echo \Slim\Slim::getInstance()->urlFor('list_getPubLists'); ?>"> Listes </a></li>
    <li><a href="<?php echo \Slim\Slim::getInstance()->urlFor('list_getCreators'); ?>"> Créateurs </a></li>
    <?php if (\mywishlist\controller\AccountController::isConnected())
      echo '<li><a href="' . \Slim\Slim::getInstance()->urlFor('acc_edit_get') . '"> Mon compte </a></li>';
    ?>
  </ul>
</header>
