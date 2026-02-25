<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Story extends Model
{
    use SoftDeletes;
    #Get the owner of the story
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    #Get the category assigned to this story
    public function categoryStory()
    {
        return $this->hasMany(CategoryStory::class);
    }

    #Get all likes for this story
    public function storyLikes()
    {
        return $this->hasMany(StoryLike::class);
    }

    #Get all comments for this story, ordered by newest first
    public function storyComments()
    {
        return $this->hasMany(StoryComment::class)->latest();
    }

    #Get all view records for this story
    public function storyViews()
    {
        return $this->hasMany(StoryView::class);
    }

    #Returns TRUE if the authenticated user has liked this story
    public function isLiked()
    {
        return $this->storyLikes()->where('user_id', Auth::user()->id)->exists();
    }

    #Returns TRUE if the authenticated user has viewed this story
    public function isViewed()
    {
        return $this->storyViews()->where('user_id', Auth::user()->id)->exists();
    }

    #Scope: only return stories posted within the last 24 hours
    public function scopeActive($query)
    {
        return $query->where('created_at', '>', now()->subDay());
    }
}
