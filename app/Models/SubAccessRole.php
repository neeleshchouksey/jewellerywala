<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubAccessRole extends Model
{
    protected $table = "sub_access_roles";

    public function accessrole()
    {
        return $this->belongsTo('App\Models\AccessRole');
    }
}
