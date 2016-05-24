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
// category-main
function copyDownHashtag()
	{
		var hashtagHolder = $("#hashtagHolder").val().trim();
		if( hashtagHolder != "" )
		{
			$("#privateHtDisplay").val( hashtagHolder );
			$("#publicHtDisplay").val( hashtagHolder );
		}		
		return false;
	}
function setPrivateCatImage(input)
	{
		var crop_width=0;
		var crop_height=0;
		var file, img;
		if ((file = input.files[0])) {
			img = new Image();
			img.src = _URL.createObjectURL(file);
			img.onload = function() {
				crop_width=this.width;
				crop_height=this.height;
				cropImagePredefinedBoth(crop_width,crop_height,img.src);
			};
		}
	}
function cropImagePredefinedBoth(width,height,src)
	{
		if(width<=255 && height<=300){
			$('#uploadedCatImage1').attr('src', src);
			$('#uploadedCatImage0').attr('src', src);
		}else if(width>255 && height<=300){
			$('#uploadedCatImage1').attr('src', src);
			$('#uploadedCatImage0').attr('src', src);
		}else if(width<=255 && height>300){
			$('#uploadedCatImage1').attr('src', src);
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
function predefinedBothCrop(src)
	{
		crop_value_count++;
		var fetchUploadedUrl = BASE_URL+'/upload-montage-image';
		$.ajax({
			type	: "POST",
			url		: fetchUploadedUrl,
			data	: { data: src,page:'predefined'},
			success: function(data){
				$('#pop-up-image-crop').css('display','none');
				$('#uploadedCatImage1').attr('src', src);
				$('#uploadedCatImage0').attr('src', src);
				$('#typeImageCrop').val(1);
				$('#imageId').val(data.imageId);
			}
		});
	}
function setPublicCatImage(input)
	{
		if (input.files && input.files[0])
		{
			var filerdr = new FileReader();
			filerdr.onload = function(e)
			{
				$('#uploadedCatImage1').attr('src', e.target.result);
				$('#uploadedCatImage0').attr('src', e.target.result);
			}
			filerdr.readAsDataURL(input.files[0]);
		}
	}
// Present not in use
function checkHashtag()
	{
		var publicHtHolder = $("#publicHtHolder").val().replace(/^\s+/, "");
		publicHtHolder = $("#publicHtHolder").val().replace(/\s+$/, "");
		if( publicHtHolder != "" )
		{
			$("#checkingHashtag").show();
			
			var hashtagUrl = $('#baseUrl').val() + "/databox/unique-hashtag";
			$.ajax({
			  url: hashtagUrl,
			  type: "POST",
			  data:{publicHtHolder:publicHtHolder},
			  async: false,
			  success: function(data) {
				if(data.output=='success')
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(is_available_hash);
					window.setTimeout(function(){$("#publicHtHolder").focus();}, 100);
					$("#checkingHashtag").hide();
					return false;
				}
				else
				{
					$("#checkingHashtag").hide();
					return false;
				}
			  }
			});
		}
		return false;
	}

function dbPrimary()
	{
		if( $("#hashtagHolder").length )
		{
			var hashtagHolder=$.trim( stripHtmlTags($("#hashtagHolder").val()).replace(/&nbsp;/g,'') );
			hashtagHolder = hashtagHolder.trim();
			$("#hashtagHolder").val( hashtagHolder );
			if( hashtagHolder == "" )
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
			var invalidHtCharacters = /[^a-z^A-Z^0-9^#]/;
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
		if( $('#categoryType').val() != "" )
		{
			var categoryType = $('#categoryType').val();
			if( parseInt(categoryType) == parseInt("0") )
			{
				/*
				var src = $('#uploadedCatImage0').prop('src');
				var imageNameArr = src.split( "/" );
				var imageName = imageNameArr[(imageNameArr.length)-1];
				if( imageName == "profile_bookmark.png" )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(category_imgae);
					return false;
				}
				*/
				var privateTitleHolder=$.trim( stripHtmlTags($("#privateTitleHolder").val()).replace(/&nbsp;/g,'') );
				privateTitleHolder = privateTitleHolder.trim();
				$("#privateTitleHolder").val( privateTitleHolder );
				if( privateTitleHolder == "" )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(category_title);
					$("#privateTitleHolder").focus();
					return false;
				}
				var invalidTitleCharacters = /[^a-z^A-Z^0-9^ ]/;
				if( invalidTitleCharacters.test( privateTitleHolder ) )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(category_chk_title);
					$("#privateTitleHolder").focus();
					return false;
				}
				$('#catTitle').val( $('#privateTitleHolder').val() );
			}
			else if( parseInt(categoryType) == parseInt("1") )
			{
				/*
				var src = $('#uploadedCatImage1').prop('src');
				var imageNameArr = src.split( "/" );
				var imageName = imageNameArr[(imageNameArr.length)-1];
				if( imageName == "profile_bookmark.png" )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(category_select_imgae);
					return false;
				}
				*/

				var publicTitleHolder=$.trim( stripHtmlTags($("#publicTitleHolder").val()).replace(/&nbsp;/g,'') );
				publicTitleHolder = publicTitleHolder.trim();
				$("#publicTitleHolder").val( publicTitleHolder );
				var publicTitleHolder = $("#publicTitleHolder").val().trim();
				if( publicTitleHolder == "" )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(category_title);
					$("#publicTitleHolder").focus();
					return false;
				}
				var invalidTitleCharacters = /[^a-z^A-Z^0-9^ ]/;
				if( invalidTitleCharacters.test( publicTitleHolder ) )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(category_chk_title);
					$("#publicTitleHolder").focus();
					return false;
				}
				$('#catTitle').val( $('#publicTitleHolder').val() );
			}
		}		
		return true;
	}
