var enabled = false;
var currentImages = [];
Dropzone.options.uploadPhoto = {
    paramName: "image",
    thumbnail: null,
    previewsContainer: false,
    processing: function () {
        $(".dz-message").html("Uploading");
    },
    success: function (response) {
        var imageId = JSON.parse(response.xhr.response).imageId;
        $(".dz-message").html("Photo uploaded.");
        setTimeout(function(){
            $(".dz-message").html("Drop files here or click to upload.");
        },1000);
        var removeFunction = "preview(\'" +  imageId + "\')";
        $(".photos").prepend("<div class='photoWrapper'><img src='/thumb/" +
            imageId + ".jpg' class='photo float-left' onclick=\"" + removeFunction + "\"/></div>")
    }
};
Dropzone.prototype.defaultOptions.dictDefaultMessage = "Drop files or click here to upload.";

function preview(imageId) {
    var imageUrl = "/thumb/" + imageId + ".jpg";
    $("#bigImage").attr('src', imageUrl);
    $("#removeButton").attr('onclick', "remove('" + imageId + "')")
    $("#previewImage").modal();
}

function remove(imageId) {
    $.ajax({
        type : "post",
        url: "/api/image/remove",
        data: {
            "imageId": imageId,
            "secret" : secret
        },
        success: function (json) {
            if (json.error) {
            } else {
                $("#previewImage").modal('hide');
                $("#" + imageId).fadeOut();
                update();
            }
        }
    });
}
function update(){
    $.get({
        url: "/api/image",
        data: {
            "secret": secret
        },
        success: function (json) {
            if(json.success && json.images !== currentImages || currentImages == null){
                currentImages = json.images;
                $(".photos").html("");
                $.each(currentImages,function(key,value){
                    value = value.imageId;
                    var removeFunction = "preview(\'" +  value + "\')";
                    $(".photos").prepend("<div class='photoWrapper'><img src='/thumb/" +
                        value + ".jpg' class='photo float-left' onclick=\"" + removeFunction + "\"/></div>")
                });
            }
        }
    });
}

setInterval(function(){
    update();
},5000);
