function checkDataboxesCount()
{
	returnValue = 0;	
	var checkDataboxesCountUrl = $('#baseUrl').val() + "/databox/check-databoxes-count";
	$.ajax({
	  url: checkDataboxesCountUrl,
	  type: "POST",
	  data:{},
	  async: false,
	  success: function(data) {
		if( parseInt(data.databoxesLimitReached) == parseInt("1") )
		{
			returnValue = 1;
		}
	  },
	  error: function(){
	  }
	});	
	return returnValue;
}
function checkCatChoiceSpaces()
{
	var hashtagHolder=$.trim( stripHtmlTags($("#hashTag").val()).replace(/&nbsp;/g,'') );
	$("#hashTag").val( hashtagHolder );
	if( hashtagHolder != "" )
	{
		hashtagHolder = $("#hashTag").val().replace(/^\s+/, "");
		hashtagHolder = hashtagHolder.replace(/\s+$/, "");
		var spc = " ";
		if( parseInt(hashtagHolder.indexOf(spc)) > parseInt("-1") )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(space_hash);
			window.setTimeout(function(){$("#hashTag").focus();}, 100);
			return false;
		}
	}
	return false;
}
function predefinedCategory( categoryName )
{
	if( parseInt(checkDataboxesCount()) == parseInt( "1" ) )
	{
		$('.alert_message').html('<b>Maximum Limit of 10 databoxes reached.</b>');
		$('.alert_message').show().delay(4000).fadeOut();
		return false;
	}
	$("#categoryName").val( categoryName );
	var baseUrl = $("#baseUrl").val();
	$("#predefinedCats").attr( "action", baseUrl + "/databox/predefined-both" );
	$("#predefinedCats").submit();
}

$(document).ready(function()
{
	$('.new_Btn').bind("click" , function (){
		$('#bookmarksFile').click();
	});

	$('#bookmarksFile').change(function()
	{
		if( ! checkBkmrksUploaded() )
		{
			return false;
		}
		readBookMarksFile( this.files[0],function(e)
		{
			if( isBkmrsFileValid(e.target.result) )
			{
				var filename = $('#bookmarksFile').val();
				$('#filename').html(filename);
			}
			else
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(invalid_bkmrks_file);
				$('#bookmarksFile').value='';
				$('#filename').html("");
				return false;
			}
		});
	});	
	$('#maxCountLink').click(function()
	{
		$('#pop-up-max-count').popUpWindow({action: "close"});
	});

	$("#userCatForm").submit(function(event)
	{
		var submitUserCatForm = true;		
		if( checkDataboxesCount() )
		{
			$('.alert_message').html('<b>Maximum Limit of 10 databoxes reached.</b>');
			$('.alert_message').show().delay(4000).fadeOut();
			submitUserCatForm = false;
		}
		if( ! submitUserCatForm )
		{
			event.preventDefault();
			return false;
		}

		var filename = $('#bookmarksFile').val();
		var invalidExtension = false;
		if( $('#filename').html().trim() != "" )
		{
			var fnameArray = filename.split( "." );
			if( parseInt(fnameArray.length) > 0 )
			{
				if( (fnameArray[fnameArray.length-1] != "json") && (fnameArray[fnameArray.length-1] != "bak") )
				{
					invalidExtension = true;
				}
			}
			if( invalidExtension )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(bookmarks_valid_file);
				submitUserCatForm = false;
			}
		}
		if( ! submitUserCatForm )
		{
			event.preventDefault();
			return false;
		}
		
		var hashTag=$.trim( stripHtmlTags($("#hashTag").val()).replace(/&nbsp;/g,'') );
		$("#hashTag").val( hashTag );
		if( hashTag == "" )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(chk_hash_tag);
			$("#hashTag").focus();
			submitUserCatForm = false;
		}
		if( ! submitUserCatForm )
		{
			event.preventDefault();
			return false;
		}
		var invalidHtCharacters = /[^a-z^A-Z^0-9^#]/;
		if( invalidHtCharacters.test( hashTag ) )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(chk_valid_hash_tag);
			$("#hashTag").focus();
			submitUserCatForm = false;
		}
		if( ! submitUserCatForm )
		{
			event.preventDefault();
			return false;
		}
		var hashCount = (hashTag.match(/#/g) || []).length;
		if( parseInt(hashCount) > parseInt("1") || ((parseInt(hashCount) == parseInt("1")) && (hashTag.charAt(0) != "#")) )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(hash_tag_allowed);
			$("#hashTag").focus();
			submitUserCatForm = false;
		}
		if( ! submitUserCatForm )
		{
			event.preventDefault();
			return false;
		}
		if( hashTag.charAt(0) != "#" )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(leading_hash);
			$("#hashTag").focus();
			submitUserCatForm = false;
		}
		if( ! submitUserCatForm )
		{
			event.preventDefault();
			return false;
		}		

		var mainCat=$.trim( stripHtmlTags($("#main_category").val()).replace(/&nbsp;/g,'') );
		$("#main_category").val( mainCat );
		/*
		if( mainCat == "" )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(chk_cat_name);
			$('#main_category').focus();
			submitUserCatForm = false;
		}
		if( ! submitUserCatForm )
		{
			event.preventDefault();
			return false;
		}
		*/

		var subCat=$.trim( stripHtmlTags($("#sub_category").val()).replace(/&nbsp;/g,'') );
		$("#sub_category").val( subCat );
		/*
		if( subCat == "" )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(chk_sub_cat_name);
			$('#sub_category').focus();
			submitUserCatForm = false;
		}
		if( ! submitUserCatForm )
		{
			event.preventDefault();
			return false;
		}		
		*/

		var userCatName=$.trim( stripHtmlTags($("#userCatName").val()).replace(/&nbsp;/g,'') );
		$("#userCatName").val( userCatName );
		/*
		if( userCatName == "" )
		{		
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(chk_cat_name);
			$('#userCatName').focus();
			submitUserCatForm = false;
		}
		if( ! submitUserCatForm )
		{
			event.preventDefault();
			return false;
		}
		*/

		var baseUrl = $("#baseUrl").val();
		var userCatFormUrl = baseUrl;
		if( $('#filename').html().trim() == "" )
		{
			userCatFormUrl += "/databox/userdefined-both";
		}
		else
		{
			userCatFormUrl += "/databox/userdefined-bookmarks";
		}
		$("#userCatForm").attr( "action",userCatFormUrl  );		
	});
});
