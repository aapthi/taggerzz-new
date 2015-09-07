function cStyleSet()
	{
		var settingId = $('#custTypeId').val();
		var linksFormUrl = $('#baseUrl').val();
		if( parseInt($('#custTypeId').val()) == parseInt("0") )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(select_customize);
			$('#dcInprogressDiv').hide();
			$('#dcHtmlDiv').show();
			return false;
		}
		else if( (parseInt(settingId) == parseInt("2")) || (parseInt(settingId) == parseInt("3")) )
		{
			linksFormUrl += "/databox/display-ascending";
		}
		$('#linksForm').attr( "action",linksFormUrl );

		$("input[id^='textbox']").each(function()
		{
			var textboxId = parseInt(this.id.replace("textbox", ""));
			$('#textbox'+textboxId).attr('readonly', true);
			$('#rem'+textboxId).html( '' );
		});

		var allValid = true;
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
					if( imageName == "warning.png" )
					{
						allValid = false;
						$('#finalnvalidCount').val( parseInt($('#finalnvalidCount').val()) + parseInt("1") );
						return;
					}
					return;
				}
			}
		});
		
		if( ! allValid )
		{
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
						if( imageName == "warning.png" )
						{
							removeTextBox( textboxId,1 );
							return;
						}
						return;
					}
				}
			});
			return false;
		}

		$('#linksForm').submit();
	}
