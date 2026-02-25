@extends('layouts.app')

@section('title', 'DM')

@section('content')
    <div class="container justify-content-center">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    
                    <div class="card-header bg-white border-bottom d-flex align-items-center py-3">
                        <a href="{{ route('profile.show', $receiver->id) }}"
                            class="text-decoration-none text-dark d-flex align-items-center gap-2 fw-bold">
                            @if ($receiver->avatar)
                                <img src="{{ $receiver->avatar }}" alt="{{ $receiver->name }}" class="rounded-circle"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary" style="font-size: 40px;"></i>
                            @endif
                            <span class="fs-5">{{ $receiver->name }}</span>
                        </a>
                    </div>

                    <div class="card-body chat-container" id="chatContainer">
                        @forelse ($messages as $message)
                            @if ($message->sender_id === Auth::id())
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="message-bubble message-sent shadow-sm">
                                        @if ($message->image)
                                            <img src="{{ $message->image }}" alt="sent image" class="img-fluid rounded mb-1" style="max-width: 200px;">
                                            @if($message->body) <br> @endif
                                        @endif
                                        {{ $message->body }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-start mb-3 align-items-end gap-2">
                                    @if ($receiver->avatar)
                                        <img src="{{ $receiver->avatar }}" alt="{{ $receiver->name }}" class="rounded-circle mb-1"
                                            style="width: 28px; height: 28px; object-fit: cover;">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary mb-1" style="font-size: 28px;"></i>
                                    @endif
                                    
                                    <div class="message-bubble message-received shadow-sm">
                                        @if ($message->image)
                                            <img src="{{ $message->image }}" alt="received image" class="img-fluid rounded mb-1" style="max-width: 200px;">
                                            @if($message->body) <br> @endif
                                        @endif
                                        {{ $message->body }}
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="text-center text-muted my-5">
                                <p>Send a message to start chatting!</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="card-footer bg-white border-top p-3">
                        <form action="{{ route('message.store', $receiver->id) }}" method="POST" enctype="multipart/form-data"
                            class="d-flex align-items-center gap-2">
                            @csrf
                            

                            <label for="image-upload" class="btn text-secondary mb-0 p-2" style="cursor: pointer;" title="Send Image">
                                <i class="fa-regular fa-image fs-4"></i>
                            </label>
                            <input type="file" name="image" id="image-upload" class="d-none" accept="image/*">

                            <input type="text" name="body" class="form-control rounded-pill bg-light border-0 px-4"
                                placeholder="Message..." autocomplete="off">
                                
                            <button type="submit" class="btn text-primary fw-bold bg-transparent border-0">Send</button>
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
    </script>
@endsection