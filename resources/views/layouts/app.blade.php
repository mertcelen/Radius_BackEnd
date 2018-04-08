<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#bc5100" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Radius</title>
    <link rel="manifest" href="manifest.json">
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/css/mdb.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div>
    @auth
    <link rel="manifest" href="/manifest.json" />
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
      var OneSignal = window.OneSignal || [];
      OneSignal.push(function() {
        OneSignal.init({
          appId: "fc2ccdef-7148-462d-9e58-238a96c100e0",
        });
      });
    </script>
        <header>
            <nav class="navbar navbar-expand-lg navbar-custom">

                <a class="navbar-brand"
                   href="/"><strong>Radius</strong></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars" style="color:white"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Home <span class="sr-only"></span></a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="photos">My Photos <span class="sr-only"></span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="settings">Settings<span class="sr-only"></span></a>
                            </li>
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="admin">Admin Panel<span class="sr-only"></span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="http://sql.psactis.me" target="_blank">phpMyAdmin<span class="sr-only"></span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="http://api.psactis.me" target="_blank">ApiDoc<span class="sr-only"></span></a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <ul class="navbar-nav navbar-right">
                        @auth
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
                </div>
            </nav>
        </header>
    @endauth

    <div class="container px-2 py-2">
        @yield('content')
    </div>
</div>
@include('layouts.loading')
</body>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/js/mdb.min.js"></script>
</html>
