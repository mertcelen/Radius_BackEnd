<style>
    @-moz-keyframes ripple {
        5%, 100% {
            opacity: 0;
        }
        5% {
            opacity: 1;
        }
    }
    @-webkit-keyframes ripple {
        5%, 100% {
            opacity: 0;
        }
        5% {
            opacity: 1;
        }
    }
    @keyframes ripple {
        5%, 100% {
            opacity: 0;
        }
        5% {
            opacity: 1;
        }
    }
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    input[type="radio"] {
        display: none;
    }

    input[type="radio"] + label {
        position: relative;
        cursor: pointer;
        margin: 30px;
        padding-left: 28px;
    }
    input[type="radio"] + label:before, input[type="radio"] + label:after {
        content: "";
        position: absolute;
        border-radius: 50%;
        -moz-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        -webkit-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }
    input[type="radio"] + label:before {
        top: 0;
        left: 0;
        width: 18px;
        height: 18px;
        background: #bc5100;
        -moz-box-shadow: inset 0 0 0 18px #fff;
        -webkit-box-shadow: inset 0 0 0 18px #fff;
        box-shadow: inset 0 0 0 18px #fff;
    }
    input[type="radio"] + label:after {
        top: 49%;
        left: 9px;
        width: 54px;
        height: 54px;
        opacity: 0;
        background: rgba(255, 255, 255, 0.3);
        -moz-transform: translate(-50%, -50%) scale(0);
        -ms-transform: translate(-50%, -50%) scale(0);
        -webkit-transform: translate(-50%, -50%) scale(0);
        transform: translate(-50%, -50%) scale(0);
    }

    input[type="radio"]:checked + label:before {
        -moz-box-shadow: inset 0 0 0 4px #fff;
        -webkit-box-shadow: inset 0 0 0 4px #fff;
        box-shadow: inset 0 0 0 4px #fff;
    }
    input[type="radio"]:checked + label:after {
        -moz-transform: translate(-50%, -50%) scale(1);
        -ms-transform: translate(-50%, -50%) scale(1);
        -webkit-transform: translate(-50%, -50%) scale(1);
        transform: translate(-50%, -50%) scale(1);
        -moz-animation: ripple 1s none;
        -webkit-animation: ripple 1s none;
        animation: ripple 1s none;
    }

</style>
<div class="container register" hidden>
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

                <div>
                    <input type="radio" id="radio1" name="radio-category" checked/ >
                    <label for="radio1">Male</label>

                    <input type="radio" id="radio2" name="radio-category" />
                    <label for="radio2">Female</label>
                </div>

                <button class="btn btn-custom btn-block" onclick="registerUser()">Register</button>
                <br>
                <button class="btn btn-custom btn-block" onclick="action('login')">Go Back</button>
                <br>
            </div>
        </div>
    </div>
</div>
