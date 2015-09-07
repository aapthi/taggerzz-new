// upload links
function fillUploadedUrls( urlsArray )
	{
		if(chkvalues==0 || chkvalues==2){
			chkvalues=urlsArray.length;
		}
		var maxIdPresent = 0;
		$("input[id^='textbox']").each(function()
		{
			var textboxId = parseInt(this.id.replace("textbox", ""));
			if( parseInt(textboxId) > parseInt(maxIdPresent) )
			{
				maxIdPresent = textboxId;
			}
		});		
		if( (parseInt(maxIdPresent) == parseInt("1")) && ($('#textbox1').val().trim() == "") )
		{
			for( var urlNum = 1; urlNum < urlsArray.length; urlNum++  )
			{
				addTextBox( urlNum,1 );
			}
			addTextBox(urlNum,1);
			$('#textbox1').val( urlsArray[0] );
			for( var urlNum = 1; urlNum < urlsArray.length; urlNum++  )
			{
				var txtboxid = urlNum + 1;
				if( $("#textbox" + txtboxid).length == 1 )
				{
					$("#textbox" + txtboxid).val( urlsArray[urlNum] );
				}
			}
			verifySingleUrl( 1 );
			for( var urlNum = 1; urlNum < urlsArray.length; urlNum++  )
			{
				var txtboxid = urlNum + 1;
				verifySingleUrl( txtboxid );
			}
		}
		else
		{
			for( var urlNum = 0,newBoxId = maxIdPresent; urlNum < urlsArray.length; urlNum++,newBoxId++  )
			{
				addTextBox( newBoxId,1 );
			}
			addTextBox(newBoxId,1);
			for( var urlNum = 0,newBoxId = maxIdPresent+1; urlNum < urlsArray.length; urlNum++,newBoxId++  )
			{
				if( $("#textbox" + newBoxId).length == 1 )
				{
					$("#textbox" + newBoxId).val( urlsArray[urlNum] );
				}
			}
			for( var urlNum = 0,newBoxId = maxIdPresent+1; urlNum < urlsArray.length; urlNum++,newBoxId++  )
			{
				if( $("#textbox" + newBoxId).length == 1 )
				{
					verifySingleUrl( newBoxId );
				}
			}
			// addTextBox(urlNum,1);
		}


		return false;
	}

// link-highlights
var duplFocusEle = null;
function resetUrlStatus( counter )
	{
		var urlEntered=$.trim( stripHtmlTags($("#textbox"+counter).val()).replace(/&nbsp;/g,'') );
		urlEntered = urlEntered.trim();
		$("#textbox"+counter).val( urlEntered );

		if( urlEntered != "" )
		{
			var duplCheckReturn = isDuplicate(counter,urlEntered);
			if( parseInt(duplCheckReturn) == parseInt("1") )
			{
				if( duplFocusEle === null )
				{
					duplFocusEle = $("#textbox"+counter);
						$('#pop-up-alerts').popUpWindow({action: "open"});	
						$("#alert_header_meassge").html('');
						$("#alert_meassage").html(link_url_already_provided);
					if( $('#imgVerified' + counter).length == 1 )
					{
						var src = $('#imgVerified' + counter).prop('src');
						var imageNameArr = src.split( "/" );
						var imageName = imageNameArr[(imageNameArr.length)-1];
						if( imageName == "checkmark.png" )
						{
							if( $('#urlsDisplayCount').val() != "" )
							{
								$('#urlsDisplayCount').val( parseInt($('#urlsDisplayCount').val()) - parseInt("1") );
								displayUrlTotal( "***" );
							}
						}
						$('#verifyImg'+counter).html('');
					}
				}
				window.setTimeout(function(){$(duplFocusEle).focus();}, 100);
				return false;
			}
			else
			{
				duplFocusEle = null;
			}
			if( urlEntered.indexOf("taggerzz") > -1 )
			{
				if( duplFocusEle === null )
				{
					duplFocusEle = $("#textbox"+counter);
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(invaild_link_url);
					if( $('#imgVerified' + counter).length == 1 )
					{
						var src = $('#imgVerified' + counter).prop('src');
						var imageNameArr = src.split( "/" );
						var imageName = imageNameArr[(imageNameArr.length)-1];
						if( imageName == "checkmark.png" )
						{
							if( $('#urlsDisplayCount').val() != "" )
							{
								$('#urlsDisplayCount').val( parseInt($('#urlsDisplayCount').val()) - parseInt("1") );
								displayUrlTotal( "***" );
							}
						}
						$('#verifyImg'+counter).html('');
					}
				}
				window.setTimeout(function(){$(duplFocusEle).focus();}, 100);
				return false;
			}
			else
			{
				duplFocusEle = null;
			}
		}
		else if( $('#imgVerified' + counter).length == 0 )
		{
			$('#rem'+counter).html('');
			return false;
		}
		if( $("#originalValue").val() == urlEntered )
		{
			return false;
		}		
		$('#submitVal').val( "Go" );
		if( $('#imgVerified' + counter).length == 1 )
		{
			var src = $('#imgVerified' + counter).prop('src');
			var imageNameArr = src.split( "/" );
			var imageName = imageNameArr[(imageNameArr.length)-1];
			if( imageName == "checkmark.png" )
			{
				if( $('#urlsDisplayCount').val() != "" )
				{
					$('#urlsDisplayCount').val( parseInt($('#urlsDisplayCount').val()) - parseInt("1") );
					displayUrlTotal( "***" );
				}
			}
		}

		if( ($("#originalValue").val() != "") && (urlEntered == "") )
		{
			$('#rem'+counter).html('');
			removeTextBox( counter,0 );
			$('#verifyImg'+counter).html('');
			return false;
		}

		$('#verifyImg'+counter).html('');
		$('#editHighLinksChanged').val( 1 );
		chkvalues++;
		verifySingleUrl( counter );
		return false;
	}
	
