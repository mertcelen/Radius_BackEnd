@extends('layouts.app')

@section('content')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/libraries/dropzone.js"></script>
    <link rel="stylesheet" href="/css/settings.css">
    <script type="text/javascript" src="/js/settings.js"></script>
    <span style="display: none" id="secret">{{$secret}}</span>
    @if($instagram == false)
        <div class="card float-left" style="width: 18rem;">
            <div class="card-body">
                <h3 class="card-title">Change Password</h3>
                <div class="alert alert-danger passwordError" role="alert" style="background-color:#bc5100;color:white" hidden>
                </div>
                <input type="password" id="oldPassword" class="form-control input" placeholder="Old Password" required>
                <input type="password" id="newPassword" class="form-control input" placeholder="New Password" required>
                <input type="password" id="newPassword2" class="form-control input" placeholder="Confirm New Password"
                       required>
                <button type="button" name="button" class="btn btn-custom btn-block" onclick="updatePassword('{{Auth::user()->secret}}')">Change
                    Password
                </button>
            </div>
        </div>
    @else
        <div class="card float-left" style="width: 18rem;">
            <div class="card-body">
                <h3 class="card-title">Retrieve from Instagram</h3>
                <button class="btn btn-custom btn-block" onclick="retrieve()">Retrieve</button>
            </div>
        </div>
    @endif
    <div class="card float-left" style="width: 18rem;">
        <div class="card-body">
            <h3 class="card-title">Profile Photo</h3>
            <form id="uploadPhoto" action="/user/avatar" class="dropzone btn btn-custom">
                <div class="fallback">
                    <input name="photo" type="file"/>
                </div>
            </form>
            <div class="avatarPhoto"></div>
        </div>
    </div>
    @include('layouts.modal')
@endsection
