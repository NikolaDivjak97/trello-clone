<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vendor/select2/js/select2.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('js/global.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/board.css') }}">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid w-full px-5">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'TrelloClone') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                        @else
                            <div class="dropdown ml-3">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="boardsDropdown" data-toggle="dropdown">
                                    Boards
                                </button>
                                <div class="dropdown-menu" aria-labelledby="boardsDropdown">
                                    @forelse(auth()->user()->boards as $userBoard)
                                        <a class="dropdown-item" href="{{ route('boards.show', $userBoard) }}">
                                            {{ $userBoard -> name }}
                                        </a>
                                    @empty
                                        <p class="text-center mb-0">No boards available</p>
                                    @endforelse
                                </div>
                            </div>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                        @if((request()->route()->getName() === 'home'))
                            <li class="nav-item d-flex align-items-center mr-3">
                                @if(auth() -> user() -> is_admin)
                                    <a class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#create-team">Create team</a>
                                @endif
                                <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#create-board">Create board</a>
                            </li>
                        @endif
                        <li class="nav-item d-flex align-items-center gap-2 position-relative">
                            <div class="bell" data-user="{{ auth()->id() }}" data-url="{{ route('notifications.viewed') }}">
                                <img width="20" height="20" src="{{ asset('images/notification-bell.png') }}" alt="">
                                <span class="badge badge-danger badge-pill position-absolute notification-number" style="top: -8px; right: 11px; @if(!auth()->user()->notifications() -> where('is_seen', false) -> count()) display: none; @endif">{{ auth()->user()->notifications () -> where('is_seen', false) -> count() }}</span>
                            </div>

                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if(auth() -> user() -> is_admin)
                                        <a class="dropdown-item" href="{{ route('dashboard.index') }}">Dashboard</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('users.profile') }}">Profile</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @include('components.notifications', ['notifications' => auth()->user() ? auth()->user()->notifications() -> where('is_seen', false) -> get() : []])

        <main style="position: relative;">
            @yield('content')
        </main>



    </div>

@yield('js')

@if(auth()->user())
    <script defer>
        const userId = '{{ auth()->id() }}';
        const r_get_notifications = "{{ route('notifications.new') }}";

        $(document).ready(function() {
            Echo.private('notifications.' + userId)
                .listen('NewNotification', (event) => {
                    notifyAndUpdate();
                });
        });

    </script>
@endif

</body>
</html>
