function addProduct() {
    var type = $("#typeSelect option:selected").val();
    var brand = $("#brandSelect option:selected").val();
    var color = $("#colorSelect option:selected").val();
    var link = $("#productLink").val();
    var image = $('#imageLink').val();
    $.post({
        url: '/api/product',
        data: {
            'type': type,
            'brand': brand,
            'color': color,
            'link' : link,
            'image' : image
        },
        success : function(data){
            if(data.success){
                $(".productError").html(data.success.message).removeAttr('hidden');
                $("#productLink").val('');
                $("#imageLink").val('');
            }else{
                $(".productError").html(data.error.message).removeAttr('hidden');
            }
        }
    });
}