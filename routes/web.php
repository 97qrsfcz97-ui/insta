<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\StoryLikeController;
use App\Http\Controllers\StoryCommentController;
#Admin
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\StoriesController;
#comment 
#hello
#good morning
#hi

Auth::routes();

Route::group(['middleware' => 'auth'], function()
{
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/search', [HomeController::class, 'search'])->name('search');


    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function(){
        #USER
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::delete('/users/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');
        Route::patch('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');

        #POST
        Route::get('/posts', [PostsController::class, 'index'])->name('posts');
        Route::delete('/posts/{id}/hide', [PostsController::class, 'hide'])->name('posts.hide');
        Route::patch('/posts/{id}/unhide', [PostsController::class, 'unhide'])->name('posts.unhide');

        #CATEGORY
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
        Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store');
        Route::patch('/categories/{id}/update', [CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}/destroy', [CategoriesController::class, 'destroy'])->name('categories.destroy');

        #STORY (admin)
        Route::get('/stories', [StoriesController::class, 'index'])->name('stories');
        Route::delete("/stories/{id}/hide", [StoriesController::class, "hide"])->name("stories.hide");
        Route::patch("/stories/{id}/unhide", [StoriesController::class, "unhide"])->name("stories.unhide");
    });

    #POST
    Route::get('/post.create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post.store', [PostController::class, 'store'])->name('post.store');
    Route::get('/post/{id}/show', [PostController::class, 'show'])->name('post.show');
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('/post/{id}/update', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');

    #COMMENT
    Route::post('/comment/{post_id}/store', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{id}/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');

    #PROFILE
    Route::get('/profile/{id}/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{id}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{id}/following', [ProfileController::class, 'following'])->name('profile.following');

    #LIKE
    Route::post('/like/{post_id}/store', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/like/{post_id}/destroy', [LikeController::class, 'destroy'])->name('like.destroy');

    #FOLLOW
    Route::post('/follow/{user_id}/store', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/follow/{user_id}/destroy', [FollowController::class, 'destroy'])->name('follow.destroy');

    #STORY
    Route::get('/story.create', [StoryController::class, 'create'])->name('story.create');
    Route::post('/story.store', [StoryController::class, 'store'])->name('story.store');
    Route::get('/story/{user_id}/show', [StoryController::class, 'show'])->name('story.show');
    Route::post('/story/{story_id}/view', [StoryController::class, 'recordView'])->name('story.view');
    Route::delete('/story/{id}/destroy', [StoryController::class, 'destroy'])->name('story.destroy');

    #STORY LIKE
    Route::post('/story-like/{story_id}/store', [StoryLikeController::class, 'store'])->name('story-like.store');
    Route::delete('/story-like/{story_id}/destroy', [StoryLikeController::class, 'destroy'])->name('story-like.destroy');

    #STORY COMMENT
    Route::post('/story-comment/{story_id}/store', [StoryCommentController::class, 'store'])->name('story-comment.store');
    Route::delete('/story-comment/{id}/destroy', [StoryCommentController::class, 'destroy'])->name('story-comment.destroy');

});
