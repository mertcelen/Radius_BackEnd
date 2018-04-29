var list = [];
function select(id){
    if(list.indexOf(id) === -1){
        $("#" + id).addClass('selected');
        list.push(id);
    }else{
        $("#" + id).removeClass('selected');
        list.splice(list.indexOf(id),1);
    }
}

function update(){
    var loading = $(".loading").html();
    $(".modal-body").html(loading);
    $("#modalDialog").modal();
    if(list.length < 5){
        $(".modal-body").html("Please select at least 5 photos that you like.");
        return;
    }
    $.post({
        url: '/api/user/style',
        data: {
            'selected': list,
            'secret': secret
        },
        success: function (data) {
            if(data.success){
                $(".modal-body").html("Style saved, you will be redirected in 2 seconds.");
                setTimeout(function(){
                    window.location.href = "/home";
                },2000);
            }else{
                $(".modal-body").html(data.error.message);
            }
        }
    });
}