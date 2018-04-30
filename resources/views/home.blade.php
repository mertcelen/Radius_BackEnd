@extends('layouts.app')

@section('content')
    <div class="container">

        @isset($recommendations)
            <div class="photos">
                @foreach($recommendations as $recommendation)
                    <div class='photoWrapper'>
                        <a href="{{$recommendation["link"]}}">
                            <img src="/products/{{$recommendation["image"]}}.jpg"
                                 class='photo float-left'/>
                        </a>
                    </div>
                @endforeach
            </div>
        @endisset
    </div>
@endsection
