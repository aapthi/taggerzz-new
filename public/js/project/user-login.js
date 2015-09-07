
$(document).ready(function()
	{
		if( $("#formUsageMode").val() == "emailLogin" )
		{
			$('#displayname').val( "" );
			$('#emailLoginPassword').val();
		}
	
		$("#userDetailsForm").submit(function(event)
		{
			var submitUserDtsForm = true;
			
			var displayName=$.trim( stripHtmlTags($('#displayname').val()).replace(/&nbsp;/g,'') );
			$('#displayname').val( displayName );
			if( displayName == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(display_name);
				$('#displayname').focus();
				submitUserDtsForm = false;
			}
			if( ! submitUserDtsForm )
			{
				event.preventDefault();
				return false;
			}
			var invalidDispNameCharacters = /[^a-z^A-Z^0-9^ ]/;
			if( invalidDispNameCharacters.test( displayName ) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(letters_digits_only + "display name.");
				$("#displayname").focus();
				submitUserDtsForm = false;
			}
			if( ! submitUserDtsForm )
			{
				event.preventDefault();
				return false;
			}
			if( parseInt(displayName.length) > parseInt("50") )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(max_fifty_allowed);
				$("#display_name").focus();
				submitUserDtsForm = false;
			}
			if( ! submitUserDtsForm )
			{
				event.preventDefault();
				return false;
			}

			var userPassword = $.trim( stripHtmlTags($('#emailLoginPassword').val()).replace(/&nbsp;/g,'') );
			$('#emailLoginPassword').val( userPassword );
			var confirmPassword = $.trim( stripHtmlTags($('#confirmPassword').val()).replace(/&nbsp;/g,'') );
			$('#confirmPassword').val( confirmPassword );
			if( userPassword == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(entered_password);
				$('#userPassword').focus();
				submitUserDtsForm = false;
			}
			if( ! submitUserDtsForm )
			{
				event.preventDefault();
				return false;
			}			
			if(userPassword!=confirmPassword){
			$('#userPassword').focus();
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(entered_confirm_password);
				submitUserDtsForm = false;
			}			
			if( ! submitUserDtsForm )
			{
				event.preventDefault();
				return false;
			}			
			if(parseInt($('#email').length)==parseInt("1")){
				var email = $('#email').val().trim();
				if( email == "" )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(entered_email_id);
					$('#email').focus();
					submitUserDtsForm = false;
				}
				if( ! submitUserDtsForm )
				{
					event.preventDefault();
					return false;
				}
			}
			var src = $('#uploadedAccountImage1').prop('src');
			
			var imageNameArr = src.split( "/" );
			var imageName = imageNameArr[(imageNameArr.length)-1];
			if( imageName == "profile_default_img.png" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(select_image);
				submitUserDtsForm = false;
			}
			if( ! submitUserDtsForm )
			{
				event.preventDefault();
				return false;
			}

			var baseUrl = $("#baseUrl").val();
			var formUsageMode = $("#formUsageMode").val();
			
			var userDtsFormUrl = baseUrl;
			if( formUsageMode == "socialLogin" )
			{
				userDtsFormUrl += "/databoxuser/update-profile";
			}
			else if( formUsageMode == "emailLogin" )
			{
				userDtsFormUrl += "/databoxuser/email-login";
			}
			$("#userDetailsForm").attr( "action",userDtsFormUrl  );
		});
	});
