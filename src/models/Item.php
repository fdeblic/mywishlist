<?php

namespace mywishlist\models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends \Illuminate\Database\Eloquent\Model {

    use SoftDeletes;
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dates = ['deleted_at'];

    /**
     *Permet de regrouper les items par liste
     */
    public function liste() {
      return $this->belongsTo('mywishlist\models\WishList','liste_id','no');
    }

    /**
     *Permet de connaître le montant restant d'une cagnotte
     */
    public function maxPotParticipation() {
      if ($this->cagnotte == true) {
        $somme = PotParticipation::where('pot_id','=',$this->id)->sum('amount');
        return round($this->tarif - $somme, 2);
      } else {
        return 0;
      }
    }

    /**
     *Permet de connaître le montant actuel de la cagnotte
     */
    public function currentPotAmount() {
      if ($this->cagnotte == true) {
        $somme = PotParticipation::where('pot_id','=',$this->id)->sum('amount');
        return $somme;
      } else {
        return -1;
      }
    }

}
?>
