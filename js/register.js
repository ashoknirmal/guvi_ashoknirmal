$(function(){
  $("#registerForm").submit(function(e){
    e.preventDefault();
    const data={
      name:$("#name").val(),
      email:$("#email").val(),
      password:$("#password").val(),
      age:$("#age").val(),
      dob:$("#dob").val(),
      contact:$("#contact").val()
    };
    $.ajax({
      url:"php/register.php",
      method:"POST",
      data:JSON.stringify(data),
      contentType:"application/json",
      success:res=>{
        if(res.success){
          alert("Registered! Please login.");
          location.href="login.html";
        }else $("#regMsg").text(res.message);
      }
    });
  });
});
