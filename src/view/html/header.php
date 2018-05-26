
<!-- HEADER -->
<header>
  <div id='entete'>
    <h1> <img src='img/lamp.png' alt='' height='40'> MyWishList </h1>
    <img src='img/icon/menu.png' alt='Menu' id='show-menu-link' class='icon'>
  </div>
  <?php
  if (isset($_SESSION['acc_content']))
  echo $_SESSION['acc_content'];
  ?>
  <!-- Menus -->
  <ul id="menu-navig">
    <li><a href="./"> <img src='img/icon/home.png' alt='' class='menuIcon'> Accueil </a></li>
    <li><a href="<?php echo \Slim\Slim::getInstance()->urlFor('list_getPubLists'); ?>"> <img src='img/icon/item.png' alt='' class='menuIcon'> Listes </a></li>
    <li><a href="<?php echo \Slim\Slim::getInstance()->urlFor('list_getCreators'); ?>"> <img src='img/icon/public.png' alt='' class='menuIcon'> Créateurs </a></li>
    <?php if (\mywishlist\controller\AccountController::isConnected())
      echo '<li><a href="' . \Slim\Slim::getInstance()->urlFor('acc_edit_get') . '"> <img src="img/icon/user.png" alt="" class="menuIcon"> Mon compte </a></li>';
    ?>
  </ul>
</header>
