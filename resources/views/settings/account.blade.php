@extends('layouts.app')

@section('content')
    <script>
        var secret = "{{Auth::user()->secret}}";
    </script>
    <script type="text/javascript" src="/js/libraries/dropzone.js"></script>
    <link rel="stylesheet" href="/css/settings.css">
    <script type="text/javascript" src="/js/settings.js"></script>
    @if(Auth::user()->type == 1)
        <div class="card float-left" style="width: 18rem;">
            <div class="card-body">
                <h3 class="card-title">Change Password</h3>
                <div class="alert alert-danger passwordError" role="alert" style="background-color:#2196F3;color:white"
                     hidden>
                </div>
                <input type="password" id="oldPassword" class="form-control input" placeholder="Old Password" required>
                <input type="password" id="newPassword" class="form-control input" placeholder="New Password" required>
                <input type="password" id="newPassword2" class="form-control input" placeholder="Confirm New Password"
                       required>
                <button type="button" name="button" class="btn btn-custom btn-block"
                        onclick="updatePassword('{{Auth::user()->secret}}')">Change
                    Password
                </button>
            </div>
        </div>
    @else
        <div class="card float-left" style="width: 22rem;">
            <div class="card-body">
                <h3 class="card-title">Instagram Update</h3>
                <h5 style="text-align: justify;">Click the button below to update our system with your last 20 photos
                    from Instagram.</h5>
                <button class="btn btn-custom btn-block" onclick="retrieve()">Update</button>
            </div>
        </div>
    @endif
    <div class="card float-left" style="width: 22rem;">
        <div class="card-body">
            <h3 class="card-title">Change Profile Photo</h3>
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