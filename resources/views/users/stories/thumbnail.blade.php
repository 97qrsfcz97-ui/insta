{{-- Stories thumbnail strip: shows up to 10 users with active stories --}}
{{-- Unviewed stories show a gradient ring; viewed stories show a gray ring --}}
<div class="d-flex overflow-auto pb-3 mb-3 story-thumbnail-strip gap-3 align-items-center">

    <a href="{{ route('story.create') }}" class="text-decoration-none text-center story-avatar-item">
        <div class="story-ring-create">
            <div class="story-avatar-placeholder rounded-circle d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-plus"></i>
            </div>
        </div>
        <div class="story-avatar-name small mt-1 text-truncate" style="max-width: 70px;">
            <p></p>
        </div>
    </a>
    @foreach($story_users as $story_user)
        @php
            // Check if all active stories for this user have been viewed by the auth user
            $active_stories = $story_user->stories()->active()->get();
            $all_viewed = $active_stories->every(fn($s) => $s->isViewed());
        @endphp

        <a href="{{ route('story.show', $story_user->id) }}" class="text-decoration-none text-center story-avatar-item">
            <div class="story-ring {{ $all_viewed ? 'story-ring-viewed' : 'story-ring-active' }} mx-auto">
                @if($story_user->avatar)
                    <img src="{{ $story_user->avatar }}" alt="{{ $story_user->name }}" class="rounded-circle story-avatar-img">
                @else
                    <div class="story-avatar-placeholder rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-circle-user story-avatar-icon text-secondary"></i>
                    </div>
                @endif
            </div>
            <div class="story-avatar-name small mt-1 text-truncate" style="max-width: 70px;">
                {{ $story_user->id === Auth::user()->id ? 'You' : $story_user->name }}
            </div>
        </a>
    @endforeach

    @if($story_users->isEmpty())
        <p class="text-muted small mb-0">No active stories from people you follow.</p>
    @endif
</div>
