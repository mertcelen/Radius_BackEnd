function updatePassword(){
  var old = $("#oldPassword");
  var updated = $("#newPassword");
  var update2 = $("#newPassword2");
  // $.ajax({
  //   url : "/api/user/password",
  //   data : {
  //     "old-password" : old.val(),
  //     "new-password" : updated.val(),
  //     "new-password_confirmation" : update2.val()
  //   },
  //   type : "POST",
  // }).done(function(response){
  //     console.log(response);
  // }).fail(function(){
  //     alert("error");
  // });
}
