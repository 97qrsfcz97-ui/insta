@extends('layouts.app')

@section('title')

@section('content')
    @include('users.profile.header')

    <div style="margin-top: 100px">
        <div class="row justify-content-center">
            <div class="col-8">
                <h2 class="h3 mb-3 fw-light text-muted text-center">Followers</h2>

                
                @foreach ($user->followers as $follower)
                    <div class="row mb-2">
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $follower->follower->id) }}">
                                @if ($follower->follower->avatar)
                                    <img src="{{ $follower->follower->avatar }}" alt="post id {{ $follower->follower->id }}" class="rounded-circle avatar-md">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                                @endif
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('profile.show', $follower->follower->id) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $follower->follower->name }}
                            </a>
                        </div>
                        <div class="col-4">
                            @if (Auth::user()->id !== $follower->follower->id)
                                @if ($follower->follower->isFollowed())
                                    <form action="{{ route('follow.destroy', $follower->follower->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-secondary btn-sm border-0 fw-bold">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow.store', $follower->follower->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection