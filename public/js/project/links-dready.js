function validatonsTzCall( submitVal )
{
	if( ! validateSettings(submitVal) )
	{
		$('#dcInprogressDiv').hide();
		$('#dcHtmlDiv').show();
		return false;
	}
	
	return false;
}

function cStyleSet()
{
	var linksFormUrl = BASE_URL;
	linksFormUrl += "/databox/display-ascending";
	$('#linksForm').attr( "action",linksFormUrl );

	$("input[id^='textbox']").each(function()
	{
		var textboxId = parseInt(this.id.replace("textbox", ""));
		$('#textbox'+textboxId).attr('readonly', true);
		$('#rem'+textboxId).html( '' );
	});

	if( $("#hashtagHolder").length )
	{
		$("#hashtagHolder").attr('readonly', true);
	}
	if( $("#titleHolder").length )
	{
		$("#titleHolder").attr('readonly', true);
	}
	if( $("#boxCategory").length )
	{
		$("#boxCategory").attr('readonly', true);
	}
	if( $("#chkbxMc").length )
	{
		$("#chkbxMc").attr("disabled", true);
	}
	if( $("#chkbxNsfw").length )
	{
		$("#chkbxNsfw").attr("disabled", true);
	}

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
				if( imageName == "checkmark_wrong.png" )
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
					if( imageName == "checkmark_wrong.png" )
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
	
	// alert( "st1" );return false;
	
	return true;
}

