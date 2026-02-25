<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $post;
    private $user;

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts         = $this->getHomePosts();
        $suggested_users    = $this->getSuggestedUsers();
        $story_users        = $this->getStoryUsers();

        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users)
                ->with('story_users', $story_users);
    }

    #GET THE POSTS OF THE USERS THAT THE SUTH USER IS FOLLOWING

    public function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = [];

        foreach ($all_posts as $post)
            {
                if($post->user->isFollowed() || $post->user->id === Auth::user()->id)
                    {
                        $home_posts[] = $post;
                    }
            }
        return $home_posts;
    }

    #Get the users that the AUTH user is not following

    public function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach ($all_users as $user)
            {
                if (!$user->isFollowed())
                    {
                        $suggested_users[] = $user;
                    }
            }
        return $suggested_users;
    }

    #Get users with active stories: followed users + auth user, up to 10, sorted by latest story
    public function getStoryUsers()
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

        return $story_users->sortByDesc(function ($user) {
            return $user->stories()->active()->max('created_at');
        })->values()->take(10);
    }

    public function search(Request $request)
    {
        $users = $this->user->where('name', 'like', '%'.$request->search.'%')->get();
        return view('users.search')->with('users', $users)->with('search', $request->search);
    }
}
