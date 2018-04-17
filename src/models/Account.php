<?php

namespace mywishlist\models;

class Account extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'account';
    protected $primary = 'id_compte';
    public $timestamps = false;
}
?>
