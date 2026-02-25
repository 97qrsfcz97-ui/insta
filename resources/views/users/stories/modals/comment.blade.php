{{-- Comment modal for the story viewer --}}
{{-- Triggered by data-bs-target="#commentModal" on the comment button --}}
{{-- Modal body and form action are updated dynamically by the viewer JS --}}
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="commentModalBody">
                {{-- Populated dynamically by renderComments() in viewer.blade.php --}}
            </div>

            <div class="modal-footer flex-column align-items-stretch">
                {{-- Form action is set by JS when the active story changes --}}
                <form id="commentForm" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm"
                               id="commentInput" placeholder="Add a comment..."
                               maxlength="100">
                        <button type="submit" class="btn btn-primary btn-sm">Post</button>
                    </div>
                    <div class="text-danger small mt-1" id="commentError"></div>
                </form>
            </div>

        </div>
    </div>
</div>
