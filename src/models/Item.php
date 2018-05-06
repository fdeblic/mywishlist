<?php

namespace mywishlist\models;

class Item extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function liste() {
      return $this->belongsTo('mywishlist\models\WishList','liste_id','no');
    }

    public function maxParticipation() {
      if ($this->cagnotte == true) {
        $somme = PotParticipation::where('pot_id','=',$this->id)->sum('amount');
        return $this->tarif - $somme;
      } else {
        return 0;
      }
    }

}
?>
