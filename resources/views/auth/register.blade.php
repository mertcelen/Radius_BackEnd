<div class="container invisible register">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="alert alert-danger registerError invisible" role="alert">
                </div>
                <div class="md-form">
                    <i class="fas fa-user prefix" style="color: #bc5100;"></i>
                    <input type="text" id="registerName" class="form-control" style="color: #bc5100;">
                    <label for="registerName" style="color: #bc5100;">Your Name</label>
                </div>
                <div class="md-form">
                    <i class="fas fa-at prefix" style="color: #bc5100;"></i>
                    <input id="registerEmail" type="email" class="form-control" required style="color: #bc5100;">
                    <label for="registerEmail" style="color: #bc5100;">Email Adress</label>
                </div>
                <div class="md-form">
                    <i class="fas fa-key prefix" style="color: #bc5100;"></i>
                    <input type="password" id="registerPassword" class="form-control" style="color: #bc5100;">
                    <label for="registerPassword" style="color: #bc5100;">Password</label>
                </div>
                <button class="btn btn-custom btn-block" onclick="registerUser()">Register</button><br>
                <button class="btn btn-custom btn-block" onclick="action('login')">Go Back</button><br>
            </div>
        </div>
    </div>
</div>
