@extends('layouts.app')

@section('content')
    <script>
        var secret = "{{Auth::user()->secret}}";
    </script>
    <script type="text/javascript" src="/js/libraries/dropzone.js"></script>
    <link rel="stylesheet" href="/css/settings.css">
    <script type="text/javascript" src="/js/settings.js"></script>
    <span style="display: none" id="secret">{{$secret}}</span>
    @if(Auth::user()->type == 1)
        <div class="card float-left" style="width: 18rem;">
            <div class="card-body">
                <h3 class="card-title">Change Password</h3>
                <div class="alert alert-danger passwordError" role="alert" style="background-color:#bc5100;color:white"
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
        <div class="card float-left" style="width: 18rem;">
            <div class="card-body">
                <h3 class="card-title">Retrieve from Instagram</h3>
                <button class="btn btn-custom btn-block" onclick="retrieve()">Retrieve</button>
            </div>
        </div>
    @endif
    <div class="card float-left sliders" style="width: 18rem;">
        <div class="card-body">
            <h3 class="card-title">Recommendation Preferences</h3>
            <label for="postRange" style="color: #bc5100;">Weight of your posts</label>
            <input type="range" min="0" max="100" value="{{$first}}" class="slider btn-block" id="1">
            <label for="likeRange" style="color: #bc5100;">Weight of your likes</label>
            <input type="range" min="0" max="100" value="{{$second}}" class="slider btn-block" id="2">
            <label for="followingRange" style="color: #bc5100;">Weight of people who you follow</label>
            <input type="range" min="0" max="100" value="{{$third}}" class="slider btn-block" id="3">
            <button class="btn btn-custom btn-block" onclick="savePreferences()">Save</button>
        </div>
    </div>
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
