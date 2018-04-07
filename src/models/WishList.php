<?php

namespace mywishlist\models;

class WishList extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'liste';
    protected $primary = 'id';
    public $timestamps = false;

    public function items() {
      return $this->hasMany('mywishlist\models\Item','id','no');
    }

  }
?>
