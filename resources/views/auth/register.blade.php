<div class="container invisible register">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="alert alert-danger registerError invisible" role="alert">
                </div>
                <div class="md-form">
                    <i class="fa fa-user prefix"></i>
                    <input type="text" id="registerName" class="form-control">
                    <label for="registerName">Your Name</label>
                </div>
                <div class="md-form">
                    <i class="fa fa-envelope prefix"></i>
                    <input id="registerEmail" type="email" class="form-control" required>
                    <label for="registerEmail">Email Adress</label>
                </div>
                <div class="md-form">
                    <i class="fa fa-lock prefix"></i>
                    <input type="password" id="registerPassword" class="form-control">
                    <label for="registerPassword">Password</label>
                </div>
                <button class="btn btn-primary btn-block" onclick="registerUser()">Register</button><br>
                <button class="btn btn-primary btn-block" onclick="action('login')">Go Back</button><br>
            </div>
        </div>
    </div>
</div>
