<?php

namespace mywishlist\models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends \Illuminate\Database\Eloquent\Model {

    use SoftDeletes;
    protected $table = 'message_list';
    protected $primaryKey = 'id_message';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    /**
     *Enumère les messages d'une liste
     */
    public function liste() {
      return $this->belongsTo('mywishlist\models\WishList','list_id','no');
    }

    /**
     *Enumère les listes d'un compte
     */
    public function creator(){
        return $this->belongsTo('mywishlist\models\Account','id_creator','id_account');
    }

}
?>