// setting-validations
function mirrorTitle( categoryType )
	{
		if( parseInt(categoryType) == parseInt("0") )
		{
			$('#privateTitleHolder').val($('#publicTitleHolder').val());
		}
		if( parseInt(categoryType) == parseInt("1") )
		{
			$('#publicTitleHolder').val($('#privateTitleHolder').val());
		}
		return false;
	}
function mirrorPvtPub( element )
	{
		if( element.id == "mcPrivate" )
		{
			$('#mcPublic').prop( 'checked',$("#mcPrivate").is(":checked") ) ;
			return false;
		}
		else if( element.id == "mcPublic" )
		{
			$('#mcPrivate').prop( 'checked',$("#mcPublic").is(":checked") ) ;
			return false;
		}
		else if( element.id == "nsfwPrivate" )
		{
			$('#nsfwPublic').prop( 'checked',$("#nsfwPrivate").is(":checked") ) ;
			return false;
		}
		else if( element.id == "nsfwPublic" )
		{
			$('#nsfwPrivate').prop( 'checked',$("#nsfwPublic").is(":checked") ) ;
			return false;
		}
		else if( element.id == "lpf1" )
		{
			$('#lpf3').prop( 'checked',$("#lpf1").is(":checked") ) ;
			return false;
		}
		else if( element.id == "lpf3" )
		{
			$('#lpf1').prop( 'checked',$("#lpf3").is(":checked") ) ;
			return false;
		}
		else if( element.id == "lpf2" )
		{
			$('#lpf4').prop( 'checked',$("#lpf2").is(":checked") ) ;
			return false;
		}
		else if( element.id == "lpf4" )
		{
			$('#lpf2').prop( 'checked',$("#lpf4").is(":checked") ) ;
			return false;
		}

		return false;
	}
function updatePostsFormation( categoryType )
	{
		if( parseInt(categoryType) == parseInt("0") )
		{
			if( $("#lpf1").is( ":checked" ) )
			{
				setPostsFormation( 1 );
			}
			else if( $("#lpf2").is( ":checked" ) )
			{
				setPostsFormation( 2 );
			}
		}
		else if( parseInt(categoryType) == parseInt("1") )
		{
			if( $("#lpf3").is( ":checked" ) )
			{
				setPostsFormation( 1 );
			}
			else if( $("#lpf4").is( ":checked" ) )
			{
				setPostsFormation( 2 );
			}
		}
		
		return false;
	}
function setPostsFormation( newValue )
	{
		$('#linkPostFormation').val( newValue );
		return false;
	}
function matureContentClicked( checkedStatus )
	{
		if( checkedStatus )
		{
			$('#matureContent').val( 1 );
		}
		else
		{
			$('#matureContent').val( 0 );
		}
		
		return false;
	}

function setMatureContent( categoryType )
	{
		if( parseInt(categoryType) == parseInt("0") )
		{
			matureContentClicked( $("#mcPrivate").is( ":checked" ) );
		}
		else if( parseInt(categoryType) == parseInt("1") )
		{
			matureContentClicked( $("#mcPublic").is( ":checked" ) );
		}
		return false;
	}
function nsfwContentClicked( checkedStatus )
	{
		if( checkedStatus )
		{
			$('#notSafeForWork').val( 1 );
		}
		else
		{
			$('#notSafeForWork').val( 0 );
		}
		return false;
	}
