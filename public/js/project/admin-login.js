function checkLogin(){
	var emailId = $('#emailId').val().trim();
	if( emailId == "" ){
		alert( "Please enter your email." );
		$('#emailId').focus();
		return false;
	}
	var userPassword = $('#userPassword').val().trim();
	if( userPassword == "" ){
		alert( "Please enter your password." );
		$('#userPassword').focus();
		return false;
	}
	$('#userMessage').show();
	$('#userMessage').html( "Checking Username,Password..." );
	var emailExistsUrl = $('#baseUrl').val() + "/admin/admin-email-exists";
	$.ajax({
	  url: emailExistsUrl,
	  type: "POST",
	  data:{emailId:emailId,userPassword:userPassword},
	  async: false,
	  success: function(data) {
		if(parseInt(data) == parseInt("0")){
			$('#userMessage').html( "Incorrect user name and password." );
			return false;
		}
		else{
			submitLoginForm();
		}
	  }
	});
}
function submitLoginForm(){
	var baseUrl = $("#baseUrl").val();
	var adminSetting = baseUrl + "/admin/admin-login";
	$("#userLoginFormUrl").attr( "action",adminSetting  );
	$("#userLoginFormUrl").submit();
	return false;
}