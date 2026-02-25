@extends('layouts.app')

@section('title', 'DM Box')

@section('content')
<div class="container justify-content-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Messages</h5>
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
        </div>
    </div>
</div>
@endsection