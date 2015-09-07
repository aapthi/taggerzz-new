$(document).ready(function()
	{
		$( "#emailId" ).val( "" );
		$( "#userPassword" ).val( "" );
		
		$( "#emailId" ).keypress(function(event)
		{
			var keyCode = event.which;
			if( parseInt(keyCode) == parseInt("13") )
			{
				$( "#userPassword" ).focus();
			}
		});

		$( "#userPassword" ).keypress(function(event)
		{
			var keyCode = event.which;
			if( parseInt(keyCode) == parseInt("13") )
			{
				checkLogin('regChe');
			}
		});
		
		$( "#emailId" ).focus(function(event)
		{
			$('#userMessage').html( "" );
			$('#userMessage').hide();
		});
		$( "#userPassword" ).focus(function(event)
		{
			$('#userMessage').html( "" );
			$('#userMessage').hide();
		});
		
		//basic login pop-up
		$('#open-pop-up-1').click(function(e) {
			e.preventDefault();
			$( "#emailId" ).val( "" );
			$( "#userPassword" ).val( "" );
			if( (parseInt($( "#postClicked" ).val()) == parseInt("0")) && (parseInt($( "#nsfwMcClicked" ).val()) == parseInt("0")) )
			{
				$( "#postHighlights" ).val( 0 );
				$( "#nsfwMcSet" ).val( 0 );
				$( "#categoryId" ).val( 0 );
				$( "#hashName" ).val( "" );
				$( "#categoryTitle" ).val( "" );
				$( "#settingId" ).val( 0 );
				$( "#categoryImage" ).val( "" );
				// clearSessRedirection();
				$('#userMessage').hide();
				$('#userMessage').html( "" );
			}
			if( parseInt($( "#postClicked" ).val()) == parseInt("1") )
			{
				$( "#postClicked" ).val( 0 );
			}
			if( parseInt($( "#nsfwMcClicked" ).val()) == parseInt("1") )
			{
				$( "#nsfwMcClicked" ).val( 0 );
			}

			
			$('#pop-up-1').popUpWindow({action: "open",
				onClose: function()
				{
					$('#userMessage').show();
					$('#userMessage').html( "Closing login..." );
					setRedirectSession();
				}
			});

		});
	});