function isDuplicate( counter,urlEntered )
	{
		var duplCheckReturn = 0;
		$("input[id^='textbox']").each(function()
		{
			var textboxId = parseInt(this.id.replace("textbox", ""));
			if( (parseInt(textboxId) != parseInt(counter)) && (urlEntered == $("#textbox"+textboxId).val().trim()) )
			{
				duplCheckReturn = 1;
				return;
			}
		});
		return duplCheckReturn;
	}

function addTextBox( count,useMode )
	{
		var loadingImgName = $('#basePath').val() + "/images/social_media/24x24_pg-loader-1.gif";
		var addUrlImgName = $('#basePath').val() + "/images/social_media/link_icon.png";
		var removeUrlImgName = $('#basePath').val() + "/images/social_media/delete.png";

		$('#submitVal').val( "Go" );

		$('#rem'+count).html('<a href="javascript:void(0);" onClick="removeTextBox('+count+',1)"><img src="' + removeUrlImgName + '" /></a>');

		var counter=count+1;
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'textBoxDiv'+counter);

		var newTextBoxDivHtml = "";

		newTextBoxDivHtml += '<div class="highlighted_links">';
		newTextBoxDivHtml += '<input type="text" class="brg_pad" placeholder="https://www.google.com/" autocomplete="on" id="textbox'+counter+'" onBlur="resetUrlStatus('+counter+')" />';
		newTextBoxDivHtml += '<b id="video'+counter+'"><img src="' + addUrlImgName + '" /></b>';
		newTextBoxDivHtml += '<span style="display:none" id="loadImg'+counter+'"><img src="' + loadingImgName + '" /></span>';
		newTextBoxDivHtml += '<span id="verifyImg'+counter+'"></span>';
		newTextBoxDivHtml += '<b id="rem'+counter+'"></b>';
		newTextBoxDivHtml += '</div>';

		newTextBoxDiv.html( newTextBoxDivHtml );

		newTextBoxDiv.appendTo("#textBoxesGroup");

		
		var totalUrlsPresent = 0;
		$("input[id^='textbox']").each(function()
		{
			totalUrlsPresent++;
		});
		if( parseInt(totalUrlsPresent) == parseInt("100") )
		{
			$('#pop-up-max-links').popUpWindow({action: "open"});
		}
		else
		{
			var newBoxHandler = function(event)
			{
				var keyCode = event.which;
				if( parseInt(keyCode) != parseInt("32") )
				{
					$("#textbox"+counter).off( "keypress",newBoxHandler );

					var maxIdPresent = 0;
					$("input[id^='textbox']").each(function()
					{
						var textboxId = parseInt(this.id.replace("textbox", ""));
						if( parseInt(textboxId) > parseInt(maxIdPresent) )
						{
							maxIdPresent = textboxId;
						}
					});
					
					if( parseInt(maxIdPresent) == parseInt(counter) )
					{
						addTextBox(counter,1);
					}
					else if( $("#originalValue").val() == "" )
					{
						$('#rem'+counter).html('<a href="javascript:void(0);" onClick="removeTextBox('+counter+',1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				}
			}
			$('#textbox'+counter).focus(function(event)
			{
				$("#textbox"+counter).on( "keypress",newBoxHandler );
				$("#originalValue").val($("#textbox"+counter).val().trim());
			});
			
			if( navigator.userAgent.toLowerCase().indexOf('chrome') > -1 )
			{
				$("#textbox"+counter).bind('paste', function(){
					$("#textbox"+counter).off( "keypress",newBoxHandler );

					var maxIdPresent = 0;
					$("input[id^='textbox']").each(function()
					{
						var textboxId = parseInt(this.id.replace("textbox", ""));
						if( parseInt(textboxId) > parseInt(maxIdPresent) )
						{
							maxIdPresent = textboxId;
						}
					});
					
					if( parseInt(maxIdPresent) == parseInt(counter) )
					{
						addTextBox(counter,1);
					}
					else if( $("#originalValue").val() == "" )
					{
						$('#rem'+counter).html('<a href="javascript:void(0);" onClick="removeTextBox('+counter+',1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				});
			}
		}
		
		if( parseInt(useMode) == parseInt("0") )
		{
			verifySingleUrl( count );
		}
	}
	
function removeTextBox( counter,mode )
	{
		$('#rem'+counter).html( '' );
		var deletingImgName = $('#basePath').val() + "/images/social_media/loading.gif";
		$('#loadImg'+counter).html( '<img src="' + deletingImgName + '" />' );
		$('#loadImg'+counter).show();

		$('#editHighLinksChanged').val( 1 );
		
		if( $("#textbox" + counter).val().trim() != "" )
		{
			if( $('#imgVerified' + counter).length == 1 )
			{
				var src = $('#imgVerified' + counter).prop('src');
				var imageNameArr = src.split( "/" );
				var imageName = imageNameArr[(imageNameArr.length)-1];
				if( imageName == "checkmark.png" )
				{
					if( $('#urlsDisplayCount').val() != "" )
					{
						if( parseInt($('#urlsDisplayCount').val()) >= parseInt("1") )
						{
							$('#urlsDisplayCount').val( parseInt($('#urlsDisplayCount').val()) - parseInt("1") );
							$('#totalUrlsSpan').html( "<strong>" + parseInt($('#urlsDisplayCount').val()) + "/100</strong>" );
						}
					}
				}
			}
		}

		var deleteUrl = $('#baseUrl').val() + "/databox/delete-url";
		var ticmarksCount=0;
		var invalidlinksCount=0;
		var loadingvisibleCount=0;
		$.ajax({
		  url: deleteUrl,
		  type: "POST",
		  data:{urlId:counter},
		  async: false,
		  success: function(data) {
			$("input[id^='textbox']").each(function()
			{
				var textboxId = parseInt(this.id.replace("textbox", ""));
				var value=$('#textbox'+textboxId).val().trim();
				if( value != "" )
				{
					if( $('#imgVerified' + textboxId).length == 1 )
					{
						var src = $('#imgVerified' + textboxId).prop('src');
						var imageNameArr = src.split( "/" );
						var imageName = imageNameArr[(imageNameArr.length)-1];
						if( imageName == "checkmark.png" )
						{
							ticmarksCount++;
						}
						if( imageName == "warning.png" ){
							invalidlinksCount++;
						}								
					}
					if( $('#imgVerified' + textboxId).length == 0 )
					{
						loadingvisibleCount++;
					}
				}
			});
			if(loadingvisibleCount==1){
				if(ticmarksCount!=0 && invalidlinksCount!=0){
					chkvalues=2;
				}else if(ticmarksCount==0 && invalidlinksCount!=0){
					chkvalues=2;
				}else if(ticmarksCount!=0 && invalidlinksCount==0){
					chkvalues=2;
				}
			}
			if(ticmarksCount==0 && invalidlinksCount==0){
				chkvalues=0;
			}
			
			if( $('#submitVal').val() == "CStyle" )
			{
				$('#loadImg'+counter).hide();
				$("#textBoxDiv" + counter).remove();
				$('#finalnvalidCount').val( parseInt($('#finalnvalidCount').val()) - parseInt("1") );
				if( parseInt($('#finalnvalidCount').val()) == parseInt("0") )
				{
					// alert( $('#linksForm').attr( "action" ) );
					$('#linksForm').submit();
				}
				return false;
			}
			
			if( parseInt(mode) == parseInt("1") )
			{
				var boxCount = 0;
				$("input[id^='textbox']").each(function()
				{
					boxCount++;
				});
				$("#textBoxDiv" + counter).remove();
				if( parseInt(boxCount) == parseInt("1") )
				{
					addTextBox(0,1);
				}
			}
			$('#loadImg'+counter).hide();
			var loadingImgName = $('#basePath').val() + "/images/social_media/24x24_pg-loader-1.gif";
			$('#loadImg'+counter).html( '<img src="' + loadingImgName + '" />' );
		  }
		});
	}

	function displayUrlTotal( urlEntered )
	{
		if( urlEntered != "" )
		{
			if( $('#urlsDisplayCount').val() != "" )
			{
				$('#totalUrlsSpan').html( "<strong>" + parseInt($('#urlsDisplayCount').val()) + "/100</strong>" );
			}
		}

		return false;
	}

	function verifySingleUrl( textboxId )
	{
		var allOkImgName = $('#basePath').val() + "/images/social_media/checkmark.png";
		var invalidImgName = $('#basePath').val() + "/images/social_media/warning.png";

		var isValid = true;
		
		if( $('#imgVerified' + textboxId).length == 1 )
		{
			var src = $('#imgVerified' + textboxId).prop('src');
			var imageNameArr = src.split( "/" );
			var imageName = imageNameArr[(imageNameArr.length)-1];
			if( imageName == "checkmark.png" )
			{
				return;
			}
		}

		$('#loadImg'+textboxId).show();

		$('#textbox'+textboxId).attr('readonly', true);
		
		var value=$('#textbox'+textboxId).val().trim();

		if( value == "" )
		{
			$('#textbox'+textboxId).attr('readonly', false);
			$('#loadImg'+textboxId).hide();
			$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" />');
			return;
		}

		
		
		isValid = true;
		var attributesUrl = $('#baseUrl').val() + "/databox/check-attributes";	
		
		$.ajax({
		  url: attributesUrl,
		  type: "POST",
		  data:{currentUrl:value,urlId:textboxId},
		  dataType: "json",
		  success: function(data) {
			chkvalues--;
			if(chkvalues==0){
				chkvalues=2;
			}
			
			isValid = true;		
			
			if( parseInt(data.linkValidityStatus) == 0 )
			{
				isValid = false;
			}
			
			if( ! isValid )
			{
				$('#textbox'+textboxId).attr('readonly', false);
				$('#loadImg'+textboxId).hide();
				$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" />');
				return;
			}

			if( isValid )
			{
				// alert( allOkImgName );
				$('#textbox'+textboxId).attr('readonly', false);
				$('#loadImg'+textboxId).hide();
				$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + allOkImgName + '" />');

				if( $('#urlsDisplayCount').val() != "" )
				{
					$('#urlsDisplayCount').val( parseInt($('#urlsDisplayCount').val()) + parseInt("1") );
				}

				displayUrlTotal( value );
			}
			
		  }
		});
		

		if( parseInt($('#submitClicked').val()) == parseInt("1") )
		{
			$('#submitClicked').val( 0 );
			validateLinks();
		}
	}

	function verifySingleUrlUb( textboxId )
	{
		var allOkImgName = $('#basePath').val() + "/images/social_media/checkmark.png";
		var invalidImgName = $('#basePath').val() + "/images/social_media/warning.png";

		var isValid = true;
		
		if( $('#imgVerified' + textboxId).length == 1 )
		{
			var src = $('#imgVerified' + textboxId).prop('src');
			var imageNameArr = src.split( "/" );
			var imageName = imageNameArr[(imageNameArr.length)-1];
			if( imageName == "checkmark.png" )
			{
				return;
			}
		}

		var value=$('#textbox'+textboxId).val().trim();
		
		$('#loadImg'+textboxId).show();
		
		if( value == "" )
		{
			$('#loadImg'+textboxId).hide();
			$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" />');
			return;
		}

		/*
		isValid = true;
		var encodedURL = encodeURIComponent(value);
		$.ajax({
		  url: "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20html%20where%20url%3D%22" + encodedURL + "%22&format=json",
		  type: "get",
		  async: false,
		  dataType: "json",
		  success: function(data) {
			isValid = data.query.results != null;
			// alert( isValid );
		  },
		  error: function(){
			isValid = false;
		  }
		});

		// alert( isValid );
		// return false;
		if( ! isValid )
		{
			$('#loadImg'+textboxId).hide();
			$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" /><span style="color:red">&nbsp;Please</span>');
			return;
		}
		*/
		
		isValid = true;
		var attributesUrl = $('#baseUrl').val() + "/databox/check-attributes";
		/*
		alert( attributesUrl );
		if( isValid )
		{
			break;
		}
		*/
		
		$.ajax({
		  url: attributesUrl,
		  type: "POST",
		  data:{currentUrl:value,urlId:textboxId},
		  dataType: "json",
		  async: false,
		  success: function(data) {
			isValid = true;
			if( parseInt(data.linkValidityStatus) == 0 )
			{
				isValid = false;
			}
			if( ! isValid )
			{
				$('#loadImg'+textboxId).hide();
				$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" />');
				return;
			}

			if( isValid )
			{
				// alert( allOkImgName );
				$('#loadImg'+textboxId).hide();
				$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + allOkImgName + '" />');

				if( $('#urlsDisplayCount').val() != "" )
				{
					$('#urlsDisplayCount').val( parseInt($('#urlsDisplayCount').val()) + parseInt("1") );
				}

				displayUrlTotal( value );
			}
		  }
		});
		
		
		
		if( parseInt($('#submitClicked').val()) == parseInt("1") )
		{
			$('#submitClicked').val( 0 );
			validateLinks();
		}
	}
	
	function verifyAllUrls( textboxId,totalUrlsPresent )
	{
		var allOkImgName = $('#basePath').val() + "/images/social_media/checkmark.png";
		var invalidImgName = $('#basePath').val() + "/images/social_media/invalid_link.png";

		var isValid = true;
		
		if( $('#imgVerified' + textboxId).length == 1 )
		{
			var src = $('#imgVerified' + textboxId).prop('src');
			var imageNameArr = src.split( "/" );
			var imageName = imageNameArr[(imageNameArr.length)-1];
			if( imageName == "checkmark.png" )
			{
				if( $('#allOkUrlCount').val() != "" )
				{
					$('#allOkUrlCount').val( parseInt($('#allOkUrlCount').val()) + parseInt("1") );
				}
				if( parseInt($('#allOkUrlCount').val()) == parseInt(totalUrlsPresent) )
				{
					$('#submitVal').val( "Continue" );
				}

				return;
			}
		}

		var value=$('#textbox'+textboxId).val().trim();
		
		$('#loadImg'+textboxId).show();

		if( value == "" )
		{
			$('#loadImg'+textboxId).hide();
			$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" />');
			if( $('#allOkUrlCount').val() != "" )
			{
				$('#allOkUrlCount').val( parseInt($('#allOkUrlCount').val()) + parseInt("1") );
			}
			if( parseInt($('#allOkUrlCount').val()) == parseInt(totalUrlsPresent) )
			{
				$('#submitVal').val( "Continue" );
			}
			return;
		}

		/*
		isValid = true;
		var encodedURL = encodeURIComponent(value);
		$.ajax({
		  url: "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20html%20where%20url%3D%22" + encodedURL + "%22&format=json",
		  type: "get",
		  async: false,
		  dataType: "json",
		  success: function(data) {
			isValid = data.query.results != null;
			// alert( isValid );
		  },
		  error: function(){
			isValid = false;
		  }
		});

		// alert( isValid );
		// return false;
		if( ! isValid )
		{
			$('#loadImg'+textboxId).hide();
			$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" /><span style="color:red">&nbsp;Please</span>');
			return;
		}
		*/
		
		isValid = true;
		var attributesUrl = $('#baseUrl').val() + "/databox/check-attributes";
		/*
		alert( attributesUrl );
		if( isValid )
		{
			break;
		}
		*/
		
		$.ajax({
		  url: attributesUrl,
		  type: "POST",
		  data:{currentUrl:value,urlId:textboxId},
		  dataType: "json",
		  success: function(data) {
			isValid = true;
			
			
			if( parseInt(data.linkValidityStatus) == 0 )
			{
				isValid = false;
			}
		  }
		});
		
		if( ! isValid )
		{
			$('#loadImg'+textboxId).hide();
			$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" />');
			if( $('#allOkUrlCount').val() != "" )
			{
				$('#allOkUrlCount').val( parseInt($('#allOkUrlCount').val()) + parseInt("1") );
			}
			if( parseInt($('#allOkUrlCount').val()) == parseInt(totalUrlsPresent) )
			{
				$('#submitVal').val( "Continue" );
			}
			return;
		}

		if( isValid )
		{
			// alert( allOkImgName );
			$('#loadImg'+textboxId).hide();
			$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + allOkImgName + '" />');

			if( $('#allOkUrlCount').val() != "" )
			{
				$('#allOkUrlCount').val( parseInt($('#allOkUrlCount').val()) + parseInt("1") );
			}

			displayUrlTotal( value );
			if( parseInt($('#allOkUrlCount').val()) == parseInt(totalUrlsPresent) )
			{
				$('#submitVal').val( "Continue" );
			}
		}
	}

	function validateLinks()
	{
		$("input[id^='textbox']").each(function()
		{
			var textboxId = parseInt(this.id.replace("textbox", ""));
			var value=$('#textbox'+textboxId).val().trim();
			if( value != "" )
			{
				if( ($('#imgVerified' + textboxId).length == 0) && ($('#loadImg' + textboxId).is(':hidden')) )
				{
					verifySingleUrl( textboxId );
				}
			}
		});

		var allVerified = true;
		$("input[id^='textbox']").each(function()
		{
			var textboxId = parseInt(this.id.replace("textbox", ""));
			var value=$('#textbox'+textboxId).val().trim();
			if( value != "" )
			{
				if( $('#imgVerified' + textboxId).length == 0 )
				{
					allVerified = false;
					return;
				}
			}
		});
		
		if( allVerified )
		{
			$('#submitVal').val( "Continue" );
		}
		else
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(not_alllinks_verifying);
			$('#submitClicked').val( 0 );
			$('#dcInprogressDiv').hide();
			$('#dcHtmlDiv').show();
			return false;
		}

		var minimumLinksCount = 0;
		$("input[id^='textbox']").each(function()
		{
			var textboxId = parseInt(this.id.replace("textbox", ""));
			var value=$('#textbox'+textboxId).val().trim();
			if( value != "" )
			{
				if( $('#imgVerified' + textboxId).length == 1 )
				{
					var src = $('#imgVerified' + textboxId).prop('src');
					var imageNameArr = src.split( "/" );
					var imageName = imageNameArr[(imageNameArr.length)-1];
					// alert( imageName );
					if( imageName == "checkmark.png" )
					{
						minimumLinksCount++;
					}
					if( parseInt(minimumLinksCount) == parseInt("5") )
					{
						return;
					}
				}
			}
		});
		if( parseInt(minimumLinksCount) < parseInt("5") )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(minimum_valid_links);
			$('#submitClicked').val( 0 );
			$('#dcInprogressDiv').hide();
			$('#dcHtmlDiv').show();
			return false;
		}


		var submitVal = $('#submitVal').val();
		if( submitVal != "Go" )
		{
			nextStepsHighlights( submitVal );
			return false;
		}
	}

//Book Marks 
function bookMarksPopUrls(){
$("input[id^='main-']").each(function(){
	$('#'+this.id).click(function() {
		if(/^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test($('#'+this.id).val())) {
			var urlIdLength=this.id.split('-');
			if(urlIdLength.length==2){
				var flag3=true;
				if($('#'+this.id).attr('checked')){
					$count=1;
					$("input[id^='main-']").each(function(){
						if($count!=1){
							if(!$('#'+this.id).attr('checked')){
								flag3=false;
								return;
							}
						}
						$count++;
					});
					if(flag3==true){
						$('#main-0').attr('checked',true);
					}else{
						$('#main-0').attr('checked',false);
					}
				}else{
					$('#main-0').attr('checked',false);
				}
			}else{
				var flag4=true;
				var flag5=true;
				var flag7=true;
				var flag6=true;
				if($('#'+this.id).attr('checked')){
					if(urlIdLength.length==4){
						$("input[id^='main-cat-"+urlIdLength[2]+"-']").each(function(){
							if(!$('#'+this.id).attr('checked')){
								flag5=false;
								return;
							}
						});
						if(flag5==true){
							$('#main-cat-'+urlIdLength[2]).attr('checked',true);
						}else{
							$('#main-cat-'+urlIdLength[2]).attr('checked',false);
						}
					}else if(urlIdLength.length==6){
						$("input[id^='main-cat-"+urlIdLength[2]+"-subcat-"+urlIdLength[4]+"-']").each(function(){
							if(!$('#'+this.id).attr('checked')){
								flag6=false;
								return;
							}
						});
						if(flag6==true){
							$('#main-cat-'+urlIdLength[2]+'-subcat-'+urlIdLength[4]).attr('checked',true);
						}else{
							$('#main-cat-'+urlIdLength[2]+'-subcat-'+urlIdLength[4]).attr('checked',false);
						}
						
						$("input[id^='main-cat-"+urlIdLength[2]+"-']").each(function(){
							if(!$('#'+this.id).attr('checked')){
								flag7=false;
								return;
							}
						});
						if(flag7==true){
							$('#main-cat-'+urlIdLength[2]).attr('checked',true);
						}else{
							$('#main-cat-'+urlIdLength[2]).attr('checked',false);
						}
					}
					$count1=1;
					$("input[id^='main-']").each(function(){
						if($count1!=1){
							if(!$('#'+this.id).attr('checked')){
								flag4=false;
								return;
							}
						}
						$count1++;
					});
					if(flag4==true){
						$('#main-0').attr('checked',true);
					}else{
						$('#main-0').attr('checked',false);
					}
				}else{
					$('#main-0').attr('checked',false);
					if(urlIdLength.length==4){
						$('#main-cat-'+urlIdLength[2]).attr('checked',false);
					}else if(urlIdLength.length==6){
						$('#main-cat-'+urlIdLength[2]).attr('checked',false);
						$('#main-cat-'+urlIdLength[2]+'-subcat-'+urlIdLength[4]).attr('checked',false);
					}
				}
			}
		}else{
			var valueLength=$('#'+this.id).val().split('-');
			if(valueLength.length==1){
				if($('#'+this.id).attr('checked')){
					$("input[id^='main-']").each(function(){
						$('#'+this.id).attr('checked',true);
					});
				}else{
					$("input[id^='main-']").each(function(){
						$('#'+this.id).attr('checked',false);
					});
				}
			}else if(valueLength.length==2 || valueLength.length==3){
				var flag2=true;
				var flag1=true;
				if($('#'+this.id).attr('checked')){
					$("input[id^='"+this.id+"-']").each(function(){
						$('#'+this.id).attr('checked',true);
					});
					if(valueLength.length==3){
						$("input[id^='main-cat-"+valueLength[2]+"-']").each(function(){
							if(!$('#'+this.id).attr('checked')){
								flag1=false;
								return;
							}
						});
						if(flag1==true){
							$('#main-cat-'+valueLength[2]).attr('checked',true);
						}else{
							$('#main-cat-'+valueLength[2]).attr('checked',false);
						}
					}
					$count2=1;
					$("input[id^='main-']").each(function(){
						if($count2!=1){
							if(!$('#'+this.id).attr('checked')){
								flag2=false;
								return;
							}
						}
						$count2++;
					});
					if(flag2==true){
						$('#main-0').attr('checked',true);
					}else{
						$('#main-0').attr('checked',false);
					}
				}else{
					$("input[id^='"+this.id+"-']").each(function(){
						$('#'+this.id).attr('checked',false);
					});
					if(valueLength.length==3){
						$('#main-cat-'+valueLength[2]).attr('checked',false);
					}
					$('#main-0').attr('checked',false);
				}
			}
		}
		//Get count of checked links
		var checkedCount=0;
		$("input[class^='uriClass']").each(function(){
			if($('#'+this.id).attr('checked')){
				checkedCount++;
			}					
		});
		$('#selectedTotalBM').html(checkedCount);
		//End
	});
});
}
//End
	