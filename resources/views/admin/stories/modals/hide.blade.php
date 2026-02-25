@if ($story->trashed())
    {{-- Unhide modal: restore a soft-deleted story --}}
    <div class="modal fade" id="unhide-story-{{ $story->id }}">
        <div class="modal-dialog">
            <div class="modal-content border-primary">
                <div class="modal-header border-primary">
                    <h3 class="h5 modal-title text-primary">
                        <i class="fa-solid fa-eye"></i> Unhide Story
                    </h3>
                </div>
                <div class="modal-body">
                    Are you sure you want to unhide this story from <strong>{{ $story->user->name }}</strong>?
                    <div class="mt-3">
                        <img src="{{ $story->image }}" alt="Story {{ $story->id }}"
                             class="d-block" style="width:80px; height:120px; object-fit:cover; border-radius:4px;">
                        <p class="mt-2 text-muted small">Posted: {{ $story->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <form action="{{ route('admin.stories.unhide', $story->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Unhide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- Hide modal: soft-delete a visible story --}}
    <div class="modal fade" id="hide-story-{{ $story->id }}">
        <div class="modal-dialog">
            <div class="modal-content border-danger">
                <div class="modal-header border-danger">
                    <h3 class="h5 modal-title text-danger">
                        <i class="fa-solid fa-eye-slash"></i> Hide Story
                    </h3>
                </div>
                <div class="modal-body">
                    Are you sure you want to hide this story from <strong>{{ $story->user->name }}</strong>?
                    <div class="mt-3">
                        <img src="{{ $story->image }}" alt="Story {{ $story->id }}"
                             class="d-block" style="width:80px; height:120px; object-fit:cover; border-radius:4px;">
                        <p class="mt-2 text-muted small">Posted: {{ $story->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <p class="text-danger small mb-0">Hidden stories will no longer appear to other users.</p>
                </div>
                <div class="modal-footer border-0">
                    <form action="{{ route('admin.stories.hide', $story->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Hide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
