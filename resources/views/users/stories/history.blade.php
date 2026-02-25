{{-- Stories history section for the profile page (visible to the profile owner only) --}}
{{-- Shows all stories including expired ones, with category filter buttons --}}

@php
    // Gather all unique categories from the user's stories (skip if category was deleted)
    $story_categories = collect();
    foreach ($user->stories as $story) {
        foreach ($story->categoryStory as $cs) {
            if ($cs->category && !$story_categories->contains('id', $cs->category->id)) {
                $story_categories->push($cs->category);
            }
        }
    }

    // Determine selected category filter from query string
    $selected_category = request('story_category');
@endphp

{{-- Category filter buttons --}}
<div class="mb-3 d-flex flex-wrap gap-2">
    <a href="{{ route('profile.show', $user->id) }}#stories-tab"
       class="btn btn-sm {{ !$selected_category ? 'btn-dark' : 'btn-outline-secondary' }}">
        All
    </a>
    @foreach($story_categories as $cat)
        <a href="{{ route('profile.show', $user->id) }}?story_category={{ $cat->id }}#stories-tab"
           class="btn btn-sm {{ $selected_category == $cat->id ? 'btn-dark' : 'btn-outline-secondary' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>

{{-- Stories grid (3 columns) --}}
@php
    $filtered_stories = $user->stories;
    if ($selected_category) {
        $filtered_stories = $filtered_stories->filter(fn($s) =>
            $s->categoryStory->contains('category_id', (int) $selected_category)
        );
    }
@endphp

@if($filtered_stories->isEmpty())
    <p class="text-muted small text-center py-3">No stories found.</p>
@else
    <div class="row g-2">
        @foreach($filtered_stories as $story)
            <div class="col-4">
                <div class="position-relative">
                    {{-- Story thumbnail image --}}
                    <a href="{{ route('story.show', $user->id) }}">
                        <img src="{{ $story->image }}" alt="Story"
                             class="w-100 rounded" style="aspect-ratio: 9/16; object-fit: cover;">
                    </a>

                    {{-- Expired badge (posted more than 24 hours ago) --}}
                    @if($story->created_at->lt(now()->subDay()))
                        <span class="badge bg-secondary position-absolute top-0 start-0 m-1"
                              style="font-size:0.65rem;">Expired</span>
                    @endif

                    {{-- Stats overlay --}}
                    <div class="position-absolute bottom-0 start-0 end-0 p-1 d-flex gap-2"
                         style="background: linear-gradient(transparent, rgba(0,0,0,0.6)); border-radius: 0 0 4px 4px;">
                        <span class="text-white" style="font-size:0.7rem;">
                            <i class="fa-solid fa-heart"></i> {{ $story->storyLikes->count() }}
                        </span>
                        <span class="text-white" style="font-size:0.7rem;">
                            <i class="fa-regular fa-comment"></i> {{ $story->storyComments->count() }}
                        </span>
                        <span class="text-white" style="font-size:0.7rem;">
                            <i class="fa-regular fa-eye"></i> {{ $story->storyViews->count() }}
                        </span>
                    </div>

                    {{-- Delete button (owner only) --}}
                    @if(Auth::user()->id === $user->id)
                        <form action="{{ route('story.destroy', $story->id) }}" method="POST"
                              class="position-absolute top-0 end-0 m-1"
                              onsubmit="return confirm('Delete this story?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger p-0 px-1"
                                    style="font-size:0.7rem; line-height:1.4;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
                <div class="text-muted mt-1" style="font-size:0.7rem;">
                    {{ $story->created_at->format('M d, Y') }}
                    @php
                        // Show category name, fallback to 'Uncategorized' if category was deleted
                        $cs_first = $story->categoryStory->first();
                        $cat_label = ($cs_first && $cs_first->category) ? $cs_first->category->name : 'Uncategorized';
                    @endphp
                    &middot; {{ $cat_label }}
                </div>
            </div>
        @endforeach
    </div>
@endif
