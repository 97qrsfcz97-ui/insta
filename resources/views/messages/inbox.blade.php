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
                                    <a href="{{ route('message.index', $following_user->id) }}"
                                        class="text-decoration-none text-dark d-flex align-items-center gap-3">
                                        @if ($following_user->avatar)
                                            <img src="{{ $following_user->avatar }}" alt="{{ $following_user->name }}"
                                                class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
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

                    </div>

                    <div class="flex-grow-1 overflow-auto p-4 chat-container bg-white" id="chatContainer">





                    </div>

                    <div class="p-3 border-top bg-white">

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

        document.getElementById('image-upload').addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                document.querySelector('input[name="body"]').placeholder = "Image selected! Add text or hit Send.";
            }
        });
    </script>
@endsection