function setNotSafeForWork( categoryType )
	{
		if( parseInt(categoryType) == parseInt("0") )
		{
			nsfwContentClicked( $("#nsfwPrivate").is( ":checked" ) );
		}
		else if( parseInt(categoryType) == parseInt("1") )
		{
			nsfwContentClicked( $("#nsfwPublic").is( ":checked" ) );
		}
		return false;
	}

	function publicDataBox()
	{
		var metaTagsHolder=$.trim( stripHtmlTags($('#metaTagsHolder').val()).replace(/&nbsp;/g,'') );
		$('#metaTagsHolder').val( metaTagsHolder );
		$('#metaTags').val( metaTagsHolder );
		return true;
	}

	function privateDataBox()
	{
		$('#uniqueCode').val( $('#ucHolder').val() );
		return true;
	}
// links-common	
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
		var loadingImgName = BASE_PATH + "/img/bx_loader.gif";
		var removeUrlImgName = BASE_PATH + "/img/dashboard_delete.png";

		$('#submitVal').val( "Go" );

		$('#rem'+count).html('<a href="javascript:void(0);" onClick="removeTextBox('+count+',1)"><img src="' + removeUrlImgName + '" /></a>');

		var counter=count+1;
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'textBoxDiv'+counter);
		
		var newTextBoxDivHtml = "";

		newTextBoxDivHtml += '<input type="text" class="inptLinks" placeholder="Ex:- http://www.google.co.in" autocomplete="on" id="textbox'+counter+'" onBlur="resetUrlStatus('+counter+')" />';
		newTextBoxDivHtml += '&nbsp;<span style="display:none" id="loadImg'+counter+'"><img style="vertical-align:middle;width:15px;" src="' + loadingImgName + '" /></span>';
		newTextBoxDivHtml += '<span id="verifyImg'+counter+'"></span>';
		newTextBoxDivHtml += '&nbsp;<b id="rem'+counter+'"></b>';

		newTextBoxDiv.html( newTextBoxDivHtml );

		newTextBoxDiv.appendTo("#textBoxesGroup");

		if( $("#formUseMode").length )
		{
			var formUseMode = $('#formUseMode').val();
			if( parseInt(formUseMode) == parseInt("1") )
			{
				$('#textBoxDiv'+counter).addClass("divCreatePublicDataboxLinks");
			}
			else if( parseInt(formUseMode) == parseInt("2") )
			{
				$('#textBoxDiv'+counter).addClass("divCreatePrivateDataboxLinks");
			}
			else if( parseInt(formUseMode) == parseInt("3") )
			{
				$('#textBoxDiv'+counter).addClass("divCreateHighlightLinks");
			}
		}
		
		var totalUrlsPresent = 0;
		$("input[id^='textbox']").each(function()
		{
			totalUrlsPresent++;
		});
		$("#linksCountPlus").html( parseInt(totalUrlsPresent - parseInt(1)) +' Links');

		// if( parseInt(totalUrlsPresent) == parseInt("100") )
		// {
			// $('#pop-up-max-links').popUpWindow({action: "open"});
		// }
		// else
		// {
			var newBoxHandler = function(event)
			{
				var keyCode = event.which;
				if( parseInt(keyCode) != parseInt("32") )
				{
					$("#textbox"+counter).off( "keypress",newBoxHandler );

					var maxId = 0;
					$("input[id^='textbox']").each(function()
					{
						var textboxId1 = parseInt(this.id.replace("textbox", ""));
						if( parseInt(textboxId1) > parseInt(maxId) )
						{
							maxId = textboxId1;
						}
					});
					
					if( parseInt(counter) == parseInt(maxId) )
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

					var maxId = 0;
					$("input[id^='textbox']").each(function()
					{
						var textboxId1 = parseInt(this.id.replace("textbox", ""));
						if( parseInt(textboxId1) > parseInt(maxId) )
						{
							maxId = textboxId1;
						}
					});
					
					if( parseInt(counter) == parseInt(maxId) )
					{
						addTextBox(counter,1);
					}
					else if( $("#originalValue").val() == "" )
					{
						$('#rem'+counter).html('<a href="javascript:void(0);" onClick="removeTextBox('+counter+',1)"><img src="' + removeUrlImgName + '" /></a>');
					}
				});
			}
		// }
		
		if( parseInt(useMode) == parseInt("0") )
		{
			verifySingleUrl( count );
		}
	}	
