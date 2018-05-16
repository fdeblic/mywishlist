<?php

namespace mywishlist\models;
use Illuminate\Database\Eloquent\SoftDeletes;

class PotParticipation extends \Illuminate\Database\Eloquent\Model {

    use SoftDeletes;
    protected $table = 'pot_participation';
    protected $primaryKey = 'id_pot_participation';
    public $timestamps = false;
    protected $dates = ['deleted_at'];

}
?>
