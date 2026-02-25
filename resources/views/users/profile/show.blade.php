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
        {{-- Stories tab: visible only to the profile owner --}}
        @if(Auth::user()->id === $user->id)
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center gap-1" id="stories-tab" data-bs-toggle="tab" data-bs-target="#stories" type="button" role="tab" aria-selected="false">
                    <i class="fa-solid fa-circle-play"></i>
                </button>
            </li>
        @endif
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

        {{-- Stories history tab (owner only) --}}
        @if(Auth::user()->id === $user->id)
            <div class="tab-pane fade" id="stories" role="tabpanel" aria-labelledby="stories-tab">
                @include('users.stories.history')
            </div>
        @endif

    </div>

@endsection

@push('scripts')
<script>
    // Auto-activate the Stories tab when the URL contains the story_category query parameter
    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        if (params.has('story_category')) {
            const storiesTab = document.getElementById('stories-tab');
            if (storiesTab) {
                const tab = new bootstrap.Tab(storiesTab);
                tab.show();
            }
        }
    });
</script>
@endpush