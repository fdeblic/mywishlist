<!DOCTYPE HTML>
<html lang="fr">
  <head>
    <meta charset='utf-8'>
    <title> MyWishList </title>
  </head>

  <body>
    <?php ob_start();
      echo "<!-- Header -->\n";
      require("header.php");

      echo "\n<!-- Content -->\n";
      require("content.php");

      echo "\n<!-- Footer -->\n";
      require("footer.php");

      $content = ob_get_contents();
      ob_end_clean();
      print str_replace("\n", "\n\t", $content) . "\r";
    ?>
  </body>
</html>
