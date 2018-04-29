@extends('layouts.app')

@section('content')
    <script src="/js/style.js"></script>
    <script>
        var secret = "{{Auth::user()->secret}}";
    </script>
    <style>
        .selected {
            border: 5px solid white;
        }
        .photoWrapper img{
            width: 225px;
            height: 225px;
        }
    </style>
    <h1 style="color:white">Please select at least 5 photos that you like.</h1>
    <div class="photos">
        @foreach($styles as $style)
            <div class='photoWrapper'>
                <img id="{{$style->name}}" src="/styles/{{Auth::user()->gender}}/{{$style->name}}.jpg" class='photo float-left' onclick="select('{{$style->name}}')"/>
            </div>
        @endforeach
        <button class="btn btn-custom btn-block" onclick="update()">Save my style</button>
    </div>
    @include('layouts.modal')
    @include('layouts.loading')
@endsection