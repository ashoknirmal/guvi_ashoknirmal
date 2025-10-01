$(function(){
  $("#loginForm").submit(function(e){
    e.preventDefault();
    const data={email:$("#email").val(),password:$("#password").val()};
    $.ajax({
      url:"php/login.php",
      method:"POST",
      data:JSON.stringify(data),
      contentType:"application/json",
      success:res=>{
        if(res.success){
          localStorage.setItem("token",res.token);
          localStorage.setItem("user",JSON.stringify(res.user));
          location.href="profile.html";
        }else $("#loginMsg").text(res.message);
      }
    });
  });
});
