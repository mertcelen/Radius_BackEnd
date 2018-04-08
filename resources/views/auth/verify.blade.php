<div class="container verify" hidden>
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="alert alert-danger verifyError invisible" role="alert">
                </div>
                <p>Verification code sent to <span id="verificationEmail"></span></p>
                <div class="md-form">
                    <i class="fas fa-key prefix" style="color: #bc5100;"></i>
                    <input type="number" id="verifyCode" class="form-control" style="color: #bc5100;">
                    <label for="verifyCode" style="color: #bc5100;">Verification Code</label>
                </div>
                <button class="btn btn-custom btn-block" onclick="verifyEmail()">Verify Email</button><br>
            </div>
        </div>
    </div>
</div>