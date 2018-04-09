@extends('layouts.app')

@section('content')
    <script src="{{asset('js/home.js')}}"></script>
        <div class="container">
            <center>
                <div class="card float-left" style="width: 90%">
                    <div class="card-body">
                        <div class="alert alert-danger verifyError" role="alert" hidden>
                        </div>
                        <h3 class="card-title" style="color:black">Please verify your email to continue</h3>
                        <div class="md-form">
                            <input type="number" id="verifyCode" class="form-control" style="color: #bc5100;">
                            <label for="verifyCode" style="color: #bc5100;">Verification Code</label>
                        </div>
                        <button class="btn btn-custom btn-block" onclick="verifyEmail()">Verify Email</button>
                        <br>
                    </div>
                </div>
            </center>
        </div>
@endsection
