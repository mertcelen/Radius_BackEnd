function search(){
    var type = $("#typeSelect option:selected").val();
    var brand = $("#brandSelect option:selected").val();
    var color = $("#colorSelect option:selected").val();
    var gender = $("#genderSelect option:selected").val();
    var url = "?";
    if(document.getElementById("typeSelect").selectedIndex !== 0) url = url + "&type=" + type;
    if(document.getElementById("brandSelect").selectedIndex !== 0) url = url + "&brand=" + brand;
    if(document.getElementById("colorSelect").selectedIndex !== 0) url = url + "&color=" + color;
    if(document.getElementById("genderSelect").selectedIndex !== 0) url = url + "&gender=" + gender;
    window.location.href = location.pathname + url;
}

$(function(){

});