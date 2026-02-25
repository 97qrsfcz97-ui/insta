<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story;

class StoriesController extends Controller
{
    private $story;

    public function __construct(Story $story)
    {
        $this->story = $story;
    }

    #Show all stories including hidden ones, with pagination (mirrors Admin\PostsController::index)
    public function index()
    {
        $all_stories = $this->story->withTrashed()->with('user')->latest()->paginate(5);
        return view('admin.stories.index')->with('all_stories', $all_stories);
    }

    #Soft-delete (hide) a story from the admin panel
    public function hide($id)
    {
        $this->story->findOrFail($id)->delete();
        return redirect()->back();
    }

    #Restore (unhide) a soft-deleted story
    public function unhide($id)
    {
        $this->story->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }
}
