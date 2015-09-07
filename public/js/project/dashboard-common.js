// upload links
function fillUploadedUrls( urlsArray )
	{
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
// links-common	
var duplFocusEle = null;

function resetUrlStatus( categoryId,counter )
	{
		var urlEntered=$.trim( stripHtmlTags($("#textbox-"+categoryId+"-"+counter).val()).replace(/&nbsp;/g,'') );
		urlEntered = urlEntered.trim();
		$("#textbox-"+categoryId+"-"+counter).val( urlEntered );

		if( urlEntered != "" )
		{
			var duplCheckReturn = isDuplicate(categoryId,counter,urlEntered);
			if( parseInt(duplCheckReturn) == parseInt("1") )
			{
				if( duplFocusEle === null )
				{
					duplFocusEle = $("#textbox-"+categoryId+"-"+counter);
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(link_url_already_provided);
					if( $('#imgVerified-'+categoryId+"-"+counter).length == 1 )
					{
						var src = $('#imgVerified-'+categoryId+"-"+counter).prop('src');
						var imageNameArr = src.split( "/" );
						var imageName = imageNameArr[(imageNameArr.length)-1];
						if( imageName == "manage_checking.png" )
						{
							if( $('#urlsDisplayCount'+categoryId).val() != "" )
							{
								$('#urlsDisplayCount'+categoryId).val( parseInt($('#urlsDisplayCount'+categoryId).val()) - parseInt("1") );
								displayUrlTotal( "***",categoryId );
							}
						}
						$('#verifyImg-'+categoryId+'-'+counter).html('');
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
					duplFocusEle = $("#textbox-"+categoryId+"-"+counter);
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(invaild_link_url);
					if( $('#imgVerified-'+categoryId+"-"+counter).length == 1 )
					{
						var src = $('#imgVerified-'+categoryId+"-"+counter).prop('src');
						var imageNameArr = src.split( "/" );
						var imageName = imageNameArr[(imageNameArr.length)-1];
						if( imageName == "manage_checking.png" )
						{
							if( $('#urlsDisplayCount'+categoryId).val() != "" )
							{
								$('#urlsDisplayCount'+categoryId).val( parseInt($('#urlsDisplayCount'+categoryId).val()) - parseInt("1") );
								displayUrlTotal( "***",categoryId );
							}
						}
						$('#verifyImg-'+categoryId+'-'+counter).html('');
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
		else if( $('#imgVerified-'+categoryId+"-"+counter).length == 0 )
		{
			// $('#rem-'+categoryId+'-'+counter).html('');
			return false;
		}
		if( $("#originalValue").val() == urlEntered )
		{
			return false;
		}
		
		if( $('#imgVerified-'+categoryId+"-"+counter).length == 1 )
		{
			var src = $('#imgVerified-'+categoryId+"-"+counter).prop('src');
			var imageNameArr = src.split( "/" );
			var imageName = imageNameArr[(imageNameArr.length)-1];
			if( imageName == "manage_checking.png" )
			{
				if( $('#urlsDisplayCount'+categoryId).val() != "" )
				{
					$('#urlsDisplayCount'+categoryId).val( parseInt($('#urlsDisplayCount'+categoryId).val()) - parseInt("1") );
					displayUrlTotal( "***",categoryId );
				}
			}
		}

		if( ($("#originalValue").val() != "") && (urlEntered == "") )
		{
			// $('#rem-'+categoryId+'-'+counter).html('');
			removeTextBox( categoryId,counter,0 );
			$('#verifyImg-'+categoryId+'-'+counter).html('');
			return false;
		}

		$('#verifyImg-'+categoryId+'-'+counter).html('');
		$('#catBoxesChanged'+categoryId).val( 1 );
		chkvalues++;
		verifySingleUrl( categoryId,counter );
		return false;
	}
function isDuplicate( categoryId,counter,urlEntered )
	{
		var duplCheckReturn = 0;
		$("input[id^='textbox']").each(function()
		{
			var idArray = this.id.replace("textbox-", "").split("-");
			if( parseInt(idArray[0]) == parseInt(categoryId) )
			{
				var textboxId = idArray[1];
				if( (parseInt(textboxId) != parseInt(counter)) && (urlEntered == $("#textbox-"+categoryId+"-"+textboxId).val().trim()) )
				{
					duplCheckReturn = 1;
					return;
				}
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

		newTextBoxDivHtml += '<div class="add_links">';
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
						$('#rem-'+categoryId+'-'+counter).html('<a href="javascript:void(0);" onClick="removeTextBox('+counter+',1)"><img src="' + removeUrlImgName + '" /></a>');
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
						$('#rem-'+categoryId+'-'+counter).html('<a href="javascript:void(0);" onClick="removeTextBox('+counter+',1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				});
			}
		}
		
		if( parseInt(useMode) == parseInt("0") )
		{
			verifySingleUrl( categoryId,count );
		}
	}	

	
	function removeTextBox( categoryId,counter,mode )
	{
		if( $('#catCustLoseFlag'+categoryId).val() != "" && parseInt($('#catCustLoseFlag'+categoryId).val()) == parseInt("0") )
		{
			$('#catCustLoseFlag'+categoryId).val( 1 );
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(lose_customization);
			return false;
		}
		
		// $('#rem-'+categoryId+'-'+counter).html( '' );
		var deletingImgName = BASE_PATH + "/images/social_media/loading.gif";
		$('#loadImg-'+categoryId+'-'+counter).html( '<img style="vertical-align:middle;width:15px;" src="' + deletingImgName + '" />' );
		$('#loadImg-'+categoryId+'-'+counter).show();

		$('#catBoxesChanged'+categoryId).val( 1 );
		
		if( $("#textbox-"+categoryId+'-'+counter).val().trim() != "" )
		{
			if( $('#imgVerified-'+categoryId+"-"+counter).length == 1 )
			{
				var src = $('#imgVerified-'+categoryId+"-"+counter).prop('src');
				var imageNameArr = src.split( "/" );
				var imageName = imageNameArr[(imageNameArr.length)-1];
				if( imageName == "manage_checking.png" )
				{
					if( $('#urlsDisplayCount'+categoryId).val() != "" )
					{
						if( parseInt($('#urlsDisplayCount'+categoryId).val()) >= parseInt("1") )
						{
							$('#urlsDisplayCount'+categoryId).val( parseInt($('#urlsDisplayCount'+categoryId).val()) - parseInt("1") );
							$('#totalUrlsSpan'+categoryId).html( "<h5>" + parseInt($('#urlsDisplayCount'+categoryId).val()) + "/100</h5>" );
						}
					}
				}
			}
		}
		$('#verifyImg-'+categoryId+'-'+counter).html('');

		var deleteUrl = BASE_URL + "/databox/delete-url";
		var ticmarksCount=0;
		var invalidlinksCount=0;
		var loadingvisibleCount=0;
		$.ajax({
		  url: deleteUrl,
		  type: "POST",
		  data:{urlId:counter,categoryId:categoryId},
		  async: false,
		  success: function(data) {

			var ticmarksCount = 0;
			var invalidlinksCount = 0;
			var loadingvisibleCount = 0;
			$("input[id^='textbox']").each(function()
			{
				var idArray = this.id.replace("textbox-", "").split("-");
				if( parseInt(idArray[0]) == parseInt(categoryId) )
				{
					var textboxId = idArray[1];
					var value=$('#textbox-' + categoryId + '-' + textboxId).val().trim();

					if( value != "" )
					{
						if( $('#imgVerified-' + categoryId + '-' + textboxId).length == 1 )
						{
							var src = $('#imgVerified-' + categoryId + '-' + textboxId).prop('src');
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
						if( $('#imgVerified-' + categoryId + '-' + textboxId).length == 0 )
						{
							loadingvisibleCount++;
						}
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
			if( parseInt(mode) == parseInt("1") )
			{
				$("#textBoxDiv-"+categoryId+'-'+counter).remove();
			}
			$('#loadImg-'+categoryId+'-'+counter).hide();
			var loadingImgName = BASE_PATH + "/images/social_media/bx_loader.gif";
			$('#loadImg-'+categoryId+'-'+counter).html( '<img style="vertical-align:middle;width:15px;" src="' + loadingImgName + '" />' );
		  }
		});
	}
function displayUrlTotal( urlEntered,categoryId )
	{
		if( urlEntered != "" )
		{
			if( $('#urlsDisplayCount'+categoryId).val() != "" )
			{
				$('#totalUrlsSpan'+categoryId).html( "<h5>" + parseInt($('#urlsDisplayCount'+categoryId).val()) + "/100</h5>" );
			}
		}

		return false;
	}
function verifySingleUrl( categoryId,textboxId )
	{
		var allOkImgName = BASE_PATH + "/images/social_media/manage_checking.png";
		var invalidImgName = BASE_PATH + "/images/social_media/checkmark_wrong.png";

		var isValid = true;
		
		if( $('#imgVerified-' + categoryId + '-' + textboxId).length == 1 )
		{
			var src = $('#imgVerified-' + categoryId + '-' + textboxId).prop('src');
			var imageNameArr = src.split( "/" );
			var imageName = imageNameArr[(imageNameArr.length)-1];
			if( imageName == "manage_checking.png" )
			{
				return;
			}
		}

		$('#loadImg-' + categoryId + '-' + textboxId).show();

		$('#textbox-' + categoryId + '-' + textboxId).attr('readonly', true);

		var value=$('#textbox-' + categoryId + '-' + textboxId).val().trim();
		
		if( value == "" )
		{
			$('#textbox-' + categoryId + '-' + textboxId).attr('readonly', false);
			$('#loadImg-' + categoryId + '-' + textboxId).hide();
			$('#verifyImg-' + categoryId + '-' + textboxId).html('<img id="imgVerified-' + categoryId + '-' + textboxId + '" src="' + invalidImgName + '" />');
			return;
		}

		isValid = true;
		var attributesUrl = BASE_URL + "/databox/check-attributes";
		
		$.ajax({
		  url: attributesUrl,
		  type: "POST",
		  data:{currentUrl:value,urlId:textboxId,categoryId:categoryId},
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
				$('#textbox-' + categoryId + '-' + textboxId).attr('readonly', false);
				$('#loadImg-' + categoryId + '-' + textboxId).hide();
				$('#verifyImg-' + categoryId + '-' + textboxId).html('<img id="imgVerified-' + categoryId + '-' + textboxId + '" src="' + invalidImgName + '" />');
				return;
			}

			if( isValid )
			{
				$('#textbox-' + categoryId + '-' + textboxId).attr('readonly', false);
				$('#loadImg-' + categoryId + '-' + textboxId).hide();
				$('#verifyImg-' + categoryId + '-' + textboxId).html('<img id="imgVerified-' + categoryId + '-' + textboxId + '" src="' + allOkImgName + '" />');

				if( $('#urlsDisplayCount'+categoryId).val() != "" )
				{
					$('#urlsDisplayCount'+categoryId).val( parseInt($('#urlsDisplayCount'+categoryId).val()) + parseInt("1") );
				}

				displayUrlTotal( value,categoryId );
			}
		  }
		});
	
	}
function validateLinks( categoryId )
	{
		$("input[id^='textbox']").each(function()
		{
			var idArray = this.id.replace("textbox-", "").split("-");
			if( parseInt(idArray[0]) == parseInt(categoryId) )
			{
				var textboxId = idArray[1];
				var value=$('#textbox-' + categoryId + '-' + textboxId).val().trim();
				if( value != "" )
				{
					if( ($('#imgVerified-' + categoryId + '-' + textboxId).length == 0) && ($('#loadImg-' + categoryId + '-' + textboxId).is(':hidden')) )
					{
						verifySingleUrl( categoryId,textboxId );
					}
				}
			}
		});


		var allVerified = true;
		var allValid = true;
		$("input[id^='textbox']").each(function()
		{
			var idArray = this.id.replace("textbox-", "").split("-");
			if( parseInt(idArray[0]) == parseInt(categoryId) )
			{
				var textboxId = idArray[1];
				var value=$('#textbox-' + categoryId + '-' + textboxId).val().trim();
				if( value != "" )
				{
					if( $('#imgVerified-' + categoryId + '-' + textboxId).length == 0 )
					{
						allVerified = false;
						return;
					}
					else
					{
						var src = $('#imgVerified-' + categoryId + '-' + textboxId).prop('src');
						var imageNameArr = src.split( "/" );
						var imageName = imageNameArr[(imageNameArr.length)-1];
						if( imageName != "manage_checking.png" )
						{
							allValid = false;
							return;
						}
					}
				}
			}
		});
		
		if( ! allVerified )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(not_alllinks_verifying);
			return 0;
		}
		if( ! allValid )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(invalid_delete_links);
			return 0;
		}

		var minimumLinksCount = 0;
		$("input[id^='textbox']").each(function()
		{
			var idArray = this.id.replace("textbox-", "").split("-");
			if( parseInt(idArray[0]) == parseInt(categoryId) )
			{
				var textboxId = idArray[1];
				var value=$('#textbox-' + categoryId + '-' + textboxId).val().trim();
				if( value != "" )
				{
					if( $('#imgVerified-' + categoryId + '-' + textboxId).length == 1 )
					{
						var src = $('#imgVerified-' + categoryId + '-' + textboxId).prop('src');
						var imageNameArr = src.split( "/" );
						var imageName = imageNameArr[(imageNameArr.length)-1];
						if( imageName == "manage_checking.png" )
						{
							minimumLinksCount++;
						}
						if( parseInt(minimumLinksCount) == parseInt("5") )
						{
							return;
						}
					}
				}
			}
		});
		if( parseInt(minimumLinksCount) < parseInt("5") )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(minimum_valid_links);
			return 0;
		}
		return 1;		
	}