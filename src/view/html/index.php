<!DOCTYPE HTML>
<html lang="fr">
  <head>
    <title> MyWishList </title>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/" />
    <link rel='stylesheet' href='css/styles.css'>
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
      print str_replace("\n", "\n    ", $content) . "\r\n";
    ?>
    <script> //setTimeout(function() { document.location.reload(); } , 2000); </script>
    <script src='script/jquery-3.3.1.js'></script>
    <script src='script/wishlist.js'></script>
  </body>
</html>
