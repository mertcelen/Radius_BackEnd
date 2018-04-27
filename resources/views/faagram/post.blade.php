@extends('layouts.app')

@section('content')
    <div class="ml-auto mr-auto">
        {{$posts->links()}}
    </div>
    <table class="table">
        <thead class="thead">
        <tr>
            <th scope="col">#</th>
            <th scope="col">User Id</th>
            <th scope="col">Image Label</th>
            <th scope="col">Image Color</th>
            <th scope="col">Likes</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($posts as $post)
            <tr>
                <th scope="row">{{$loop->iteration + (20 * $posts->currentPage() - 20)}}</th>
                <td>{{$post->userId}}</td>
                <td>{{$post->label}}</td>
                <td>{{$post->color}}</td>
                <td>{{$post->like_count}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection