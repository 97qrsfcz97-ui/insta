<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryView extends Model
{
    #Timestamps are enabled to record when the story was viewed
    #created_at is used to determine if a view occurred within the 24-hour active window
    protected $guarded = [];
}
