<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryLike extends Model
{
    public $timestamps = false;
    protected $primaryKey = ['user_id', 'story_id'];
    public $incrementing = false;

    #Get the story that was liked
    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
