<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    //
    protected $primaryKey = "user_id";
    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany("App\Post", "user_id");
    }
}
