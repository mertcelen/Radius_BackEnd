@extends('layouts.app')

@section('content')
    <div class="ml-auto mr-auto">
        {{$relations->links()}}
    </div>
    <table class="table">
        <thead class="thead">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Follower</th>
            <th scope="col">Following</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($relations as $relation)
            <tr>
                <th scope="row">{{$loop->iteration + (20 * $relations->currentPage() - 20)}}</th>
                <td>{{$relation->follower}}</td>
                <td>{{$relation->following}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection