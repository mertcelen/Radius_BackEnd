@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="/css/settings.css">
<script type="text/javascript" src="/js/settings.js"></script>

<div class="card float-left" style="width: 36rem;">
  <div class="card-body">
    <h3 class="card-title">Change Password</h3>
    <input type="password" id="oldPassword" class="form-control input" placeholder="Old Password" required>
    <input type="password" id="newPassword" class="form-control input" placeholder="New Password" required>
    <input type="password" id="newPassword2" class="form-control input" placeholder="Confirm New Password" required>
    <button type="button" name="button" class="btn btn-primary btn-black" onclick="updatePassword()">Change Password</button>
  </div>
</div>
@if($instagram == true)
<div class="card float-left" style="width: 36rem;">
    <div class="card-body">
        <h3 class="card-title">Retrieve from Instagram</h3>
        <button class="btn btn-primary btn-black">Retrieve</button>
    </div>
</div>
@endif
@endsection
