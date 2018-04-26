var enabled = false;
Dropzone.options.uploadPhoto = {
    paramName: "photo",
    thumbnail: null,
    previewsContainer: false,
    processing: function () {
        $(".dz-message").html("Uploading");
    },
    success: function (response) {
        var imageId = JSON.parse(response.xhr.response).imageId;
        $(".dz-message").html("Photo uploaded.");
        setTimeout(function(){
            $(".dz-message").html("Drop files or click here to upload.");
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
    $.post({
        url: "/photos/remove",
        data: {
            "imageId": imageId
        },
        success: function (json) {
            if (json.error) {
            } else {
                $("#previewImage").modal('hide');
                $("#" + imageId).fadeOut();
            }
        }
    });
}