function validateSettings( submitVal )
{
	if( submitVal == "Continue" )
	{
		if( $("#hashtagHolder").length )
		{
			var hashtagHolder=$.trim( stripHtmlTags($("#hashtagHolder").val()).replace(/&nbsp;/g,'') );
			hashtagHolder = hashtagHolder.trim();
			$("#hashtagHolder").val( hashtagHolder );
			if( hashtagHolder == "" || hashtagHolder == "#" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(chk_hash_tag);
				$("#hashtagHolder").focus();
				return false;
			}
			hashtagHolder = $("#hashtagHolder").val().replace(/^\s+/, "");
			hashtagHolder = hashtagHolder.replace(/\s+$/, "");
			var spc = " ";
			if( parseInt(hashtagHolder.indexOf(spc)) > parseInt("-1") )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(space_hash);
				$("#hashtagHolder").focus();
				return false;
			}
			//var invalidHtCharacters = /[^a-z^A-Z^0-9^#]/;
			var invalidHtCharacters = /[^a-z^A-Z^0-9^_^#]/;
			if( invalidHtCharacters.test( hashtagHolder ) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(chk_valid_hash_tag);
				$("#hashtagHolder").focus();
				return false;
			}
			var hashCount = (hashtagHolder.match(/#/g) || []).length;
			if( parseInt(hashCount) > parseInt("1") || ((parseInt(hashCount) == parseInt("1")) && (hashtagHolder.charAt(0) != "#")) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(hash_tag_allowed);
				$("#hashtagHolder").focus();
				return false;
			}
			if( hashtagHolder.charAt(0) != "#" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(leading_hash);
				$("#hashtagHolder").focus();
				return false;
			}
			$('#catHashTag').val( $('#hashtagHolder').val().trim() );
		}
		
		if( $("#titleHolder").length )
		{
			var titleHolder=$.trim( stripHtmlTags($("#titleHolder").val()).replace(/&nbsp;/g,'') );
			titleHolder = titleHolder.trim();
			$("#titleHolder").val( titleHolder );
			if( titleHolder == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(category_title);
				$("#titleHolder").focus();
				return false;
			}
			var invalidTitleCharacters = /[^a-z^A-Z^0-9^&^, ]/;
			if( invalidTitleCharacters.test( titleHolder ) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(category_chk_title);
				$("#titleHolder").focus();
				return false;
			}
			$('#catTitle').val( $('#titleHolder').val() );
		}
		
		if( $("#boxCategory").length )
		{
			var boxCategory=$.trim( stripHtmlTags($("#boxCategory").val()).replace(/&nbsp;/g,'') );
			boxCategory = boxCategory.trim();
			$("#boxCategory").val( boxCategory );
			if( boxCategory == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(box_category_empty);
				$("#boxCategory").focus();
				return false;
			}
			//var invalidTitleCharacters = /[^a-z^A-Z^0-9^ ]/;
			var invalidTitleCharacters = /[^a-z^A-Z^0-9^, ]/;
			
			if( invalidTitleCharacters.test( boxCategory ) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(box_category_invalid);
				$("#boxCategory").focus();
				return false;
			}
			$('#metaTags').val( $('#boxCategory').val() );
		}

		if( $("#ucHolder").length )
		{
			$('#uniqueCode').val( $('#ucHolder').val() );
		}

		$('#submitVal').val( "CStyle" );

		return cStyleSet();
	}
	
	return false;
}

$(document).ready(function()
{
	var editBoxId = $('#editBoxId').val();
	if( editBoxId != "" && parseInt(editBoxId) > 0 )
	{
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
						var removeUrlImgName = BASE_PATH + "/img/dashboard_delete.png";
						$('#rem1').html('<a href="javascript:void(0);" onClick="removeTextBox(1,1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				}
			}
			$("#textbox"+textboxId).focus(function(event)
			{
				$("#textbox"+textboxId).on( "keypress",newBoxHandler );
				$("#originalValue").val($("#textbox"+textboxId).val().trim());
			});
			
			if( navigator.userAgent.toLowerCase().indexOf('chrome') > -1 )
			{
				$("#textbox"+textboxId).bind('paste', function(){
					$("#textbox1").off( "keypress",newBoxHandler );

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
						var removeUrlImgName = BASE_PATH + "/img/dashboard_delete.png";
						$('#rem1').html('<a href="javascript:void(0);" onClick="removeTextBox(1,1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				});
			}
		});
	}
	else if( $("#textbox1").length )
	{
		if( $("#ucHolder").length )
		{
			refreshUcodeDatabox();			
		}
		
		var textboxId = 1;

		var newBoxHandler = function(event)
		{
			var keyCode = event.which;
			
			if( parseInt(keyCode) != parseInt("32") )
			{
				$("#textbox1").off( "keypress",newBoxHandler );

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
					addTextBox(1,1);
				}
				else if( $("#originalValue").val() == "" )
				{
					var removeUrlImgName = BASE_PATH + "/img/dashboard_delete.png";
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
					addTextBox(1,1);
				}
				else if( $("#originalValue").val() == "" )
				{
					var removeUrlImgName = BASE_PATH + "/img/dashboard_delete.png";
					$('#rem1').html('<a href="javascript:void(0);" onClick="removeTextBox(1,1)"><img src="' + removeUrlImgName + '" /></a>');
				}
			});
		}
	}

	if( $("#databoxContinue").length )
	{
		$('#databoxContinue').click(function(event)
		{
			$('#dcHtmlDiv').hide();
			$('#dcInprogressDiv').show();

			$('#submitClicked').val( 1 );
			validateLinks();
		});
	}
	
	if( $("#cropBoxImage").length )
	{
		$('#cropBoxImage').click(function(event)
		{
			if( $('#submitVal').val() == "CStyle" )
			{
				return false;
			}
			$('#pop-up-image-crop-new').popUpWindow({action: "open"});
			$('.pop-up-content').css('border','0');
			$('.close').css('right','0');
			$('.close').css('top','0');
		});
	}

	if( $("input[class^='uriClass']").length )
	{
		$('#pop-up-bookmarks').show();
		
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

var urlsLength = 0;

function checkBookMarksUrls()
	{
			var selectedBookmarksArray = [];
			var urlId = 0;
			$("input[id^='main-']").each(function()
			{
				if( $('#'+this.id).prop('checked') )
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
		}
		else if( parseInt(selectedBookmarksArray.length) > parseInt("50") )
		{
			 alert(max_allowed_links);
			 return false;
		}
		urlsLength = selectedBookmarksArray.length;
			
		$('#pop-up-bookmarks').popUpWindow({action: "close"});
		$('body').css('overflow','auto');
		fillUploadedUrls( selectedBookmarksArray );
	}
function cancelBookMarksUrls()
	{
		$('#pop-up-bookmarks').popUpWindow({action: "close"});
		$('body').css('overflow','auto');
	}
