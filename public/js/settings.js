function updatePassword(secret){
  var old = $("#oldPassword").val();
  var updated = $("#newPassword").val();
  var update2 = $("#newPassword2").val();
  if(!old || !updated || !update2){
      $(".passwordError").html('Please fill all blanks.').removeAttr('hidden');
      return;
  }
    $(".modal-title").html("Please Wait");
    var loading = $(".loading").html();
    $(".modal-body").html(loading);
    $("#modalDialog").modal();
  $.post({
    url : "/api/user/password",
    data : {
      "old-password" : old,
      "new-password" : updated,
      "new-password2" : update2,
      "secret" : secret
    },
      success : function(data){
          if(data.success){
              $(".modal-title").html("Updated");
              $(".modal-body").html("Password successfully updated.");
          }else{
              $("#modalDialog").modal('toggle');
              $(".passwordError").html(data.error.message).removeAttr('hidden');
          }

      }
  });
}
Dropzone.prototype.defaultOptions.dictDefaultMessage = "Drop files or click here to upload";

function retrieve(){
    $(".modal-title").html("Please Wait");
    var loading = $(".loading").html();
    $(".modal-body").html(loading);
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
