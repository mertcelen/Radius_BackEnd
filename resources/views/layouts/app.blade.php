@if(isset($status) && $status == 2)
<?php die('YOU ARE BANNED'); ?>
@endif
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="css/mdb.min.css">--}}
    <link rel="stylesheet" href="css/app.css">
    <script src="/js/app.js"></script>
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Personal Shopping Assistant') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a class="navbar-brand" href="/">{{ config('app.name', 'Personal Shopping Assistant') }}</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                        </li>
                        @auth
                            @if(isset($status) && $status == 3)
                                <li class="nav-item">
                                    <a class="nav-link" href="admin">Admin Panel <span class="sr-only">(current)</span></a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        @auth
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{Auth::user()->name}}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="logout" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Logout</a>
                            </div>
                        </div>
                        @endauth
                    </ul>
                </div>
            </nav>
        </header>
        <div class="container">
            @yield('content')
        </div>
    </div>

</body>
</html>
