@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container w-100">
        <div class="row justify-content-center">
            <div>
                <div class="card border shadow-none mb-3">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="logo-font text-center mt-1 mb-4">{{ config('app.name') }}</h1>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <input id="email" type="email"
                                    class="form-control bg-light text-muted @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input id="password" type="password"
                                    class="form-control bg-light text-muted @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="current-password" placeholder="Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 mt-4">
                                <button type="submit" class="btn w-100 fw-bold text-white shadow-none bg-info">
                                    {{ __('Login') }}
                                </button>
                            </div>

                            <div class="d-flex align-items-center my-4">
                                <hr class="flex-grow-1 text-muted opacity-25">
                                <span class="mx-3 text-muted fw-bold" style="font-size: 0.85rem;">OR</span>
                                <hr class="flex-grow-1 text-muted opacity-25">
                            </div>

                            <div class="text-center">
                            <p class="mb-0 text-dark" style="font-size: 0.9rem;">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #0095f6;">
                                    Register
                                </a>
                            </p>
                        </div>

                            {{-- @if (Route::has('password.request'))
                            <div class="text-center">
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            </div>
                            @endif --}}
                        </form>
                    </div>
                </div>

                {{-- <div class="card border shadow-none">
                    <div class="card-body text-center py-3">
                        <p class="mb-0 text-dark">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #0095f6;">
                                Register
                            </a>
                        </p>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
@endsection