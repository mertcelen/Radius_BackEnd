<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#bc5100"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Radius Fashion</title>
    <link rel="manifest" href="manifest.json">
    <link rel="shortcut icon" href="favicon.ico">
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js"
            integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl"
            crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/css/mdb.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/css/styles.css">
    {{--<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">--}}
</head>
<body>
<div>
    @auth
        @if(!Auth::user()->isVerified())
            <link rel="manifest" href="/manifest.json"/>
            <header>
                <nav class="navbar navbar-expand-lg navbar-custom">
                    <img src="logo_radius.png" alt="" width="35px" height="35px">
                    {{--<a class="navbar-brand"--}}
                       {{--href="/"><strong>Radius</strong></a>--}}
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars" style="color:white"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="/home">Recommendations<span class="sr-only"></span></a>
                            </li>
                            @auth
                                <li class="nav-item">
                                    <a class="nav-link" href="/photos">My Photos <span class="sr-only"></span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/settings">Settings<span class="sr-only"></span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/privacy_policy.html" target="_blank">Privacy Policy<span class="sr-only"></span></a>
                                </li>
                                @if(Auth::user()->isAdmin())
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Admin
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="/admin/users">User Configuration</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Products
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="/product/list">Product List</a>
                                            <a class="dropdown-item" href="/product/add">Add Product</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Faagram
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="/faagram/users">User List</a>
                                            <a class="dropdown-item" href="/faagram/posts">Post List</a>
                                            <a class="dropdown-item" href="/faagram/relations">Relation List</a>
                                            <a class="dropdown-item disabled" href="/faagram/likes" disabled>Like List</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            System
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="/phpmyadmin" target="_blank">phpMyAdmin</a>
                                            <a class="dropdown-item" href="https://mertcelen.github.io/PSA_ApiDoc" target="_blank">ApiDoc</a>
                                            <a class="dropdown-item" href="/admin/logs">Logs</a>
                                            <a class="dropdown-item" href="/admin/config">Configuration</a>
                                        </div>
                                    </li>
                                @endif
                            @endauth
                        </ul>
                        <ul class="navbar-nav navbar-right">
                            @auth
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                <li class="nav-item">
                                    <a class="nav-link" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        <img id="userAvatar" src="/avatar/{{Auth::user()->avatar}}.jpg" width="30px" height="30px"/>
                                        <b>Logout from {{Auth::user()->name}}</b>
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </nav>
            </header>
        @else
            <center>
            <ul class="navbar-nav navbar-right">
                @auth
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    <li class="nav-item">
                        <a class="nav-link" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            <img src="/avatar/{{Auth::user()->avatar}}.jpg" width="30px" height="30px"/>
                            <b>Logout from {{Auth::user()->name}}</b>
                        </a>
                    </li>
                @endauth
            </ul>
            </center>
        @endif
    @endauth

    <div class="container px-2 py-2">
        @yield('content')
    </div>
</div>
@include('layouts.loading')
</body>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/js/mdb.min.js"></script>
</html>
