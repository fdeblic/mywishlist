
<!-- Content -->
<content>
<?php
    if (isset($_SESSION['content']) && $_SESSION['content'] != "")
      echo $_SESSION['content'];
    else
      echo "\t<p> Contenu de la page... </p>\n";
?>
</content>
