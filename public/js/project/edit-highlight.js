function cStyleSet()
	{
		var highlightBothUrl = $('#baseUrl').val();
		var settingId = $('#custTypeId').val();
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
			highlightBothUrl += "/databox/edit-highlight-ascending";
		}
		
		$('#linksForm').attr( "action",highlightBothUrl );

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

		$('#highlightImageFile').hide();

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

		$("input[id^='textbox']").each(function()
		{
			var textboxId = parseInt(this.id.replace("textbox", ""));

			var newBoxHandler = function(event)
			{
				var keyCode = event.which;
				if( parseInt(keyCode) != parseInt("32") )
				{
					$("#textbox"+textboxId).off( "keypress",newBoxHandler );

					var maxId = 0;
					$("input[id^='textbox']").each(function()
					{
						var textboxId1 = parseInt(this.id.replace("textbox", ""));
						if( parseInt(textboxId1) > parseInt(maxId) )
						{
							maxId = textboxId1;
						}
					});
					
					if( parseInt(textboxId) == parseInt(maxId) )
					{
						addTextBox(textboxId,1);
					}
					else if( $("#originalValue").val() == "" )
					{
						var removeUrlImgName = $('#basePath').val() + "/images/social_media/delete.png";
						$('#rem'+textboxId).html('<a href="javascript:void(0);" onClick="removeTextBox('+textboxId+',1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				}
			}
			$('#textbox'+textboxId).focus(function(event)
			{
				$("#textbox"+textboxId).on( "keypress",newBoxHandler );
				$("#originalValue").val($("#textbox"+textboxId).val().trim());
			});
			
			if( navigator.userAgent.toLowerCase().indexOf('chrome') > -1 )
			{
				$("#textbox"+textboxId).bind('paste', function(){
					$("#textbox"+textboxId).off( "keypress",newBoxHandler );

					var maxId = 0;
					$("input[id^='textbox']").each(function()
					{
						var textboxId2 = parseInt(this.id.replace("textbox", ""));
						if( parseInt(textboxId2) > parseInt(maxId) )
						{
							maxId = textboxId2;
						}
					});
					
					if( parseInt(textboxId) == parseInt(maxId) )
					{
						addTextBox(textboxId,1);
					}
					else if( $("#originalValue").val() == "" )
					{
						var removeUrlImgName = $('#basePath').val() + "/images/social_media/delete.png";
						$('#rem'+textboxId).html('<a href="javascript:void(0);" onClick="removeTextBox('+textboxId+',1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				});
			}
		});

		
		$('#catImageLink0').click(function(event)
		{
			// crop_value_countt=0;
			// $('#highlightImageFile').click();
			$('#crop_heigh').removeClass('cropit-image-preview');
			$('#crop_heigh').addClass('cropit-image-preview-heigh');
			$('#pop-up-image-crop-new').popUpWindow({action: "open"});
		});
		//OLD CROP
		
		$('#highlightImageFile').change(function(click)
		{
			// $('#hltImageSelected').val( 1 );
			// setHighlightImage(this,$('#userCategoryId').val());
		});
		function setHighlightImage(input,value){
			$('#hltImageSelected').val( 1 );
			var crop_width=0;
			var crop_height=0;
			var file, img;
			if ((file = input.files[0])) {
				img = new Image();
				img.src = _URL.createObjectURL(file);
				img.onload = function() {
					crop_width=this.width;
					crop_height=this.height;
					cropImageEditHigh(crop_width,crop_height,img.src,value);
				};
			}
		}
		function cropImageEditHigh(width,height,src,value){
			crop_value=value;
			if(width<=255 && height<=300){
				$('#uploadedCatImage0').attr('src', src);
			}else if(width>255 && height<=300){
				$('#uploadedCatImage0').attr('src', src);
			}else if(width<=255 && height>300){
				$('#uploadedCatImage0').attr('src', src);
			}else{
				$('.resize-image').attr('src', src);
				$.getScript( BASE_PATH+"/js/cropjs/jquery-2.1.1.min.js" ).done(function( script, textStatus ) {
					$.getScript( BASE_PATH+"/js/cropjs/component.js" ).done(function( script, textStatus ) {
						$('#pop-up-image-crop').css('display','block');
					});
				});
			}
		}
		

		$('#ascendingLink').mousedown(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			var disabledHorizontalImage = BASE_PATH + "/images/social_media/horizental_disabled.png";
			$('#horizontalImage').prop( 'src',disabledHorizontalImage );
			var enabledAscendingImage = BASE_PATH + "/images/social_media/ascending.png";
			$('#ascendingImage').prop( 'src',enabledAscendingImage );
			$('#custTypeId').val( 3 );
		});
		$('#horizontalLink').mousedown(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			var disabledAscendingImage = BASE_PATH + "/images/social_media/assending_disabled.png";
			$('#ascendingImage').prop( 'src',disabledAscendingImage );
			var enabledHorizontalImage = BASE_PATH + "/images/social_media/horizental.png";
			$('#horizontalImage').prop( 'src',enabledHorizontalImage );
			$('#custTypeId').val( 2 );
		});
		$('#ascendingLink').click(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			$('#custTypeId').val( 3 );
		});
		$('#horizontalLink').click(function(event)
		{
			if( ($('#submitVal').val() ==  "CStyle") && (parseInt($('#custTypeId').val()) > parseInt("0")) )
			{
				return false;
			}
			$('#custTypeId').val( 2 );
		});


		$('#highlightsContinue').mousedown(function(event)
		{
			$('#submitClicked').val( 1 );
		});
		$('#highlightsContinue').click(function(event)
		{
			$('#dcHtmlDiv').hide();
			$('#dcInprogressDiv').show();

			validateLinks();
		});

		$('#pop-up-alerts').popUpWindow({action: "open"});	
		$("#alert_header_meassge").html('');
		$("#alert_meassage").html(high_lose_customization);
		
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
