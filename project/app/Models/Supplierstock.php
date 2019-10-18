<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Supplierstock extends Model
{
    protected $fillable = ['user_id','item','item_desc','quantity_avail','item_img','item_price'];

    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
