<!DOCTYPE HTML>
<html lang="fr">
  <head>
    <title> MyWishList </title>
    <meta charset='utf-8'>
    <base href="/wishlist/index.php" />
    <link rel='stylesheet' href='./css/styles.css'>
  </head>

  <body>
    <?php
      // Temporise la sortie HTML (pour ajouter les tabulation par la suite)
      ob_start();

      require("header.php");

      require("content.php");

      require("footer.php");

      $content = ob_get_contents();
      ob_end_clean();

      // Mise en place des tabulations pour un code HTML lisible
      print str_replace("\n", "\n\t", $content) . "\r";
    ?>
  </body>
</html>
