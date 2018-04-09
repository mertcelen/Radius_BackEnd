<div class="container login" hidden>
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="alert alert-danger loginError invisible" role="alert" style="background-color:#bc5100;color:white">
                </div>
                <div class="md-form">
                    <i class="fas fa-at prefix" style="color: #bc5100;"></i>
                    <input id="loginEmail" type="email" class="form-control" required style="color: #bc5100;">
                    <label for="loginEmail" style="color: #bc5100;">Email Adress:</label>
                </div>
                <div class="md-form">
                  <i class="fas fa-key prefix" style="color: #bc5100;"></i>
                    <input type="password" id="loginPassword" class="form-control" required style="color: #bc5100;">
                    <label for="loginPassword" style="color: #bc5100;">Type your password</label>
                </div>
                <br>
                <button class="btn btn-custom btn-block" onclick="loginUser()">Login</button>
                <br>
                <button class="btn btn-custom btn-block" onclick="action('register')">Register</button>
                <br>
                <button class="btn btn-custom btn-block" onclick="window.location.href = 'login/instagram'">Login with
                    Instagram
                </button>

            </div>
        </div>
    </div>
</div>
