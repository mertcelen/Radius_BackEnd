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
        <div class="card float-left sliders standardSliders" style="width: 22rem;">
            <div class="card-body">
                <h3 class="card-title">Preferences</h3>
                <label for="uploadRange" style="color: #2196F3;">Weight of your uploads</label>
                <input type="range" min="0" max="100" value="{{$first}}" class="slider btn-block" id="uploadRange">
                <label for="styleRange" style="color: #2196F3;">Weight of your styles</label>
                <input type="range" min="0" max="100" value="{{$second}}" class="slider btn-block" id="styleRange">
                <br>
                <button class="btn btn-custom btn-block" onclick="savePreferences(false)">Save</button>
            </div>
        </div>
    @else
        <div class="card float-left sliders instagramSliders" style="width: 22rem;">
            <div class="card-body">
                <h3 class="card-title">Preferences</h3>
                <h5 style="text-align: justify;">Change the sliders below to customize your recommendations
                    experience.</h5>
                <label for="postRange" style="color: #2196F3;">Weight of your posts</label>
                <input type="range" min="0" max="100" value="{{$first}}" class="slider btn-block" id="1">
                <label for="likeRange" style="color: #2196F3;">Weight of your likes</label>
                <input type="range" min="0" max="100" value="{{$second}}" class="slider btn-block" id="2">
                <label for="followingRange" style="color: #2196F3;">Weight of people who you follow</label>
                <input type="range" min="0" max="100" value="{{$third}}" class="slider btn-block" id="3"><br>
                <button class="btn btn-custom btn-block" onclick="savePreferences(true)">Save</button>
            </div>
        </div>
    @endif
    <div class="card float-left" style="width: 22rem;">
        <div class="card-body">
            <h3 class="card-title">Change Style</h3>
            <h5>Click the button below to change your style.</h5><br>
            <button class="btn btn-custom btn-block" onclick="window.location.href = '/setup/reset'">Update your style</button>
        </div>
    </div>
    <div class="card float-left" style="width: 22rem;">
        <div class="card-body">
            <h3 class="card-title">Change Gender</h3>
            <h5>Click the gender you want to change.</h5><br>
            <center>
                <div style="margin-left:55px;margin-right:55px;color:#2196F3" onclick="gender(1)"
                     class="float-left genderClass"><i id="male" class="fas fa-male" style="font-size: 90px"></i><br>Male</div>
                <div style="margin-left:55px;margin-right:55px;color:pink" onclick="gender(2)"
                     class="float-left genderClass"><i class="fas fa-female" style="font-size: 90px"></i><br>Female</div>
            </center>
        </div>
    </div>
    @include('layouts.modal')
@endsection
