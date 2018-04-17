<?php

namespace mywishlist\models;

class Account extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'account';
    protected $primary = 'id';
    public $timestamps = false;

    public function items() {
      return $this->hasMany('mywishlist\models\Item','liste_id','no');
    }
  }
?>