$(document).ready(function()
	{
		$('#bookmarksFile').hide();
		$('#uploadSubmitBtn').hide();

		$('#catImageFile').hide();
	
		refreshUcodeDatabox();
		
		$('#uploadLink').click(function(event)
		{		
			var loadingvisibleCountP=0;	
			$("input[id^='textbox']").each(function()
			{
				var textboxId = parseInt(this.id.replace("textbox", ""));
				var value=$('#textbox'+textboxId).val().trim();
				if( value != "" )
				{					
					if( $('#imgVerified' + textboxId).length == 0 )
					{
						loadingvisibleCountP++;
					}
				}
			});
			if(loadingvisibleCountP==0)
			{
				$('#bookmarksFile').click();
			}			
			else
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(upload_in_progress);
			}
		});


		$('#bookmarksFile').change(function(click)
		{
			if( ! checkBkmrksUploaded() )
			{
				return false;
			}
			
			readBookMarksFile( this.files[0],function(e)
			{
				if( isBkmrsFileValid(e.target.result) )
				{
					$('#uploadSubmitBtn').submit();
				}
				else
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(invalid_bkmrks_file);
					$('#bookmarksFile').value='';
					return false;
				}
			});
		});
		
		$('#uploadSubmitBtn').submit(function(event)
		{
			$("#bookmarksLoadingDiv").show();
			var fetchUploadedUrl = $('#baseUrl').val() + "/databox/fetch-uploadedurls";			
			var file_data = $('#bookmarksFile').prop('files')[0];
			var form_data = new FormData();
			form_data.append('file', file_data);
			$.ajax({
					url: fetchUploadedUrl,
					dataType: 'text',
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,                         
					type: 'post',
					success: function(data){
						$('#pop-up-bookmarks').html(data);
						$('#pop-up-bookmarks').popUpWindow({action: "open",
							onClose: function()
							{
								$('body').css('overflow','auto');
							}
						});
						$('.pop-up.large').css('max-width','80%');
						$('#pop-up-bookmarks').css('height','500px');
						$('.pop-up.large').css('left','30%');
						$('body').css('overflow','hidden');
						$('#pop-up-bookmarks').css('overflow','auto');
						bookMarksPopUrls();
						$("#bookmarksLoadingDiv").hide();
					}
				 });

		});

		$('#catImageLink0').click(function(event)
		{
			$('#pop-up-image-crop-new').popUpWindow({action: "open"});
		});
		$('#catImageLink1').click(function(event)
		{
			$('#pop-up-image-crop-new').popUpWindow({action: "open"});
		});

		$('#catImageFile').change(function(click)
		{
			// setPrivateCatImage(this);
		});
		
		$('#mailAccessDetails').click(function(event)
		{
			var hashTag = $('#catHashTag').val().trim();
			if( hashTag == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(category_hash_not_available);
				return false;
			}
			
			var uniqueCode = $('#ucHolder').val().trim();
			if( uniqueCode == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(cat_unique_code);
				return false;
			}

			var mailDetailsTo = $('#mailDetailsTo').val().trim();
			if( mailDetailsTo == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(no_email_access);
				$('#mailDetailsTo').focus();
				return false;
			}

			$('#pvtAccessDetailsDiv').hide();
			$('#sendingDetailsDiv').show();

			var categoryName = $('#categoryName').val().trim();

			var mailAccessDetailsUrl = $('#baseUrl').val() + "/databox/mail-access-details";
			$.ajax({
			  url: mailAccessDetailsUrl,
			  type: "POST",
			  data:{hashTag:hashTag,uniqueCode:uniqueCode,mailDetailsTo:mailDetailsTo,categoryName:categoryName},
			  async: false,
			  success: function(data) {
				if( parseInt(data) == parseInt("1") )
				{
					$('#sendingDetailsDiv').hide();
					$('#pvtAccessDetailsDiv').show();
					$('#tickImage').show();
					return false;
				}
				else
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(not_mailed);
					$('#sendingDetailsDiv').hide();
					$('#pvtAccessDetailsDiv').show();
					return false;
				}
			  }
			});
		});


		$('#ascendingLink0').mousedown(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			var disabledHorizontalImage = BASE_PATH + "/images/social_media/horizental_disabled.png";
			$('#horizontalImage0').prop( 'src',disabledHorizontalImage );
			$('#horizontalImage1').prop( 'src',disabledHorizontalImage );
			var enabledAscendingImage = BASE_PATH + "/images/social_media/ascending.png";
			$('#ascendingImage0').prop( 'src',enabledAscendingImage );
			$('#ascendingImage1').prop( 'src',enabledAscendingImage );
			$('#custTypeId').val( 3 );
		});
		$('#ascendingLink1').mousedown(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			var disabledHorizontalImage = BASE_PATH + "/images/social_media/horizental_disabled.png";
			$('#horizontalImage0').prop( 'src',disabledHorizontalImage );
			$('#horizontalImage1').prop( 'src',disabledHorizontalImage );
			var enabledAscendingImage = BASE_PATH + "/images/social_media/ascending.png";
			$('#ascendingImage0').prop( 'src',enabledAscendingImage );
			$('#ascendingImage1').prop( 'src',enabledAscendingImage );
			$('#custTypeId').val( 3 );
		});
		$('#horizontalLink0').mousedown(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			var disabledAscendingImage = BASE_PATH + "/images/social_media/assending_disabled.png";
			$('#ascendingImage0').prop( 'src',disabledAscendingImage );
			$('#ascendingImage1').prop( 'src',disabledAscendingImage );
			var enabledHorizontalImage = BASE_PATH + "/images/social_media/horizental.png";
			$('#horizontalImage0').prop( 'src',enabledHorizontalImage );
			$('#horizontalImage1').prop( 'src',enabledHorizontalImage );
			$('#custTypeId').val( 2 );
		});
		$('#horizontalLink1').mousedown(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			var disabledAscendingImage = BASE_PATH + "/images/social_media/assending_disabled.png";
			$('#ascendingImage0').prop( 'src',disabledAscendingImage );
			$('#ascendingImage1').prop( 'src',disabledAscendingImage );
			var enabledHorizontalImage = BASE_PATH + "/images/social_media/horizental.png";
			$('#horizontalImage0').prop( 'src',enabledHorizontalImage );
			$('#horizontalImage1').prop( 'src',enabledHorizontalImage );
			$('#custTypeId').val( 2 );
		});
		$('#ascendingLink0').click(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			$('#custTypeId').val( 3 );
		});
		$('#ascendingLink1').click(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			$('#custTypeId').val( 3 );
		});
		$('#horizontalLink0').click(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			$('#custTypeId').val( 2 );
		});
		$('#horizontalLink1').click(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			$('#custTypeId').val( 2 );
		});

		
		$('#databoxContinue').mousedown(function(event)
		{
			$('#submitClicked').val( 1 );
		});
		$('#databoxContinue').click(function(event)
		{
			if( $('#custTypeId').val() == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(select_customize);
				return false;
			}

			$('#dcHtmlDiv').hide();
			$('#dcInprogressDiv').show();

			$('#submitClicked').val( 1 );
			validateLinks();
		});

		if( $("#textbox1").length )
		{
			var newBoxHandler = function(event)
			{
				var keyCode = event.which;
				// console.log( "h" + keyCode);
				if( parseInt(keyCode) != parseInt("32") )
				{
					$("#textbox1").off( "keypress",newBoxHandler );

					var maxIdPresent = 0;
					$("input[id^='textbox']").each(function()
					{
						var textboxId = parseInt(this.id.replace("textbox", ""));
						if( parseInt(textboxId) > parseInt(maxIdPresent) )
						{
							maxIdPresent = textboxId;
						}
					});
					
					if( parseInt(maxIdPresent) == parseInt("1") )
					{
						addTextBox(1,1);
					}
					else if( $("#originalValue").val() == "" )
					{
						var removeUrlImgName = $('#basePath').val() + "/images/social_media/delete.png";
						$('#rem1').html('<a href="javascript:void(0);" onClick="removeTextBox(1,1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				}
			}
			$('#textbox1').focus(function(event)
			{
				$("#textbox1").on( "keypress",newBoxHandler );
				$("#originalValue").val($("#textbox1").val().trim());
			});
			
			if( navigator.userAgent.toLowerCase().indexOf('chrome') > -1 )
			{
				$("#textbox1").bind('paste', function(){
					$("#textbox1").off( "keypress",newBoxHandler );

					var maxIdPresent = 0;
					$("input[id^='textbox']").each(function()
					{
						var textboxId = parseInt(this.id.replace("textbox", ""));
						if( parseInt(textboxId) > parseInt(maxIdPresent) )
						{
							maxIdPresent = textboxId;
						}
					});
					
					if( parseInt(maxIdPresent) == parseInt("1") )
					{
						addTextBox(1,1);
					}
					else if( $("#originalValue").val() == "" )
					{
						var removeUrlImgName = $('#basePath').val() + "/images/social_media/delete.png";
						$('#rem1').html('<a href="javascript:void(0);" onClick="removeTextBox(1,1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				});
			}
		}

		$('#maxLinksLink').click(function()
		{
			$('#pop-up-max-links').popUpWindow({action: "close"});
		});
		
		
		$( "#private_btn" ).removeClass( "rem_active_btn" ).addClass( "active_btn" );
		$("#private_btn").click(function(){
			$( "#private_btn" ).removeClass( "rem_active_btn" ).addClass( "active_btn" );
			$( "#public_btn" ).removeClass( "act_public_btn" ).addClass( "public_btn" );
			$('#categoryType').val( 0 );
			mirrorTitle( 0 );
			$('#publicSVal').val( $('#submitVal').val() );
			$('#submitVal').val( $('#privateSVal').val() );
			$("#hideandshow_private").show();
			$("#hideandshow_public").hide();
		});
		$("#public_btn").click(function(){
			$( "#private_btn" ).removeClass( "active_btn" ).addClass( "rem_active_btn" );
			$( "#public_btn" ).removeClass( "public_btn" ).addClass( "act_public_btn" );
			$('#categoryType').val( 1 );
			mirrorTitle( 1 );
			$('#privateSVal').val( $('#submitVal').val() );
			$('#submitVal').val( $('#publicSVal').val() );
			$("#hideandshow_public").show();
			$("#hideandshow_private").hide();
		});
		
	});
function checkBookMarksUrls()
	{
		var selectedBookmarksArray = [];
		var urlId = 0;
		$("input[id^='main-']").each(function()
		{
			if( $('#'+this.id).attr('checked') )
			{
				if(/^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test($('#'+this.id).val()))
				{
					selectedBookmarksArray[urlId++] = $('#'+this.id).val();
				}
			}
		});
		
		if( parseInt(selectedBookmarksArray.length) == parseInt("0") )
		{
			alert(atleast_one_url);
			return false;
		}else if( parseInt(selectedBookmarksArray.length) > parseInt("100") )
		{
			alert(max_allowed_links);
			return false;
		}

		$('#pop-up-bookmarks').popUpWindow({action: "close"});
		$('body').css('overflow','auto');
		fillUploadedUrls( selectedBookmarksArray );
	}
function cancelBookMarksUrls()
	{
		$('#pop-up-bookmarks').popUpWindow({action: "close"});
		$('body').css('overflow','auto');
	}

