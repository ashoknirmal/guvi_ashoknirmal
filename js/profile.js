$(function(){
  const token=localStorage.getItem("token");
  if(!token){location.href="login.html";return;}

  function loadProfile(){
    $.ajax({
      url:"php/profile.php",
      headers:{Authorization:"Bearer "+token},
      success:res=>{
        if(res.success){
          $("#displayName").text(res.user.name);
          $("#displayEmail").text(res.user.email);
          $("#displayAge").text(res.user.age);
          $("#displayDob").text(res.user.dob);
          $("#displayContact").text(res.user.contact);
          $("#name").val(res.user.name);
          $("#age").val(res.user.age);
          $("#dob").val(res.user.dob);
          $("#contact").val(res.user.contact);
        }else location.href="login.html";
      }
    });
  }

  loadProfile();

  $("#updateForm").submit(function(e){
    e.preventDefault();
    const data={
      name:$("#name").val(),
      age:$("#age").val(),
      dob:$("#dob").val(),
      contact:$("#contact").val()
    };
    $.ajax({
      url:"php/profile.php",
      method:"POST",
      headers:{Authorization:"Bearer "+token},
      data:JSON.stringify(data),
      contentType:"application/json",
      success:res=>{
        if(res.success){alert("Updated");loadProfile();}
        else $("#updateMsg").text(res.message);
      }
    });
  });
});
