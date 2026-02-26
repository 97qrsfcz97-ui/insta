<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Grand+Hotel&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
</head>

<body class="bg-white text-dark">
    <div id="app" class="d-flex @auth has-sidebar @endauth">
        
        @auth
        <nav class="sidebar sidebar-wrapper d-flex flex-column bg-white border-end">
            <a href="{{ url('/') }}" class="navbar-brand sidebar-brand mb-4 mt-2 px-3 text-dark text-decoration-none" title="Instagram">
                Instagram
            </a>

            <ul class="nav flex-column gap-1 mb-auto">
                <li class="nav-item">
                    <a href="{{ route('index') }}" class="nav-link text-dark p-3 sidebar-link d-flex align-items-center" title="Home">
                        <i class="fa-solid fa-house fa-fw fs-4 me-3"></i>
                        <span class="fs-6">Home</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('search') }}" class="nav-link text-dark p-3 sidebar-link d-flex align-items-center" title="Search">
                        <i class="fa-solid fa-magnifying-glass fa-fw fs-4 me-3"></i> 
                        <span class="fs-6">Search</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('message.inbox') }}" class="nav-link text-dark p-3 sidebar-link d-flex align-items-center" title="Messages">
                        <i class="fa-regular fa-message fa-fw fs-4 me-3"></i> 
                        <span class="fs-6">Messages</span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link text-dark p-3 sidebar-link d-flex align-items-center" id="createDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Create">
                        <i class="fa-regular fa-square-plus fa-fw fs-4 me-3"></i> 
                        <span class="fs-6">Create</span>
                    </a>
                    <ul class="dropdown-menu sidebar-dropdown text-small shadow border-0" aria-labelledby="createDropdown">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('post.create') }}">
                                <i class="fa-solid fa-newspaper me-2"></i> Post
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('story.create') }}">
                                <i class="fa-solid fa-circle-play me-2"></i> Story
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('profile.show', Auth::user()->id) }}" class="nav-link text-dark p-3 sidebar-link d-flex align-items-center" title="Profile">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="avatar" class="rounded-circle me-3" style="width: 26px; height: 26px; object-fit: cover;">
                        @else
                            <i class="fa-regular fa-circle-user fa-fw fs-4 me-3"></i>
                        @endif
                        <span class="fs-6">Profile</span>
                    </a>
                </li>
            </ul>

            <div class="dropdown mt-auto">
                <a href="#" class="nav-link text-dark p-3 sidebar-link d-flex align-items-center" id="dropdownMore" data-bs-toggle="dropdown" aria-expanded="false" title="More">
                    <i class="fa-solid fa-bars fa-fw fs-4 me-3"></i> 
                    <span class="fs-6">More</span>
                </a>
                <ul class="dropdown-menu sidebar-dropdown text-small shadow border-0" aria-labelledby="dropdownMore">
                    <li>
                        <button id="theme-toggle" class="dropdown-item py-2 d-flex align-items-center shadow-none border-0 bg-transparent w-100 text-start" title="Toggle Theme">
                            <i id="theme-icon" class="fa-regular fa-moon me-2"></i> Switch the view
                        </button>
                    </li>
                    @can('admin')
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('admin.users') }}" title="Admin">
                                <i class="fa-solid fa-user-gear me-2"></i> Admin
                            </a>
                        </li>
                    @endcan
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        @endauth

        <main class="flex-grow-1 main-content">
            <div class="pt-0 py-5">
                <div class="container pt-3">
                    <div class="row justify-content-center">
                        @if (request()->is('admin/*'))
                            <div class="col-3">
                                <div class="list-group shadow-sm">
                                    <a href="{{ route('admin.users') }}" class="list-group-item {{ request()->is('admin/users') ? 'active' : '' }}">
                                        <i class="fa-solid fa-users"></i> Users
                                    </a>
                                    <a href="{{ route('admin.posts') }}" class="list-group-item {{ request()->is('admin/posts') ? 'active' : '' }}">
                                        <i class="fa-solid fa-newspaper"></i> Posts
                                    </a>
                                    <a href="{{ route('admin.categories') }}" class="list-group-item {{ request()->is('admin/categories') ? 'active' : '' }}">
                                        <i class="fa-solid fa-tags"></i> Categories
                                    </a>
                                    <a href="{{ route('admin.stories') }}" class="list-group-item {{ request()->is('admin/stories') ? 'active' : '' }}">
                                        <i class="fa-solid fa-circle-play"></i> Stories
                                    </a>
                                </div>
                            </div>
                            <div class="col-9">
                                @yield('content')
                            </div>
                        @else
                            <div class="col-12">
                                @yield('content')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
        
    </div>

    @stack('scripts')

    <script>
        const toggleBtn = document.getElementById('theme-toggle');
        const icon = document.getElementById('theme-icon');
        const body = document.body;

        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            if(icon) icon.classList.replace('fa-moon', 'fa-sun');
        }

        if(toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation(); 
                body.classList.toggle('dark-mode');
                if (body.classList.contains('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                    icon.classList.replace('fa-moon', 'fa-sun');
                } else {
                    localStorage.setItem('theme', 'light');
                    icon.classList.replace('fa-sun', 'fa-moon');
                }
            });
        }
    </script>
</body>
</html>