function removeTextBox( counter,mode )
	{
		$('#rem'+counter).html( '' );
		var deletingImgName = BASE_PATH + "/images/social_media/loading.gif";
		$('#loadImg'+counter).html( '<img style="vertical-align:middle;width:15px;" src="' + deletingImgName + '" />' );
		$('#loadImg'+counter).show();
		
		if( $("#textbox" + counter).val().trim() != "" )
		{
			if( $('#imgVerified' + counter).length == 1 )
			{
				var src = $('#imgVerified' + counter).prop('src');
				var imageNameArr = src.split( "/" );
				var imageName = imageNameArr[(imageNameArr.length)-1];
				if( imageName == "manage_checking.png" )
				{
					if( $('#urlsDisplayCount').val() != "" )
					{
						if( parseInt($('#urlsDisplayCount').val()) >= parseInt("1") )
						{
							$('#urlsDisplayCount').val( parseInt($('#urlsDisplayCount').val()) - parseInt("1") );
							// $('#totalUrlsSpan').html( "<strong>" + parseInt($('#urlsDisplayCount').val()) + "/100</strong>" );
						}
					}
				}
			}
		}
		$('#verifyImg'+counter).html('');

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
						if( imageName == "manage_checking.png" )
						{
							ticmarksCount++;
							$("#linksCountPlus").html(ticmarksCount+' Links');
						}
						if( imageName == "checkmark_wrong.png" ){
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
			var loadingImgName = BASE_PATH + "/img/bx_loader.gif";
			$('#loadImg'+counter).html( '<img style="vertical-align:middle;width:15px;" src="' + loadingImgName + '" />' );
		  }
		});
	}
function displayUrlTotal( urlEntered )
	{
		if( urlEntered != "" )
		{
			if( $('#urlsDisplayCount').val() != "" )
			{
				// $('#totalUrlsSpan').html( "<strong>" + parseInt($('#urlsDisplayCount').val()) + "/100</strong>" );
			}
		}

		return false;
	}

