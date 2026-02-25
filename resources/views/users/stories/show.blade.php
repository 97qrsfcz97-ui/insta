@extends('layouts.app')

@section('title', 'Stories')

@section('content')

@if($active_stories->isEmpty())
    {{-- No active stories for this user --}}
    <div class="text-center py-5">
        <p class="text-muted">No active stories from {{ $target_user->name }}.</p>
        <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-sm">Back to Home</a>
    </div>
@else

{{-- Full-screen story viewer overlay --}}
<div class="story-viewer-overlay" id="storyOverlay">

    {{-- Left sidebar: story users list --}}
    <div class="story-users-sidebar d-none d-lg-flex">
        @foreach($story_users as $index => $su)
            @php
                $su_active = $su->stories()->active()->get();
                $su_all_viewed = $su_active->every(fn($s) => $s->isViewed());
            @endphp
            <a href="{{ route('story.show', $su->id) }}"
               class="text-decoration-none d-flex align-items-center gap-2 text-white
                      {{ $su->id === $target_user->id ? 'opacity-100' : 'opacity-50' }}">
                <div class="{{ $su_all_viewed ? 'story-ring-viewed' : 'story-ring-active' }}"
                     style="width:44px; height:44px; flex-shrink:0;">
                    @if($su->avatar)
                        <img src="{{ $su->avatar }}" alt="{{ $su->name }}"
                             class="rounded-circle" style="width:38px; height:38px; object-fit:cover; border:2px solid #000;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:38px; height:38px; background:#3a3a3a; border:2px solid #000;">
                            <i class="fa-solid fa-circle-user text-secondary" style="font-size:34px;"></i>
                        </div>
                    @endif
                </div>
                <span class="small text-truncate" style="max-width:100px;">{{ $su->name }}</span>
            </a>
        @endforeach
    </div>

    {{-- Story card --}}
    <div class="story-viewer-card" id="storyCard">

        {{-- Progress bars (one per story) --}}
        <div class="story-progress-bars" id="progressBars">
            @foreach($active_stories as $i => $story)
                <div class="story-progress-track">
                    <div class="story-progress-fill" id="progress-{{ $i }}"></div>
                </div>
            @endforeach
        </div>

        {{-- Top overlay: user info + close --}}
        <div class="story-viewer-top">
            <a href="{{ route('profile.show', $target_user->id) }}"
               class="d-flex align-items-center gap-2 text-decoration-none text-white">
                @if($target_user->avatar)
                    <img src="{{ $target_user->avatar }}" alt="{{ $target_user->name }}"
                         class="rounded-circle" style="width:36px; height:36px; object-fit:cover; border:2px solid #fff;">
                @else
                    <i class="fa-solid fa-circle-user" style="font-size:36px;"></i>
                @endif
                <div>
                    <div class="fw-bold small">{{ $target_user->name }}</div>
                    <div class="text-white-50" id="storyTimestamp" style="font-size:0.72rem;"></div>
                </div>
            </a>
            <div class="ms-auto d-flex gap-2">
                {{-- Play / Pause button --}}
                <button class="btn btn-sm btn-dark text-white border-0 p-1" id="playPauseBtn"
                        onclick="togglePause()" title="Play / Pause">
                    <i class="fa-solid fa-pause" id="playPauseIcon"></i>
                </button>
                {{-- Close button --}}
                <a href="{{ route('index') }}" class="btn btn-sm btn-dark text-white border-0 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </div>

        {{-- Story image --}}
        <img src="" alt="Story" class="story-viewer-img" id="storyImage">

        {{-- Left/right tap zones for manual navigation --}}
        <div class="story-tap-prev" onclick="goPrev()"></div>
        <div class="story-tap-next" onclick="goNext()"></div>

        {{-- Bottom overlay: like + comment --}}
        <div class="story-viewer-bottom">
            {{-- Like button (form POST, changes based on current story) --}}
            <div id="likeFormContainer"></div>

            {{-- Like count --}}
            <span class="text-white small" id="likeCount"></span>

            {{-- Comment button: opens modal --}}
            <button class="btn btn-sm btn-dark text-white border-0 ms-2"
                    data-bs-toggle="modal" data-bs-target="#commentModal"
                    id="commentBtn">
                <i class="fa-regular fa-comment"></i>
            </button>

            {{-- Description --}}
            <span class="text-white small ms-2 text-truncate" id="storyDescription" style="max-width:130px;"></span>

            {{-- Category badge --}}
            <span class="badge bg-secondary ms-auto text-white" id="storyCategory" style="font-size:0.68rem; white-space:nowrap;"></span>
        </div>
    </div>

</div>

{{-- Comment modal --}}
@include('users.stories.modals.comment')

@endif
@endsection

@push('scripts')
    @include('users.stories.scripts.viewer')
@endpush
