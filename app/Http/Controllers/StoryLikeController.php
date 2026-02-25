<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoryLike;
use Illuminate\Support\Facades\Auth;

class StoryLikeController extends Controller
{
    private $storyLike;

    public function __construct(StoryLike $storyLike)
    {
        $this->storyLike = $storyLike;
    }

    #Add a like to a story
    public function store($story_id)
    {
        $this->storyLike->user_id   = Auth::user()->id;
        $this->storyLike->story_id  = $story_id;
        $this->storyLike->save();

        return redirect()->back();
    }

    #Remove a like from a story
    public function destroy($story_id)
    {
        $this->storyLike
            ->where('user_id', Auth::user()->id)
            ->where('story_id', $story_id)
            ->delete();

        return redirect()->back();
    }
}
