<?php

namespace mywishlist\models;

class WishList extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'liste';
    protected $primaryKey = 'no';
    public $timestamps = false;

    public function items() {
      return $this->hasMany('mywishlist\models\Item','liste_id','no');
    }

    public function messages() {
      return $this->hasMany('mywishlist\models\Message','list_id','no');
    }
  }
?>
