<?php

namespace mywishlist\models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends \Illuminate\Database\Eloquent\Model {
    use SoftDeletes;
    protected $table = 'account';
    protected $primaryKey = 'id_account';
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
?>