function verifySingleUrl( textboxId )
	{
		
		var loadingvisibleCount1=0;
		var allOkImgName = BASE_PATH + "/img/manage_checking.png";
		var invalidImgName = BASE_PATH + "/img/checkmark_wrong.png";

		var isValid = true;
		
		if( $('#imgVerified' + textboxId).length == 1 )
		{
			var src = $('#imgVerified' + textboxId).prop('src');
			var imageNameArr = src.split( "/" );
			var imageName = imageNameArr[(imageNameArr.length)-1];
			if( imageName == "manage_checking.png" )
			{
				return;
			}
		}
		if( $('#imgVerified' + textboxId).length == 0 )
		{
			loadingvisibleCount1++;
		}
		$('#loadImg'+textboxId).show();

		$('#textbox'+textboxId).attr('readonly', true);
		
		// alert(textboxId);return false;
		// alert($('#textbox'+textboxId).length);return false;

		var value=$('#textbox'+textboxId).val().trim();
		
		if( value == "" )
		{
			$('#textbox'+textboxId).attr('readonly', false);
			$('#loadImg'+textboxId).hide();
			$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" />');
			return;
		}
		isValid = true;
		var attributesUrl = BASE_URL + "/databox/check-attributes";
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
				var addFocusId=	parseInt(textboxId)+parseInt("1");			
				$('#textbox'+addFocusId).focus();
				$('#textbox'+textboxId).attr('readonly', false);
				$('#loadImg'+textboxId).hide();
				$('#verifyImg'+textboxId).html('<img id="imgVerified' + textboxId + '" src="' + invalidImgName + '" />');
				return;
			}

			if( isValid )
			{
				var addFocusId=	parseInt(textboxId)+parseInt("1");			
				$('#textbox'+addFocusId).focus();
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
// present not in use
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

		isValid = true;
		var attributesUrl = $('#baseUrl').val() + "/databox/check-attributes";
				
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
		alert( "verifyAllUrls used." );
		
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

		isValid = true;
		var attributesUrl = $('#baseUrl').val() + "/databox/check-attributes";
				
		$.ajax({
		  url: attributesUrl,
		  type: "POST",
		  data:{currentUrl:value,urlId:textboxId},
		  async: false,
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
		});
		$("#links_cnt").val(minimumLinksCount);
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
			validatonsTzCall( submitVal );
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
					if($('#'+this.id).prop('checked')){
						$count=1;
						$("input[id^='main-']").each(function(){
							if($count!=1){
								if(!$('#'+this.id).prop('checked')){
									flag3=false;
									return;
								}
							}
							$count++;
						});
						if(flag3==true){
							$('#main-0').prop('checked',true);
						}else{
							$('#main-0').prop('checked',false);
						}
					}else{
						$('#main-0').prop('checked',false);
					}
				}else{
					var flag4=true;
					var flag5=true;
					var flag7=true;
					var flag6=true;
					if($('#'+this.id).prop('checked')){
						if(urlIdLength.length==4){
							$("input[id^='main-cat-"+urlIdLength[2]+"-']").each(function(){
								if(!$('#'+this.id).prop('checked')){
									flag5=false;
									return;
								}
							});
							if(flag5==true){
								$('#main-cat-'+urlIdLength[2]).prop('checked',true);
							}else{
								$('#main-cat-'+urlIdLength[2]).prop('checked',false);
							}
						}else if(urlIdLength.length==6){
							$("input[id^='main-cat-"+urlIdLength[2]+"-subcat-"+urlIdLength[4]+"-']").each(function(){
								if(!$('#'+this.id).prop('checked')){
									flag6=false;
									return;
								}
							});
							if(flag6==true){
								$('#main-cat-'+urlIdLength[2]+'-subcat-'+urlIdLength[4]).prop('checked',true);
							}else{
								$('#main-cat-'+urlIdLength[2]+'-subcat-'+urlIdLength[4]).prop('checked',false);
							}
							
							$("input[id^='main-cat-"+urlIdLength[2]+"-']").each(function(){
								if(!$('#'+this.id).prop('checked')){
									flag7=false;
									return;
								}
							});
							if(flag7==true){
								$('#main-cat-'+urlIdLength[2]).prop('checked',true);
							}else{
								$('#main-cat-'+urlIdLength[2]).prop('checked',false);
							}
						}
						$count1=1;
						$("input[id^='main-']").each(function(){
							if($count1!=1){
								if(!$('#'+this.id).prop('checked')){
									flag4=false;
									return;
								}
							}
							$count1++;
						});
						if(flag4==true){
							$('#main-0').prop('checked',true);
						}else{
							$('#main-0').prop('checked',false);
						}
					}else{
						$('#main-0').prop('checked',false);
						if(urlIdLength.length==4){
							$('#main-cat-'+urlIdLength[2]).prop('checked',false);
						}else if(urlIdLength.length==6){
							$('#main-cat-'+urlIdLength[2]).prop('checked',false);
							$('#main-cat-'+urlIdLength[2]+'-subcat-'+urlIdLength[4]).prop('checked',false);
						}
					}
				}
			}else{
				var valueLength=$('#'+this.id).val().split('-');
				if(valueLength.length==1){
					if($('#'+this.id).prop('checked')){
						$("input[id^='main-']").each(function(){
							$('#'+this.id).prop('checked',true);
						});
					}else{
						$("input[id^='main-']").each(function(){
							$('#'+this.id).prop('checked',false);
						});
					}
				}else if(valueLength.length==2 || valueLength.length==3){
					var flag2=true;
					var flag1=true;
					if($('#'+this.id).prop('checked')){
						$("input[id^='"+this.id+"-']").each(function(){
							$('#'+this.id).prop('checked',true);
						});
						if(valueLength.length==3){
							$("input[id^='main-cat-"+valueLength[2]+"-']").each(function(){
								if(!$('#'+this.id).prop('checked')){
									flag1=false;
									return;
								}
							});
							if(flag1==true){
								$('#main-cat-'+valueLength[2]).prop('checked',true);
							}else{
								$('#main-cat-'+valueLength[2]).prop('checked',false);
							}
						}
						$count2=1;
						$("input[id^='main-']").each(function(){
							if($count2!=1){
								if(!$('#'+this.id).prop('checked')){
									flag2=false;
									return;
								}
							}
							$count2++;
						});
						if(flag2==true){
							$('#main-0').prop('checked',true);
						}else{
							$('#main-0').prop('checked',false);
						}
					}else{
						$("input[id^='"+this.id+"-']").each(function(){
							$('#'+this.id).prop('checked',false);
						});
						if(valueLength.length==3){
							$('#main-cat-'+valueLength[2]).prop('checked',false);
						}
						$('#main-0').prop('checked',false);
					}
				}
			}
			//Get count of checked links
			var checkedCount=0;
			$("input[class^='uriClass']").each(function(){
				if($('#'+this.id).prop('checked')){
					checkedCount++;
				}					
			});
			$('#selectedTotalBM').html(checkedCount);
			//End
		});
	});
}
//End	
