<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryComment extends Model
{
    #Get the user who posted this comment
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    #Get the story this comment belongs to
    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
