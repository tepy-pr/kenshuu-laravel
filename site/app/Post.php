<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $primaryKey = "post_id";
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo("App\User", "user_id");
    }

    public function tags()
    {
        return $this->belongsToMany("App\Tag", "post_tag", "post_id", "tag_id");
    }

    public function images()
    {
        return $this->hasMany("App\Image", "post_id");
    }

    public function tagLabels()
    {
        $labels = [];
        foreach ($this->tags->toArray() as $tag) {
            array_push($labels, $tag["label"]);
        }

        return implode(", ", $labels);
    }
}
