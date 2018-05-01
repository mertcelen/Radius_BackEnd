@extends('layouts.app')

@section('content')
    <div class="ml-auto mr-auto">
        {{$logs->links()}}
    </div>
    <table class="table">
        <thead class="thead">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Action Type</th>
            <th scope="col">Message</th>
            <th scope="col">User</th>
            <th scope="col">Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($logs as $log)
            <tr>
                <th scope="row">{{$loop->iteration + (20 * $logs->currentPage() - 20)}}</th>
                <td>{{$log->process}}</td>
                <td>{{$log->message}}</td>
                <td>{{$log->user}}</td>
                <td>{{$log->created_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection