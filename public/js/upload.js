function uploadPhoto(){
  var progress = $("#progress");
  progress.html("%0");
  var file = $("#file");
  var formData = new FormData();
  var msg = $(".msg");
  msg.html("Uploading now");
  console.log(file);
  formData.append('photo',file.files[0]);
  $.ajax({
    url: '/photos/upload',
    data : formData,
    type : 'POST',
    xhr : function(){
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = evt.loaded / evt.total;
          progress.html("%" + percentComplete);
          progress.attr('aria-valuenow',percentComplete);
        }
      },false);
      return xhr;
    },
    success : function(data){
      msg.html(data);
    }
  });
}
