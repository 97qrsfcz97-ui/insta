@extends('layouts.app')

@section('title', 'Direct Message')

@section('content')
    <div class="container-fluid px-4 py-2">
        <div class="card shadow-sm border-0 bg-white" style="height: 85vh; border-radius: 10px; overflow: hidden;">
            <div class="row g-0 h-100">
                
                <div class="col-4 border-end h-100 d-flex flex-column bg-white">

                    <div class="p-3 border-bottom d-flex align-items-center justify-content-center">
                        <span class="fw-bold fs-5">DM</span>
                    </div>
                    
                    <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($following_users as $following_user)
                            <li class="list-group-item p-3 border-bottom-0 message-list-item">
                                <a href="{{ route('message.index', $following_user->id) }}" class="text-decoration-none text-dark d-flex align-items-center gap-3">
                                    @if ($following_user->avatar)
                                        <img src="{{ $following_user->avatar }}" alt="{{ $following_user->name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary" style="font-size: 50px;"></i>
                                    @endif
                                    <div>
                                        <div class="fw-bold fs-6">{{ $following_user->name }}</div>
                                        <div class="text-muted small">Tap to chat</div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <div class="text-center text-muted my-5">
                                <i class="fa-regular fa-paper-plane fs-1 mb-3"></i>
                                <p>You don't follow anyone yet.<br>Follow someone to start chatting!</p>
                            </div>
                        @endforelse
                    </ul>
                </div>
                </div>

                <div class="col-8 h-100 d-flex flex-column bg-white">

                    <div class="p-3 border-bottom d-flex align-items-center gap-2">
                        <a href="{{ route('profile.show', $receiver->id) }}" class="text-decoration-none text-dark d-flex align-items-center fw-bold">
                            @if ($receiver->avatar)
                                <img src="{{ $receiver->avatar }}" alt="{{ $receiver->name }}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary me-2 fs-3"></i>
                            @endif
                            <span class="fs-5">{{ $receiver->name }}</span>
                        </a>
                    </div>

                    <div class="flex-grow-1 overflow-auto p-4 chat-container bg-white" id="chatContainer">
                        @forelse ($messages as $message)
                            @if ($message->sender_id === Auth::id())
                                {{-- me --}}
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="message-bubble message-sent shadow-sm text-white" style="background-color: #0095f6; border-radius: 18px 18px 4px 18px; padding: 10px 15px; max-width: 70%;">
                                        @if ($message->image)
                                            <img src="{{ $message->image }}" alt="sent image" class="img-fluid rounded mb-2" style="max-width: 100%;">
                                        @endif
                                        {{ $message->body }}
                                    </div>
                                </div>
                            @else
                                {{-- friends --}}
                                <div class="d-flex justify-content-start mb-3 align-items-end gap-2">
                                    @if ($receiver->avatar)
                                        <img src="{{ $receiver->avatar }}" alt="{{ $receiver->name }}" class="rounded-circle mb-1" style="width: 28px; height: 28px; object-fit: cover;">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary mb-1" style="font-size: 28px;"></i>
                                    @endif
                                    
                                    <div class="message-bubble message-received shadow-sm bg-light text-dark" style="border-radius: 18px 18px 18px 4px; padding: 10px 15px; max-width: 70%;">
                                        @if ($message->image)
                                            <img src="{{ $message->image }}" alt="received image" class="img-fluid rounded mb-2" style="max-width: 100%;">
                                        @endif
                                        {{ $message->body }}
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="text-center text-muted my-5 d-flex flex-column align-items-center justify-content-center h-100">
                                <i class="fa-regular fa-message fs-1 mb-3"></i>
                                <h5>Your Messages</h5>
                                <p>Send a message to start chatting.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-3 border-top bg-white">
                        <form action="{{ route('message.store', $receiver->id) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center border rounded-pill px-3 py-1">
                            @csrf
                            <label for="image-upload" class="btn text-dark mb-0 p-2 border-0 shadow-none" style="cursor: pointer;" title="Send Image">
                                <i class="fa-regular fa-image fs-5"></i>
                            </label>
                            <input type="file" name="image" id="image-upload" class="d-none" accept="image/*">

                            <input type="text" name="body" class="form-control border-0 shadow-none bg-transparent" placeholder="Message..." autocomplete="off">
                                
                            <button type="submit" class="btn text-primary fw-bold bg-transparent border-0 shadow-none">Send</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            var chatContainer = document.getElementById("chatContainer");
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        document.getElementById('image-upload').addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                document.querySelector('input[name="body"]').placeholder = "Image selected! Add text or hit Send.";
            }
        });
    </script>
@endsection