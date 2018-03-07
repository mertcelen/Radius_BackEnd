function detect(imageId,type,index){
    var url = "http://localhost/api/test";
    console.log("#rbg" + index);
    $.post(url, {
          "imageId": imageId,
          "type" : type
    }, function(result){
        $("#label" + index).html(result.labels.toString());
        $("#colors" + index).css('backgroundColor',result.colors.toString());
    });
}