{{-- Story viewer JavaScript --}}
{{-- Included via @push('scripts') in users.stories.show --}}
{{-- Blade variables ($stories_data, $story_users_data, $current_user_index) --}}
{{-- are injected by StoryController::show() --}}
<script>
    // ---------------------------------------------------------------------------
    // Data injected by StoryController::show()
    // ---------------------------------------------------------------------------
    const STORIES            = @json($stories_data);
    const STORY_USERS        = @json($story_users_data);
    const CURRENT_USER_INDEX = {{ $current_user_index }};
    const AUTH_USER_ID       = {{ Auth::user()->id }};
    const CSRF_TOKEN         = '{{ csrf_token() }}';
    const STORY_DURATION     = 5000; // milliseconds per story

    // ---------------------------------------------------------------------------
    // Playback state
    // ---------------------------------------------------------------------------
    let currentIndex    = 0;
    let timer           = null;
    let isPaused        = false;
    let progressStart   = null;
    let progressElapsed = 0;

    // ---------------------------------------------------------------------------
    // Show the story at the given index
    // ---------------------------------------------------------------------------
    function showStory(index) {
        if (index < 0 || index >= STORIES.length) return;

        currentIndex = index;
        const story  = STORIES[index];

        // Update image
        document.getElementById('storyImage').src = story.image;

        // Update timestamp
        document.getElementById('storyTimestamp').textContent = story.created_at;

        // Update description
        document.getElementById('storyDescription').textContent = story.description;

        // Update category badge
        document.getElementById('storyCategory').textContent = story.category_name;

        // Update like count
        document.getElementById('likeCount').textContent =
            story.like_count + (story.like_count === 1 ? ' like' : ' likes');

        // Update like button (POST form to toggle like)
        renderLikeForm(story);

        // Update comment modal content
        renderComments(story);

        // Update progress bars
        updateProgressBars(index);

        // Record view (fire-and-forget)
        recordView(story.view_url);

        // Start auto-advance timer
        startTimer();
    }

    // ---------------------------------------------------------------------------
    // Render the like toggle form
    // ---------------------------------------------------------------------------
    function renderLikeForm(story) {
        const container = document.getElementById('likeFormContainer');
        if (story.liked) {
            container.innerHTML = `
                <form action="${story.like_destroy_url}" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-dark text-white border-0 p-1">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </button>
                </form>`;
        } else {
            container.innerHTML = `
                <form action="${story.like_store_url}" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                    <button type="submit" class="btn btn-sm btn-dark text-white border-0 p-1">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </form>`;
        }
    }

    // ---------------------------------------------------------------------------
    // Render comments in the modal body
    // ---------------------------------------------------------------------------
    function renderComments(story) {
        const body = document.getElementById('commentModalBody');
        if (story.comments.length === 0) {
            body.innerHTML = '<p class="text-muted small text-center">No comments yet.</p>';
        } else {
            body.innerHTML = story.comments.map(c => `
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="fw-bold small">${c.user_name}</span>
                        <span class="small ms-2">${c.body}</span>
                    </div>
                    ${c.user_id === AUTH_USER_ID ? `
                        <form action="${c.destroy_url}" method="POST" class="d-inline ms-2">
                            <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm text-danger border-0 p-0">
                                <i class="fa-solid fa-trash" style="font-size:0.75rem;"></i>
                            </button>
                        </form>` : ''}
                </div>`).join('');
        }

        // Update form action to the current story's comment store URL
        document.getElementById('commentForm').action = story.comment_store_url;

        // Replace hidden input that carries the comment body field name
        const existing = document.getElementById('hiddenCommentBody');
        if (existing) existing.remove();

        const hiddenInput  = document.createElement('input');
        hiddenInput.type   = 'hidden';
        hiddenInput.name   = 'comment_body' + story.id;
        hiddenInput.id     = 'hiddenCommentBody';
        document.getElementById('commentForm').appendChild(hiddenInput);
    }

    // ---------------------------------------------------------------------------
    // Update progress bar fill states
    // ---------------------------------------------------------------------------
    function updateProgressBars(activeIndex) {
        for (let i = 0; i < STORIES.length; i++) {
            const fill = document.getElementById('progress-' + i);
            fill.style.transition = 'none';
            if (i < activeIndex) {
                fill.style.width = '100%';
            } else if (i === activeIndex) {
                fill.style.width  = '0%';
                progressElapsed   = 0;
                progressStart     = null;
            } else {
                fill.style.width = '0%';
            }
        }
    }

    // ---------------------------------------------------------------------------
    // Start the auto-advance timer and progress bar animation
    // ---------------------------------------------------------------------------
    function startTimer() {
        clearTimeout(timer);

        if (isPaused) return;

        const fill      = document.getElementById('progress-' + currentIndex);
        const remaining = STORY_DURATION - progressElapsed;

        // Animate progress bar over the remaining duration
        fill.style.transition = `width ${remaining}ms linear`;
        fill.style.width      = '100%';

        progressStart = Date.now();
        timer = setTimeout(() => {
            progressElapsed = 0;
            goNext();
        }, remaining);
    }

    // ---------------------------------------------------------------------------
    // Record that the auth user has viewed this story (fire-and-forget)
    // ---------------------------------------------------------------------------
    function recordView(url) {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json',
                'Accept':       'application/json',
            }
        });
    }

    // ---------------------------------------------------------------------------
    // Navigation
    // ---------------------------------------------------------------------------

    // Advance to the next story; if last story, move to the next user
    function goNext() {
        if (currentIndex < STORIES.length - 1) {
            showStory(currentIndex + 1);
        } else {
            goToNextUser();
        }
    }

    // Go back to the previous story
    function goPrev() {
        if (currentIndex > 0) {
            showStory(currentIndex - 1);
        }
    }

    // Navigate to the next user's story page (or home if no more users)
    function goToNextUser() {
        const nextIndex = CURRENT_USER_INDEX + 1;
        if (nextIndex < STORY_USERS.length) {
            window.location.href = STORY_USERS[nextIndex].show_url;
        } else {
            window.location.href = '/';
        }
    }

    // ---------------------------------------------------------------------------
    // Play / Pause toggle
    // ---------------------------------------------------------------------------
    function togglePause() {
        isPaused = !isPaused;
        const icon = document.getElementById('playPauseIcon');

        if (isPaused) {
            clearTimeout(timer);

            // Capture elapsed time so we can resume accurately
            if (progressStart) {
                progressElapsed += Date.now() - progressStart;
            }

            // Freeze the progress bar at its current visual position
            const fill         = document.getElementById('progress-' + currentIndex);
            const computedWidth = parseFloat(getComputedStyle(fill).width);
            const trackWidth   = fill.parentElement.offsetWidth;
            fill.style.transition = 'none';
            fill.style.width      = (computedWidth / trackWidth * 100) + '%';

            icon.classList.replace('fa-pause', 'fa-play');
        } else {
            icon.classList.replace('fa-play', 'fa-pause');
            startTimer();
        }
    }

    // ---------------------------------------------------------------------------
    // Initialisation
    // ---------------------------------------------------------------------------
    document.addEventListener('DOMContentLoaded', function () {

        // Sync visible comment input value into the hidden field on submit
        document.getElementById('commentForm').addEventListener('submit', function () {
            const visible = document.getElementById('commentInput');
            const hidden  = document.getElementById('hiddenCommentBody');
            if (hidden) hidden.value = visible.value;
        });

        // Pause the story when the comment modal opens (if not already paused)
        // Resume automatically when the modal is fully closed
        let pausedByModal    = false;
        const commentModal   = document.getElementById('commentModal');

        commentModal.addEventListener('show.bs.modal', function () {
            if (!isPaused) {
                togglePause();
                pausedByModal = true;
            }
        });

        commentModal.addEventListener('hidden.bs.modal', function () {
            if (pausedByModal) {
                togglePause();
                pausedByModal = false;
            }
        });

        // Start playback from the first story on page load
        showStory(0);
    });
</script>
