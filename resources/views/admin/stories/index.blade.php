@extends('layouts.app')

@section('title', 'Admin: Stories')

@section('content')
    <table class="table table-hover align-middle bg-white border text-secondary">
        <thead class="small table-primary text-secondary">
            <tr>
                <th></th>
                <th>CATEGORY</th>
                <th>OWNER</th>
                <th>CREATED AT</th>
                <th>STATS</th>
                <th>VISIBILITY</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($all_stories as $story)
                <tr>
                    <td>
                        <img src="{{ $story->image }}" alt="Story {{ $story->id }}"
                             class="d-block" style="width: 80px; height: 120px; object-fit: cover; border-radius: 4px;">
                    </td>
                    <td>
                        @forelse ($story->categoryStory as $cs)
                            <span class="badge bg-primary">{{ $cs->category->name ?? 'Uncategorized' }}</span>
                        @empty
                            <span class="badge bg-dark">Uncategorized</span>
                        @endforelse
                    </td>
                    <td>{{ $story->user->name }}</td>
                    <td>
                        {{ $story->created_at->format('M d, Y H:i') }}
                        {{-- Show active / expired badge based on age --}}
                        @if($story->created_at->lt(now()->subDay()))
                            <br><span class="badge bg-secondary" style="font-size:0.65rem;">Expired</span>
                        @else
                            <br><span class="badge bg-success" style="font-size:0.65rem;">Active</span>
                        @endif
                    </td>
                    <td>
                        <span class="small">
                            <i class="fa-solid fa-heart text-danger"></i> {{ $story->storyLikes->count() }}
                        </span>
                        <br>
                        <span class="small">
                            <i class="fa-regular fa-eye text-secondary"></i> {{ $story->storyViews->count() }}
                        </span>
                    </td>
                    <td>
                        {{-- Visibility status (mirrors posts admin) --}}
                        @if ($story->trashed())
                            <i class="fa-regular fa-circle text-secondary"></i> &nbsp; Hidden
                        @else
                            <i class="fa-solid fa-circle text-primary"></i> &nbsp; Visible
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <div class="dropdown-menu">
                                @if ($story->trashed())
                                    {{-- Story is hidden: offer Unhide --}}
                                    <button class="dropdown-item text-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#unhide-story-{{ $story->id }}">
                                        <i class="fa-solid fa-eye"></i> Unhide Story {{ $story->id }}
                                    </button>
                                @else
                                    {{-- Story is visible: offer Hide --}}
                                    <button class="dropdown-item text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#hide-story-{{ $story->id }}">
                                        <i class="fa-solid fa-eye-slash"></i> Hide Story {{ $story->id }}
                                    </button>
                                @endif
                            </div>
                        </div>
                        @include('admin.stories.modals.hide')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $all_stories->links() }}
    </div>
@endsection
