function detect(imageId,type,index){
    $.post('/api/test', {
          "imageId": imageId,
          "type" : type
    }, function(result){
        $("#label" + index).html(result.labels.toString());
        $("#colors" + index).css('backgroundColor',result.colors.toString());
        if(result.colors.toString().includes("Face count") == false){
            $("#image" + index).attr('src','/cropped/' + imageId + '.jpg');
        }
        $("#button" + index).fadeOut();
    });
}