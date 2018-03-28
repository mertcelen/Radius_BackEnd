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
      $(".photos").prepend("<div class='photoWrapper'><img src='/thumb/" + imageId + ".jpg' class='photo float-left' /><div class='removeButton' onclick='remove('" + imageId + "')'>X</div></div>")
    }
};
Dropzone.prototype.defaultOptions.dictDefaultMessage = "Drop files or click here to upload"
function remove(imageId){
    if(enabled === false){
        return;
    }
  alert('clicked');
}

function toggle(){
    enabled = !enabled;
    $( ".photoWrapper" ).each(function() {
        $( this ).toggleClass( "blur" );
    });
}