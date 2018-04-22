@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">Upper
            </button>
            <div class="dropdown-menu">
                @foreach($part1 as $item)
                    <a class="dropdown-item" href="#" style="background-color: {{$item[0]}}">{{$item[1]}}</a>
                @endforeach
            </div>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">Lower
            </button>
            <div class="dropdown-menu">
                @foreach($part2 as $item)
                    <a class="dropdown-item" href="#" style="background-color: {{$item[0]}}">{{$item[1]}}</a>
                @endforeach
            </div>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">Full Body
            </button>
            <div class="dropdown-menu">
                @foreach($part3 as $item)
                    <a class="dropdown-item" href="#" style="background-color: {{$item[0]}}">{{$item[1]}}</a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
