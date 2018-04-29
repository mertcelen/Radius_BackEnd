function action(type) {
    $(".modal-title").html("Login the Radius");
    var data = $("." + type).html();
    $(".modal-body").html(data);
    $("#modalDialog").modal();
}

function loginUser() {
    var email = $("#loginEmail").val();
    var password = $("#loginPassword").val();
    var oldHtml = $(".modal-body").html();
        var loading = $(".loading").html();
        $(".modal-body").html(loading);
    if ($("#loginEmail").is(':empty') || $("#loginPassword").is(':empty')) {
        $(".loginError").html('Please fill all blanks.').removeClass('invisible');
    }
    $.post({
        url: "login",
        data: {
            "email": email,
            "password": password,
            "session": true
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            if (data.success) {
                location.reload();
            } else {
                $(".modal-body").html(oldHtml);
                $(".loginError").html(data.error.message).removeClass('invisible');
            }
        },
        error: function (request) {
            $(".modal-body").html(oldHtml);
            $(".loginError").html('').removeClass('invisible');
            for (var element in request.responseJSON.errors) {
                $(".loginError").append(request.responseJSON.errors[element] + "<br>");
            }
            ;
            $("#loginEmail").val(email);
            $("#loginName").val(name);
        }
    });
}

function registerUser() {
    var email = $("#registerEmail").val();
    var name = $("#registerName").val();
    var password = $("#registerPassword").val();
    var password2 = $("#registerPassword2").val();
    if($("#radio2").is(":checked")){
        var gender = 2;
    }else{
        var gender = 1;
    }
    var oldHtml = $(".modal-body").html();
    var loading = $(".loading").html();
    $(".modal-body").html(loading);
    $.post({
        url: "register",
        data: {
            "email": email,
            "password": password,
            "password-confirm": password2,
            "name": name,
            "gender" : gender
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            $(".modal-body").html("Registration email sent, please check " + email + " to complete registration.");
        },
        error: function (request) {
            $(".modal-body").html(oldHtml);
            $(".registerError").html('').removeClass('invisible');
            for (var element in request.responseJSON.errors) {
                $(".registerError").append(request.responseJSON.errors[element] + "<br>");
            }
            ;
            $("#registerEmail").val(email);
            $("#registerName").val(name);
        }
    });
}
