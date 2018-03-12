@extends('layouts.app')

@section('content')
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        function updateStatus(userId,newStatus){
            $('.status').text('Updating ' + userId + '\' status');
            $.post("user/status", {id: userId, status : newStatus,_token: CSRF_TOKEN}, function(result){
                if(result == 0){
                    $('.status').text('An error occurred');
                    setTimeout(function(){ $('.status').text('User status updated'); }, 3000);
                }else{
                    $('.status').text('User status updated');
                }
            });
        }
    </script>
    <div class="alert alert-dark" role="alert">
        Status : <span class="status">Ready for commands</span>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">User ID</th>
            <th scope="col">Name</th>
            <th scope="col">Instagram</th>
            <th scope="col">Status</th>
            <th scope="col">Image Recognition</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users["users"] as $user)
            <tr>
                <th scope="row">{{$loop->iteration}}</th>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->isInstagram}}</td>
                {{--<td><button class="btn btn-secondary">Run</button></td>--}}
                <td>
                    <div class="btn-group">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @switch($user->status)
                                @case(0)
                                    Unconfirmed
                                @break
                                @case(1)
                                    Confirmed
                                @break
                                @case(2)
                                    Banned
                                @break
                                @case(3)
                                    Administrator
                                @break
                                @endswitch
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onclick="updateStatus({{$user->id}},0)">Unconfirmed</a>
                            <a class="dropdown-item" href="#" onclick="updateStatus({{$user->id}},1)">Confirmed</a>
                            <a class="dropdown-item" href="#" onclick="updateStatus({{$user->id}},2)">Banned</a>
                            <a class="dropdown-item" href="#" onclick="updateStatus({{$user->id}},3)">Admin</a>
                        </div>
                    </div>
                </td>
                <td>
                    <button>Run now</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection