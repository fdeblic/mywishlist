<?php

require_once 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;
use mywishlist\models\Item;
use mywishlist\models\Liste;

$db = new DB();
$db->addConnection(parse_ini_file('src/conf/db.config.ini'));
$db->setAsGlobal();
$db->bootEloquent();


?>
