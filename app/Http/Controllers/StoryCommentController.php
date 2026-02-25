<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoryComment;
use Illuminate\Support\Facades\Auth;

class StoryCommentController extends Controller
{
    private $storyComment;

    public function __construct(StoryComment $storyComment)
    {
        $this->storyComment = $storyComment;
    }

    #Store a new comment on a story
    public function store(Request $request, $story_id)
    {
        #1. Validate the comment body
        $request->validate(
            [
                'comment_body' . $story_id => 'required|max:100'
            ],
            [
                'comment_body' . $story_id . '.required'    => 'You cannot submit an empty comment.',
                'comment_body' . $story_id . '.max'         => 'The comment must not have more than 100 characters.'
            ]
        );

        #2. Save comment to the database
        $this->storyComment->body       = $request->input('comment_body' . $story_id);
        $this->storyComment->user_id    = Auth::user()->id;
        $this->storyComment->story_id   = $story_id;
        $this->storyComment->save();

        #3. Return to the story viewer, resuming at the same story
        return redirect()->back()->with('resume_story_id', $story_id);
    }

    #Delete a comment (comment author only)
    public function destroy($id)
    {
        $this->storyComment->destroy($id);
        return redirect()->back();
    }
}
