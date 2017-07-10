<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <?xml version="1.0" encoding="utf-8"?>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="-527.2 390.3 55.9 29.6" enable-background="new -527.2 390.3 55.9 29.6" xml:space="preserve">
                          <g>
                            <path fill="#feae3b" d="M-489.6,399.1h17.3c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1h-17.3c-0.6,0-1,0.5-1,1v6.7
                            C-490.6,398.6-490.1,399.1-489.6,399.1z"/>
                            <path fill="#feae3b" d="M-510.9,399.1h17.3c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1h-17.3c-0.6,0-1,0.5-1,1v6.7
                            C-511.9,398.6-511.5,399.1-510.9,399.1z"/>
                            <path fill="#feae3b" d="M-526.2,399.1h11.4c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1h-11.4c-0.6,0-1,0.5-1,1v6.7
                            C-527.2,398.6-526.7,399.1-526.2,399.1z"/>
                            <path fill="#feae3b" d="M-472.2,411.2h-17.3c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h17.3c0.6,0,1-0.5,1-1v-6.7
                            C-471.2,411.7-471.7,411.2-472.2,411.2z"/>
                            <path fill="#feae3b" d="M-493.6,411.2h-17.3c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h17.3c0.6,0,1-0.5,1-1v-6.7
                            C-492.6,411.7-493,411.2-493.6,411.2z"/>
                            <path fill="#feae3b" d="M-514.8,411.2h-11.4c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h11.4c0.6,0,1-0.5,1-1v-6.7
                            C-513.7,411.7-514.2,411.2-514.8,411.2z"/>
                            <path fill="#feae3b" d="M-508.8,409.6c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1h-17.3c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1H-508.8z"
                            />
                            <path fill="#feae3b" d="M-504.7,400.8c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h17.3c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1H-504.7z"/>
                            <path fill="#feae3b" d="M-472.2,400.8h-11.4c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h11.4c0.6,0,1-0.5,1-1v-6.7
                            C-471.2,401.3-471.7,400.8-472.2,400.8z"/>
                          </g>
                        </svg>
                        Dashboard
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <img src="{{ Auth::user()->avatar }}" alt="">
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid main">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
