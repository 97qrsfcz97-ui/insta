@extends('layouts.app')

@section('title')

@section('content')
    @include('users.profile.header')

    <div style="margin-top: 100px">
        <div class="row justify-content-center">
            <div class="col-8">
                <h2 class="h3 mb-3 fw-light text-muted">Following</h2>

                
                @foreach ($user->following as $following)
                    <div class="row mb-2">
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $following->following->id) }}">
                                @if ($following->following->avatar)
                                    <img src="{{ $following->following->avatar }}" alt="post id {{ $following->following->id }}" class="rounded-circle avatar-md">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                                @endif
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('profile.show', $following->following->id) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $following->following->name }}
                            </a>
                        </div>
                        <div class="col-4">
                            @if (Auth::user()->id !== $following->following->id)
                                @if ($following->following->isFollowed())
                                    <form action="{{ route('follow.destroy', $following->following->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-secondary border-0 btn-sm fw-bold">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow.store', $following->following->id) }}" method="post">
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
