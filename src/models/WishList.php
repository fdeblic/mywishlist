<?php

namespace mywishlist\models;
use Illuminate\Database\Eloquent\SoftDeletes;

class WishList extends \Illuminate\Database\Eloquent\Model {

    use SoftDeletes;
    protected $table = 'liste';
    protected $primaryKey = 'no';
    public $timestamps = false;
    protected $dates = ['deleted_at'];

    /**
     *Compte le nombre d'item d'une liste
     */
    public function items() {
      return $this->hasMany('mywishlist\models\Item','liste_id','no');
    }

    /**
     *Compte le nombre de messages d'une liste
     */
    public function messages() {
      return $this->hasMany('mywishlist\models\Message','list_id','no');
    }
  }
?>
