@extends('layouts.app')

@section('content')
    <div class="ml-auto mr-auto">
        {{$users->links()}}
    </div>
    <table class="table">
        <thead class="thead">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Follower</th>
            <th scope="col">Following</th>
            <th scope="col">Posts</th>
            <th scope="col">Likes</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <th scope="row">{{$loop->iteration + (20 * $users->currentPage() - 20)}}</th>
                <td>{{$user->name}}</td>
                <td>{{$user->follower}}</td>
                <td>{{$user->following}}</td>
                <td>{{$user->post_count}}</td>
                <td>{{$user->likes}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection