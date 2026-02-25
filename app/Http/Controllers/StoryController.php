<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Category;
use App\Models\User;
use App\Models\StoryView;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    private $story;
    private $category;
    private $user;

    public function __construct(Story $story, Category $category, User $user)
    {
        $this->story    = $story;
        $this->category = $category;
        $this->user     = $user;
    }

    #Show the story creation form
    public function create()
    {
        $all_categories = $this->category->all();
        return view('users.stories.create')->with('all_categories', $all_categories);
    }

    #Store a new story in the database
    public function store(Request $request)
    {
        #1. Validate form data (only 1 category allowed for stories)
        $request->validate([
            'category'      => 'required|array|size:1',
            'description'   => 'required|min:1|max:1000',
            'image'         => 'required|mimes:jpeg,png,jpg,gif|max:1048'
        ]);

        #2. Save story data to the database
        $this->story->user_id       = Auth::user()->id;
        $this->story->image         = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->story->description   = $request->description;
        $this->story->save();

        #3. Save the selected category to the category_story pivot table
        $this->story->categoryStory()->create(['category_id' => $request->category[0]]);

        #4. Redirect to home
        return redirect()->route('index');
    }

    #Show the story viewer for a specific user's active stories
    public function show($user_id)
    {
        #Get the target user
        $target_user = $this->user->findOrFail($user_id);

        #Get all active stories for this user, ordered oldest to newest for sequential playback
        #Eager-load relations used in $stories_data to prevent N+1 queries
        $active_stories = $this->story
            ->where('user_id', $user_id)
            ->with(['storyLikes', 'storyComments.user', 'storyViews', 'categoryStory.category'])
            ->active()
            ->oldest()
            ->get();

        #Get all users who have active stories, ordered by their latest story
        #Only shows followed users and the auth user themselves
        $story_users = $this->getStoryUsers();

        #Find the index position of the target user in the story users list
        $current_user_index = $story_users->search(fn($u) => $u->id == $user_id);

        #Pre-format story data for JS to avoid complex Blade/PHP expressions in the view
        $stories_data = $active_stories->map(function ($s) {
            #Get the first category name, fallback to 'Uncategorized' if category was deleted
            $category_story = $s->categoryStory->first();
            $category_name  = ($category_story && $category_story->category)
                ? $category_story->category->name
                : 'Uncategorized';

            return [
                'id'                => $s->id,
                'image'             => $s->image,
                'description'       => $s->description,
                'category_name'     => $category_name,
                'liked'             => $s->isLiked(),
                'like_count'        => $s->storyLikes->count(),
                'created_at'        => $s->created_at->diffForHumans(),
                'view_url'          => route('story.view', $s->id),
                'like_store_url'    => route('story-like.store', $s->id),
                'like_destroy_url'  => route('story-like.destroy', $s->id),
                'comment_store_url' => route('story-comment.store', $s->id),
                'comments'          => $s->storyComments->map(function ($c) {
                    return [
                        'id'          => $c->id,
                        'body'        => $c->body,
                        'user_name'   => $c->user->name,
                        'user_id'     => $c->user_id,
                        'destroy_url' => route('story-comment.destroy', $c->id),
                    ];
                })->values()->all(),
            ];
        })->values()->all();

        #Pre-format story users list for JS navigation
        $story_users_data = $story_users->map(function ($u) {
            return [
                'id'       => $u->id,
                'show_url' => route('story.show', $u->id),
            ];
        })->values()->all();

        return view('users.stories.show')
            ->with('target_user', $target_user)
            ->with('active_stories', $active_stories)
            ->with('stories_data', $stories_data)
            ->with('story_users', $story_users)
            ->with('story_users_data', $story_users_data)
            ->with('current_user_index', $current_user_index !== false ? $current_user_index : 0);
    }

    #Record that the authenticated user has viewed a story (called via fetch from JS)
    public function recordView($story_id)
    {
        #Use updateOrCreate to respect the unique constraint (story_id, user_id)
        StoryView::updateOrCreate([
            'story_id' => $story_id,
            'user_id'  => Auth::user()->id,
        ]);

        return response()->json(['success' => true]);
    }

    #Delete a story (owner only)
    public function destroy($id)
    {
        $story = $this->story->findOrFail($id);

        #Redirect to home if the auth user is not the owner
        if (Auth::user()->id !== $story->user_id) {
            return redirect()->route('index');
        }

        $story->delete();
        return redirect()->route('profile.show', Auth::user()->id);
    }

    #Get users who have active stories, limited to followed users and self
    private function getStoryUsers()
    {
        $all_users = $this->user->all();
        $story_users = collect();

        foreach ($all_users as $user) {
            if ($user->hasActiveStories()) {
                if ($user->id === Auth::user()->id || $user->isFollowed()) {
                    $story_users->push($user);
                }
            }
        }

        #Sort by the latest active story timestamp (newest first)
        return $story_users->sortByDesc(function ($user) {
            return $user->stories()->active()->max('created_at');
        })->values();
    }
}
