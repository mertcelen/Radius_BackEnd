@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                @if($status == 0)
                    <div class="alert alert-danger" role="alert">
                        You haven't confirmed your email. Please check your email address provided.
                    </div>
                @endif
                <div class="panel-heading">Dashboard</div>
                    @foreach($images as $image)
                        <div id="wrapper">
                            <img src="/images/{{$image->imageId}}.{{$image->type}}" class="instagramImage rounded float-left" style="width: 170px;height: 170px;padding: 10px; border: 1px solid black">
                        </div>
                    @endforeach
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection