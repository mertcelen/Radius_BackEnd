function updatePassword() {
    var old = $("#oldPassword").val();
    var updated = $("#newPassword").val();
    var update2 = $("#newPassword2").val();
    if (!old || !updated || !update2) {
        $(".passwordError").html('Please fill all blanks.').removeAttr('hidden');
        return;
    }
    $(".modal-title").html("Please Wait");
    var loading = $(".loading").html();
    $(".modal-body").html(loading);
    $("#modalDialog").modal();
    $.post({
        url: "/api/user/password",
        data: {
            "old-password": old,
            "new-password": updated,
            "new-password2": update2,
            "secret": secret
        },
        success: function (data) {
            if (data.success) {
                $(".modal-title").html("Updated");
                $(".modal-body").html("Password successfully updated.");
            } else {
                $("#modalDialog").modal('toggle');
                $(".passwordError").html(data.error.message).removeAttr('hidden');
            }

        }
    });
}

Dropzone.prototype.defaultOptions.dictDefaultMessage = "Click here to upload";

function retrieve() {
    $(".modal-title").html("Please Wait");
    let loading = $(".loading").html();
    $(".modal-body").html(loading);
    $("#modalDialog").modal();
    $.post({
        url: "/api/instagram/get",
        data: {"secret": secret},
        success: function (data) {
            window.location.href = "/photos";
        }
    });
}

function modal(title, json) {
    if (json.error) {
        $(".modal-title").html("Error!");
        $(".modal-body").html(json.error.message);
    } else {
        $(".modal-title").html(title);
        $(".modal-body").html(json.success.message);
    }
}

function magic() {
    var loading = $(".loading").html();
    $(".modal-body").html(loading);
    $("#modalDialog").modal();
    $.post({
        url: "/api/magic",
        data: {secret: secret},
        success: function (data) {
            $(".modal-title").html("Detection Requested");
            $(".modal-body").html("Cloth detection is requested, it is going to work background because it will take a while");
        }
    });
}

Dropzone.options.uploadPhoto = {
    paramName: "image",
    thumbnail: null,
    previewsContainer: false,
    processing: function () {
        $(".dz-message").html("Uploading");
    },
    success: function (response) {
        var avatarId = JSON.parse(response.xhr.response).avatarId;
        $(".dz-message").html("Avatar updated.");
        setTimeout(function () {
            $(".dz-message").html("Click here to upload.");
        }, 1000);
        $("#userAvatar").attr('src', '/avatar/' + avatarId + '.jpg');
    }
};

function savePreferences(flag) {
    $(".modal-title").html("Please Wait");
    let loading = $(".loading").html();
    $(".modal-body").html(loading);
    $("#modalDialog").modal();
    if (flag) {
        $.post({
            url: "/api/user/values",
            data: {
                "secret": secret,
                "first": $("#1").val().toString(),
                "second": $("#2").val().toString(),
                "third": $("#3").val().toString()
            },
            success: function (data) {
                if (data.success) {
                    $(".modal-title").html("Updated");
                    $(".modal-body").html(data.success.message);
                } else {
                    $(".modal-title").html("Error");
                    $(".modal-body").html(data.error.message);
                }
            }
        });
    } else {
        $.post({
            url: "/api/user/values",
            data: {
                "secret": secret,
                "first": $("#uploadRange").val().toString(),
                "second": $("#styleRange").val().toString()
            },
            success: function (data) {
                if (data.success) {
                    $(".modal-title").html("Updated");
                    $(".modal-body").html(data.success.message);
                } else {
                    $(".modal-title").html("Error");
                    $(".modal-body").html(data.error.message);
                }
            }
        });
    }

}

$(function () {
    $(document).on('input change', '.instagramSliders input[type=range]', function () {
        var current = parseInt($(this).attr('id'));
        var next = (current == 3 ? 1 : current + 1);
        var before = (current == 1 ? 3 : current - 1);
        $("#" + next.toString()).val(50 - $(this).val() / 2);
        $("#" + before.toString()).val(50 - $(this).val() / 2);
    });
    $(document).on('input change', '.standardSliders input[type=range]', function () {
        var first = $("#uploadRange");
        var second = $("#styleRange");
        if ($(this).attr('id') == first.attr('id')) {
            second.val(100 - $(this).val());
        } else {
            first.val(100 - $(this).val());
        }

    });
});