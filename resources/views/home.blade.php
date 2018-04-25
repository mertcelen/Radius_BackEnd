@extends('layouts.app')

@section('content')
    <div class="container">

        @isset($recommendations)
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Color</th>
                </tr>
                </thead>
                <tbody>
            @foreach($recommendations as $recommendation)
                <tr>
                    <th scope="row">{{$loop->iteration}}</th>
                    <td>{{$recommendation["label"]}}</td>
                    <td>{{$recommendation["color"]}}</td>
                </tr>
            @endforeach
                </tbody>
            </table>
        @endisset
    </div>
@endsection
