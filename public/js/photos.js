var enabled = false;
Dropzone.options.uploadPhoto = {
    paramName: "photo",
    thumbnail : null,
    previewsContainer : false,
    processing : function(){
      $(".dz-message").html("Uploading");
    },
    success : function(response){
      var imageId = JSON.parse(response.xhr.response).imageId;
      $(".dz-message").html("Photo uploaded.");
      $(".photos").prepend("<div class='photoWrapper'><img src='/thumb/" + imageId + ".jpg' class='photo float-left' /><div class='removeButton' onclick='remove('" + imageId + "')'></div></div>")
    }
};
Dropzone.prototype.defaultOptions.dictDefaultMessage = "Drop files or click here to upload"
function remove(imageId){
    if(enabled === false){
        return;
    }
    // var loading = $(".loading").html();
    // $(".modal-body").html(loading);
    // $("#modalDialog").modal();
    $.post({
        url : "/photos/remove",
        data : {
            "imageId" : imageId
        },
        success : function(json){
            if (json.error) {
            } else {
                $("#" + imageId).fadeOut();
                toggle();
            }
        }
    });
}

function toggle(){
    enabled = !enabled;
    $( ".photoWrapper" ).each(function() {
        $( this ).toggleClass( "blur" );
    });
}