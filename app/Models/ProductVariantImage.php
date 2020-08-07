<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantImage extends Model
{
    protected $table = "product_variant_images";

    public function productVariant()
    {
        return $this->belongsTo('App\Models\productVariant');
    }
}
