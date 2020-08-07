<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $table = "products";
    use SoftDeletes;
    public function variant()
    {
        return $this->hasMany('App\Models\ProductVariant');
    }


}
