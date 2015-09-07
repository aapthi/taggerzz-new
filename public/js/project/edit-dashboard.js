function cStyleSet()
	{
		var settingId = $('#settingId').val();
		var userdefinedBookmarksUrl = $('#baseUrl').val();
		if( parseInt($('#settingId').val()) == parseInt("0") )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(select_customize);
			return false;
		}
		else if( (parseInt(settingId) == parseInt("2")) || (parseInt(settingId) == parseInt("3")) )
		{
			userdefinedBookmarksUrl += "/databox/display-ascending";
		}

		$('#userdefinedBookmarksForm').attr( "action",userdefinedBookmarksUrl );
		$('#userdefinedBookmarksForm').submit();
	}

	$(document).ready(function()
	{
		$("input[id^='textbox']").each(function()
		{
			var idArray = this.id.replace("textbox-", "").split("-");
			$('#textbox-' + idArray[0] + '-' + idArray[1]).focus(function(event)
			{
				$("#originalValue").val($('#textbox-' + idArray[0] + '-' + idArray[1]).val().trim());
				if( $('#catCustLoseFlag'+idArray[0]).val() != "" && parseInt($('#catCustLoseFlag'+idArray[0]).val()) == parseInt("0") )
				{
					$('#catCustLoseFlag'+idArray[0]).val( 1 );
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(lose_customization);
					return false;
				}
			});
		});		
	});

