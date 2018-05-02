@extends('layouts.app')

@section('content')
    @isset($favorites)
        @foreach($favorites as $favorite)
            <div class='photoWrapper'>
                <img src="/products/{{$recommendation["image"]}}.jpg"
                     class='photo float-left'
                     onclick="preview('{{$recommendation["image"]}}'
                             ,'{{$recommendation["brand"]}}'
                             ,'{{$recommendation["link"]}}',
                     {{$recommendation["source"]}})"/>
            </div>
        @endforeach
    @endisset
    <br><br>
    <center>
        <h1 style="color: white;">Under Construction</h1>
    </center>
@endsection