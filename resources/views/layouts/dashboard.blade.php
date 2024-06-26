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
    <script src="{{ asset('js/utils.js') }}" defer></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" defer></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
    @yield('css')
</head>
<body>

<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid w-full px-5">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'TrelloClone') }}
            </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li style="cursor: pointer">
                        <span class="navbar-toggler-icon" id="sidebarCollapse"></span>
                    </li>
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
                        <li class="nav-item d-flex align-items-center gap-2 position-relative">

                            <span class="badge badge-info text-white rounded p-2" id="important-info" style="cursor: pointer" title="CRUD operations not available on Demo version">?</span>

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

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h5 class="mb-0"><a href="{{ route('dashboard.index') }}">Dashboard</a></h5>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">Users</a>
                </li>
                <li class="{{ request()->routeIs('teams.index') ? 'active' : '' }}">
                    <a href="{{ route('teams.index') }}">Teams</a>
                </li>
                <li class="{{ request()->routeIs('boards.index') ? 'active' : '' }}">
                    <a href="{{ route('boards.index') }}">Boards</a>
                </li>
            </ul>
        </nav>

        <div id="content">
            @yield('content')
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

@yield('js')

</body>
</html>
