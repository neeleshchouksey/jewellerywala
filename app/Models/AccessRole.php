<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRole extends Model
{
    protected $table = "access_roles";

    public function children()
    {
        return $this->hasMany('App\Models\SubAccessRole');
    }
}
