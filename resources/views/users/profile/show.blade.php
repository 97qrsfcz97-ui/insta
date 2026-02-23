@extends('layouts.app')

@section('title', 'Profile')

@section('content')

    @include('users.profile.header')

    {{-- tab button --}}
    <ul class="nav nav-tabs justify-content-center profile-tabs mt-5" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center gap-1" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab" aria-selected="true">
                <i class="fa-solid fa-table-cells"></i> 
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-1" id="liked-tab" data-bs-toggle="tab" data-bs-target="#liked" type="button" role="tab" aria-selected="false">
                <i class="fa-regular fa-heart"></i> 
            </button>
        </li>
    </ul>

    <div class="tab-content mt-4" id="profileTabsContent">
        
        <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
            @if ($user->posts->isNotEmpty())
                <div class="row">
                    @foreach ($user->posts as $post)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <a href="{{ route('post.show', $post->id) }}">
                                <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="grid-img shadow-sm">
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <h3 class="text-muted text-center mt-5">No Posts Yet</h3>
            @endif
        </div>

        <div class="tab-pane fade" id="liked" role="tabpanel" aria-labelledby="liked-tab">
            <div class="row">
                @forelse ($user->likes as $like) 
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('post.show', $like->post->id) }}">
                            <img src="{{ $like->post->image }}" alt="post id {{ $like->post->id }}" class="grid-img shadow-sm" style="aspect-ratio: 1/1; object-fit: cover; width: 100%;">
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center mt-5">
                        <h3 class="text-muted">No Liked Posts Yet</h3>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection