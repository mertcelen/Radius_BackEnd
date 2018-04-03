function updatePassword(){
  var old = $("#oldPassword");
  var updated = $("#newPassword");
  var update2 = $("#newPassword2");
  // $.ajax({
  //   url : "/api/user/password",
  //   data : {
  //     "old-password" : old.val(),
  //     "new-password" : updated.val(),
  //     "new-password_confirmation" : update2.val()
  //   },
  //   type : "POST",
  // }).done(function(response){
  //     console.log(response);
  // }).fail(function(){
  //     alert("error");
  // });
}

function retrieve(){
    $(".modal-title").html("Please Wait");
    $(".modal-body").html("Retrieving from instagram");
    $("#modalDialog").modal();
    var secret = $("#secret").html();
    $.post({
        url  : "/api/instagram/get",
        data : { "secret" : secret},
        success : function(data){
            modal("Images Updated",data);
        }
    });
}

function modal(title, json) {
    if (json.error) {
        $(".modal-title").html("Error!");
        $(".modal-body").html(json.error.message);
    } else {
        $(".modal-title").html(title);
        $(".modal-body").html(json.updated + " " + json.success.message);
    }
}

function magic(secret){
    var loading = $(".loading").html();
    $(".modal-body").html(loading);
    $("#modalDialog").modal();
    $.post({
        url  : "/api/magic",
        data : { secret : secret},
        success : function(data){
            $(".modal-title").html("Detection Requested");
            $(".modal-body").html("Cloth detection is requested, it is going to work background because it will take a while");
        }
    });
}

Dropzone.options.uploadPhoto = {
    paramName: "photo",
    thumbnail : null,
    previewsContainer : false,
    processing : function(){
        $(".dz-message").html("Uploading");
    },
    success : function(response){
        var avatarId = JSON.parse(response.xhr.response).id;
        $(".dz-message").html("Avatar updated.");
        $(".avatarPhoto").html("<img src='/avatar/" + avatarId + ".jpg'/>");
    }
};