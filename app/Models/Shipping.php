<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Shipping extends Model
{
    protected $fillable=['type','price','status'];

    public function orders(){
        return $this->hasMany('App\Models\Order','shipping_id','id');
    }
}
