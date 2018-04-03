<div class="container invisible login">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="alert alert-danger loginError invisible" role="alert">
                </div>
                <div class="md-form">
                    <i class="fa fa-envelope prefix"></i>
                    <input id="loginEmail" type="email" class="form-control" required>
                    <label for="loginEmail">Email Adress:</label>
                </div>
                <div class="md-form">
                    <i class="fa fa-lock prefix"></i>
                    <input type="password" id="loginPassword" class="form-control" required>
                    <label for="loginPassword">Type your password</label>
                </div>
                <br>
                <button class="btn btn-primary btn-block" onclick="loginUser()">Login</button>
                <br>
                <button class="btn btn-primary btn-block" onclick="action('register')">Register</button>
                <br>
                <button class="btn btn-primary btn-block" onclick="window.location.href = 'login/instagram'">Login with
                    Instagram
                </button>

            </div>
        </div>
    </div>
</div>