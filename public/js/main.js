function detect(imageId, type, index, part) {
    $("#image" + index + "_" + part).attr('src', 'loading.gif');
    $.post('/api/vision/magic', {
            "imageId": imageId,
            "type": type,
            "part": part
        }, function (result) {
            if (result.error !== null) {
                $("#image" + index + "_" + part).attr('src', '/cropped/' + imageId + "_" + part + '.jpg').attr('onclick', '');
                $("#color_" + index + "_" + part).css('backgroundColor', result.colors.toString());
                $("#label_" + index + "_" + part).html(result.labels.toString());
                $("#time_" + index + "_" + part).html(result.time.toString());
                $("#button" + index).fadeOut();
            }
        }
    ).fail(function () {
        $("#image" + index + "_" + part).attr('src', 'error.png');
    });
    ;
}