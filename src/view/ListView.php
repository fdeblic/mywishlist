<?php
namespace mywishlist\view;

  class ListView {
    static function render() {
      echo 'function render()';
    }

    static function displayLists($lists) {
      echo "Listes :<br>\r\n";
      foreach ($lists as $list) {
        echo $list->no . ' : ' . $list->titre . "<br>\r\n";
        //var_dump($list);
      }
    }

  }

?>
