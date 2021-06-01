<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];
    protected $primaryKey = "tag_id";

    public function posts()
    {
        return $this->belongsToMany("App\Post", "post_tag", "post_id", "tag_id");
    }
}
