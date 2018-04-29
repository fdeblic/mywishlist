<?php

namespace mywishlist\models;

class Message extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'message_list';
    protected $primaryKey = 'id_message';
    public $timestamps = true;

    public function liste() {
      return $this->belongsTo('mywishlist\models\WishList','list_id','no');
    }

    public function creator(){
        return $this->belongsTo('mywishlist\models\Account','id_creator','id_account');
    }

}
?>
