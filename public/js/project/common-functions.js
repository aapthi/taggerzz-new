
$(document).keyup(function(e) {
  if (e.keyCode == 27){// esc
	if( $('.pop-up-close-trigger').length )
	{
		if($('#pop-up-1').is(':visible'))
		{
			$('#pop-up-1').popUpWindow({action: "close"});
		}
		else if($('#pop-up-comment').is(':visible'))
		{
			$('#pop-up-comment').popUpWindow({action: "close"});
		}
		else if($('#pop-up-image-crop-new').is(':visible'))
		{
			$('#pop-up-image-crop-new').popUpWindow({action: "close"});
		}
	}
  }
});

var searchVariable = 0;
function set_item(item) 
	{
		$('#searchTermHolder').val(item);
		$('#country_list_id').hide();
		if( $('#homeSearchLink').length )
		{
			
			
			$('#homeSearchLink').click();
		}
		if( $('#hgltsSearchLink').length )
		{
			$('#hgltsSearchLink').click();
		}
	}
function applyCss()
	{
		$('#'+searchVariable).css('background','#f5f5f5');
		$('#'+searchVariable).css('border-top','1px solid #dedede');
		$('#'+searchVariable).css('border-bottom','1px solid #dedede');
	}

function removeCss()
	{
		$('#'+searchVariable).css('background','');
		$('#'+searchVariable).css('border-top','');
		$('#'+searchVariable).css('border-bottom','');
	}
	
// header-menu
	$(document).on("mousedown", "a.tag_chk", function(e) {	
			if( e.which == 2 ) {
				var removeHashvalue=this.text.split('#')['0'].trim().toLowerCase();
				var hashMontage=this.text.trim().toLowerCase();
				 var hashNameMontage=hashMontage.charAt(0);
				if(removeHashvalue=='databox/my-collected-links'){
					var m_url= BASE_URL +'/databox/my-collected-links';
					var mm_url=	'/databox/my-collected-links';			
				}else if(removeHashvalue=='home'){
					var m_url= BASE_URL +'/';	
					var mm_url=	'/';
				}else if(removeHashvalue=='databoxuser/dashboard'){
					var m_url= BASE_URL +'/databoxuser/dashboard';
						var mm_url=	'/databoxuser/dashboard';	
				}else if(hashNameMontage=='#'){
					var m_url= BASE_URL +'/montage';
					var mm_url=	'/montage';	
				}else if(removeHashvalue=='accounts'){
					var m_url= BASE_URL +'/accounts';
					var mm_url=	'/accounts';	
				}else if(removeHashvalue=='taggerzz.com'){
					var m_url= BASE_URL +'/';	
					var mm_url=	'/';
				}else if(removeHashvalue=='databox/post-vertical'){
					var m_url= BASE_URL +'/databox/post-vertical';
						var mm_url=	'/databox/post-vertical';	
				}	
				if( chkvalues == 2 )
				{
					var urlmouse='mousedownEvent';
					$('#mousemiddle_event_menu').val(urlmouse);	
					$('.tag_chk').removeAttr('href');
					$('.tag_chk').css('cursor','pointer');
					 $('#confirm-pop-up-alerts').popUpWindow({action: "open"});	
					 $("#confirm_alert_header_meassge").html('');
					 $("#confirm_alert_meassage").html(confrim_verifying_urls_alert);
					 $('#menu_loc_url').val(mm_url);				
				}
				else if( chkvalues==0 )
				{	
					var menuUrlM= m_url;
					if(navigator.userAgent.toLowerCase().indexOf('chrome') > -1){
						window.open(m_url);
					}else{
						$('a.tag_chk').attr('href',m_url);
					}
				}
				else
				{
					$('.tag_chk').removeAttr('href');
					$('.tag_chk').css('cursor','pointer');
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(verifying_urls_alert);
				}	
			}else if( e.which == 1 ) {
				if( chkvalues == 2 )
				{
					var urlmouse='';
					$('#mousemiddle_event_menu').val(urlmouse);								
				}
			}
		});
		
	function processHinting( userId,hintingNewVal )
	{
		$.ajax({
			type		:	'POST',
			url			:  	BASE_URL+'/update-hinting',
			data		:	{userId:userId,hintingNewVal:hintingNewVal},
			success: function(data){
				/*
				if( parseInt(hintingNewVal) == parseInt("0") )
				{
					$('#userHintingStateSpan').html("off");
				}
				else if( parseInt(hintingNewVal) == parseInt("1") )
				{
					$('#userHintingStateSpan').html("on");
				}
				*/
				location.reload();
			}
		});
		return false;
	}
	
	function goToLocation( menuLocation )
	{
		//New Code
		
		if(menuLocation=="accounts"){
			window.location.href = BASE_URL +'/'+ menuLocation;
			return false;
		}else if(menuLocation=="montage"){
			window.location.href = BASE_URL +'/'+ menuLocation;
			return false;
		}
		else if(menuLocation=="my-collected-links"){
			window.location.href = BASE_URL +'/databox/my-collected-links';
			return false;
		}
		if( $('#viewPageYes').length )
		{
			
			if( parseInt($('#viewPageChanged').val()) == parseInt("0") )
			{
				if( menuLocation=='logout' )
				{
					logOut('');
					return false;
				}
				else
				{
					window.location.href = BASE_URL + menuLocation;
					return false;
				}
			}
			else if( parseInt($('#viewPageChanged').val()) == parseInt("1") )
			{
				$("#confirm_alert_meassage1").html(cust_changes_alert);			
				$("#confirm_alert_meassage2").html(cust_changes_leave_alert);			
				$('#menu_loc_url').val( menuLocation );
				$('#confirm-pop-up-custpage').popUpWindow({action: "open"});
				if( window.location.href.indexOf("post-horizontal") > -1 )
				{
					$(".pop-up-content").css('position','fixed');
				}
				else if( window.location.href.indexOf("post-vertical") > -1 )
				{
					$(".pop-up-content").removeAttr('position');
				}
				return false;
			}
		}
		
		if( chkvalues==2 )
		{			
			$('.tag_chk').removeAttr('href');
			$('.tag_chk').css('cursor','pointer');
			$('.tag_chk').attr('href','javascript:void(0)');
			$('#confirm-pop-up-alerts').popUpWindow({action: "open"});
			$("#confirm_alert_header_meassge").html('');
			$("#confirm_alert_meassage").html(confrim_verifying_urls_alert);			
			$('#menu_loc_url').val(menuLocation);
		}
		else if( chkvalues==0 )
		{
			$('.tag_chk').css('cursor','pointer');
			$('.tag_chk').attr('href','javascript:void(0)');
			if(menuLocation=='logout'){
				logOut('');
			}else{
				window.location.href = BASE_URL + menuLocation;
			}
		}
		else
		{			
			$('.tag_chk').css('cursor','pointer');
			$('.tag_chk').attr('href','javascript:void(0)');
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(verifying_urls_alert);
		}
	}
	
	function stripHtmlTags(html)
	{
	   var tmp = document.createElement("DIV");
	   tmp.innerHTML = html;
	   return tmp.textContent || tmp.innerText || "";
	}
	

//Account
function updateUser(userId)
	{
		var display_name=$.trim( stripHtmlTags($('#display_name').val()).replace(/&nbsp;/g,'') );
		var password=$.trim( stripHtmlTags($('#password').val()).replace(/&nbsp;/g,'') );
		var confirmpwd=$.trim( stripHtmlTags($('#confirmPwd').val()).replace(/&nbsp;/g,'') );
		$('#display_name').val( display_name );
		if(display_name==""){	
			$('#display_name').focus();
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(entered_profile);			
			return false;
		}
		var invalidDispNameCharacters = /[^a-z^A-Z^0-9^ ]/;
		if( invalidDispNameCharacters.test( display_name ) )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(letters_digits_only + "display name.");
			$("#display_name").focus();
			return false;
		}
		if( parseInt(display_name.length) > parseInt("50") )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(max_fifty_allowed);
			$("#display_name").focus();
			return false;
		}
		if(password!=confirmpwd){	
			$('#password').focus();
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(entered_confirm_password);			
			return false;
		}
		$.ajax({
			type		:	'POST',
			url			:  	BASE_URL+'/accounts',
			data		:	{userId:userId,display_name:display_name,password:password},
			success: function(data){
				$('#display_name').html('<label for="email">Profile Name:</label><input type="text" name="display_name" id="display_name" value="'+display_name+'" onClick="acountChange();"><div class="acount_pos_abs"><p>Change</p><p><img onClick="profileEmpty();" src="'+BASE_PATH+'/images/social_media/edit_ico.png"/></p></div>');
				$('.acount_pos_abs').hide();
				$('#confirmPwd').val('');
				$('#confirmLable').hide();
				$('#passwordLable').html('<label for="email">Password:</label><input type="password" name="password" id="password" placeholder="reset your password" onfocus="showConfirmPassword()" >');
				$('#headerDisplayName').html(display_name);
				$('#updateMessage').show();
				$('#updateMessage').html('Updated sucessfully');
				$('#updateMessage').delay(2000).fadeOut('slow');
			}
		});
	}
function changeAccountStatus(status,userId){
		$('#deactiveLoad').show();
		$.ajax({
			type		:	'POST',
			url			:  	BASE_URL+'/change-status-account',
			data		:	{userId:userId,status:status},
			success: function(data){
				if(status==2){
					$('#active-deactive-id').html('<a href="javascript:void(0);" onclick="changeAccountStatus(1,'+userId+');">Active Account</a>');
					$('#updateMessage').show();
					logOut('Deactive');
				}else if(status==1){
					$('#active-deactive-id').html('<a href="javascript:void(0);" onclick="changeAccountStatus(2,'+userId+');">Deactive Account</a>');
					$('#updateMessage').show();
					$('#updateMessage').html('Account Activated succussesfully');
					$('#updateMessage').delay(2000).fadeOut('slow');
				}else{
					logOut();
				}
			}
		});
	}
function accountPicChange1(src,width,height){
		if(width<=255 && height<=300){
			$('#pop-up-image-crop').css('display','none');
			$('#uploadedAccountImage1').attr('src',src);
			$('#uploadedAccountImage2').attr('src',src);
			$('#uploadedAccountImage3').attr('src',src);
			$('#headerImage1').attr('src',src);
			setAccountPublicCatImage(0);
		}else if(width>255 && height<=300){
			$('#pop-up-image-crop').css('display','none');
			$('#uploadedAccountImage1').attr('src',src);
			$('#uploadedAccountImage2').attr('src',src);
			$('#uploadedAccountImage3').attr('src',src);
			$('#headerImage1').attr('src',src);
			setAccountPublicCatImage(0);
		}else if(width<=255 && height>300){
			$('#pop-up-image-crop').css('display','none');
			$('#uploadedAccountImage1').attr('src',src);
			$('#uploadedAccountImage2').attr('src',src);
			$('#uploadedAccountImage3').attr('src',src);
			$('#headerImage1').attr('src',src);
			setAccountPublicCatImage(0);
		}else{
			$('.resize-image').attr('src', src);
			$.getScript( BASE_PATH+"/js/cropjs/jquery-2.1.1.min.js" ).done(function( script, textStatus ) {
				$.getScript( BASE_PATH+"/js/cropjs/component.js" ).done(function( script, textStatus ) {
					$('#pop-up-image-crop').css('display','block');
				});
			});
		}
	}
function setAccountPublicCatImage(src){	
		var fetchUploadedUrl = BASE_URL+'/upload-montage-image';
		if(src==0){		
			var file_data = $('#accountPublicImageFile').prop('files')[0];
			var form_data = new FormData();
			form_data.append('montage_file', file_data);
			$.ajax({
				url: fetchUploadedUrl,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function(data){
					if(data.value==1) {
						//window.location=BASE_URL+"/accounts";
					}else{
						//window.location=BASE_URL+"/databoxuser/user-login/"+data.imageId;
						$('#cropImage').val(data.imageId);
					}
				}
			 });
		}else{
			crop_value_count++;
			$.ajax({
				type	: "POST",
				url		: fetchUploadedUrl,
				data	: { data: src,page:'acc'},
				success: function(data){
					$('#pop-up-image-crop').css('display','none');
					$('#uploadedAccountImage1').attr('src',src);
					$('#uploadedAccountImage2').attr('src',src);
					$('#uploadedAccountImage3').attr('src',src);
					$('#headerImage1').attr('src',src);
					$('#cropImage').val(data.imageId);
				}
			});
		}
	}
function showimagepreview(input)
	{
		if (input.files && input.files[0])
		{
			var filerdr = new FileReader();
			filerdr.onload = function(e)
			{
				$('#imgprvw').attr('src', e.target.result);
			}
			filerdr.readAsDataURL(input.files[0]);
		}
	}
	

	function isInValidFormat( emailId )
	{
		var arCount = (emailId.match(/@/g) || []).length;
		if( parseInt(arCount) == parseInt("0") || parseInt(arCount) > parseInt("1") || ((parseInt(arCount) == parseInt("1")) && (emailId.charAt(0) == "@")) )
		{
			return false;
		}
		
		var splitOnDot = emailId.split( "." );
		if( parseInt(splitOnDot.length) == parseInt("1") )
		{
			return false;
		}
		for( var sodi = 0;sodi < splitOnDot.length;sodi++ )
		{
			if( splitOnDot[sodi].length == 0 )
			{
				return false;
			}
		}
		var splitOnAr = emailId.split( "@" );
		for( var soari = 0;soari < splitOnAr.length;soari++ )
		{
			if( splitOnAr[soari].length == 0 )
			{
				return false;
			}
			var splitOnDot = splitOnAr[soari].split( "." );
			for( var sodi = 0;sodi < splitOnDot.length;sodi++ )
			{
				if( splitOnDot[sodi].length == 0 )
				{
					return false;
				}
			}
		}
		
		return true;
	}
	
function checkLogin(alChe)
	{
		if(alChe == 'regChe'){
			var emailId = $.trim( stripHtmlTags($('#emailId').val()).replace(/&nbsp;/g,'') );
			$('#emailId').val( emailId );
			if( emailId == "" )
			{
				/*
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(entered_email_id);
				*/
				alert( "Please enter your email." );
				$('#emailId').focus();
				return false;
			}
			
			if( ! isInValidFormat(emailId)  )
			{
				alert( "Please enter email Id in valid format." );
				$('#emailId').focus();
				return false;
			}
			var userPassword = $.trim( stripHtmlTags($('#userPassword').val()).replace(/&nbsp;/g,'') );
			$('#userPassword').val( userPassword );
			var userPassword = $('#userPassword').val().trim();
			if( userPassword == "" )
			{
				/*
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(entered_password);
				*/
				alert( "Please enter your password." );
				$('#userPassword').focus();
				return false;
			}	
			$('#userMessage').show();
			$('#userMessage').html( "Checking Username,Password..." );
		}else if(alChe == 'loginChe'){
			var emailId = $.trim( stripHtmlTags($('#emailId1').val()).replace(/&nbsp;/g,'') );
			$('#emailId1').val( emailId );
			if( emailId == "" )
			{
				/*
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(entered_email_id);
				*/
				alert( "Please enter your email." );
				$('#emailId1').focus();
				return false;
			}
			
			if( ! isInValidFormat(emailId)  )
			{
				alert( "Please enter email Id in valid format." );
				$('#emailId1').focus();
				return false;
			}
			var userPassword = $.trim( stripHtmlTags($('#userPassword1').val()).replace(/&nbsp;/g,'') );
			$('#userPassword1').val( userPassword );
			var userPassword = $('#userPassword1').val().trim();
			if( userPassword == "" )
			{
				/*
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(entered_password);
				*/
				alert( "Please enter your password." );
				$('#userPassword1').focus();
				return false;
			}	
			$('#userMessage').show();
			$('#userMessage').html( "Checking Username,Password..." );
		}else if(alChe == 'loginPopup'){
			$('#userMessage2').removeClass( "tg_email_btn error_display_c" );
			$('#userMessage2').addClass( "tg_email_btn success_display_c" );
			var emailId = $.trim( stripHtmlTags($('#emailId2').val()).replace(/&nbsp;/g,'') );
			$('#emailId2').val( emailId );
			if( emailId == "" )
			{
				/*
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(entered_email_id);
				*/
				alert( "Please enter your email." );
				$('#emailId1').focus();
				return false;
			}
			
			if( ! isInValidFormat(emailId)  )
			{
				alert( "Please enter email Id in valid format." );
				$('#emailId1').focus();
				return false;
			}
			var userPassword = $.trim( stripHtmlTags($('#userPassword2').val()).replace(/&nbsp;/g,'') );
			$('#userPassword2').val( userPassword );
			var userPassword = $('#userPassword2').val().trim();
			if( userPassword == "" )
			{
				/*
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(entered_password);
				*/
				alert( "Please enter your password." );
				$('#userPassword2').focus();
				return false;
			}	
			$('#userMessage2').show();
			$('#userMessage2').html( "Checking Username,Password..." );
		}
		
		var emailExistsUrl = BASE_URL + "/databoxuser/email-exists";
		$.ajax({
		  url: emailExistsUrl,
		  type: "POST",
		  data:{emailId:emailId,userPassword:userPassword},
		  async: false,
		  success: function(data) {
			if( parseInt(data) == parseInt("1") )
			{
				$('#userMessage2').removeClass( "tg_email_btn success_display_c" );
				$('#userMessage2').addClass( "tg_email_btn error_display_c" );
				$('#userMessage2').html( "Incorrect password." );
				return false;
			}
			else
			{
				if(alChe == 'loginChe'){
					if(userLoginFormUrl1.remember_me.checked == true){ 
						$.cookie('email', emailId, { expires: 1 });
						$.cookie('password', userPassword, { expires: 1 });
						$.cookie('remember', true, { expires: 1 });  
					}else{
						$.cookie('email', null);
						$.cookie('password', null);
						$.cookie('remember', null);
					}
				}
				submitLoginForm(alChe);
			}
		  }
		});
	}
function submitLoginForm(alChe)
	{
		var baseUrl = BASE_URL;
		if(alChe == 'loginChe'){
			var userLoginFormUrl1 = baseUrl + "/databoxuser/user-login";
			$("#userLoginFormUrl1").attr( "action",userLoginFormUrl1  );
			$("#userLoginFormUrl1").submit();
		}else if(alChe == 'loginPopup'){
			var userLoginFormUrl2 = baseUrl + "/databoxuser/user-login";
			$("#userLoginFormUrl2").attr( "action",userLoginFormUrl2  );
			$("#userLoginFormUrl2").submit();
		}else{
			var userLoginFormUrl = baseUrl + "/databoxuser/user-login";
			$("#userLoginFormUrl").attr( "action",userLoginFormUrl  );
			$("#userLoginFormUrl").submit();
		}
		return false;
	}
function checkSpaces()
	{
		var hashtagHolder = $("#highlightHtHolder").val().trim();
		if( hashtagHolder != "" )
		{
			hashtagHolder = $("#highlightHtHolder").val().replace(/^\s+/, "");
			hashtagHolder = hashtagHolder.replace(/\s+$/, "");
			var spc = " ";
			if( parseInt(hashtagHolder.indexOf(spc)) > parseInt("-1") )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(space_hash);
				window.setTimeout(function(){$("#highlightHtHolder").focus();}, 100);
				return false;
			}
		}
		return false;
	}

	function nextStepsHighlights( submitVal )
	{
		if( submitVal == "Continue" )
		{
			var highlightHtHolder=$.trim( stripHtmlTags($('#highlightHtHolder').val()).replace(/&nbsp;/g,'') );
			highlightHtHolder = highlightHtHolder.trim();
			$('#highlightHtHolder').val( highlightHtHolder );
			if( highlightHtHolder == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(highlight_hash);
				$("#highlightHtHolder").focus();
				$('#dcInprogressDiv').hide();
				$('#dcHtmlDiv').show();
				return false;
			}
			var invalidHtCharacters = /[^a-z^A-Z^0-9^#]/;
			if( invalidHtCharacters.test( highlightHtHolder ) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(chk_valid_hash_tag);
				$("#highlightHtHolder").focus();
				$('#dcInprogressDiv').hide();
				$('#dcHtmlDiv').show();
				return false;
			}
			var hashCount = (highlightHtHolder.match(/#/g) || []).length;
			if( parseInt(hashCount) > parseInt("1") || ((parseInt(hashCount) == parseInt("1")) && (highlightHtHolder.charAt(0) != "#")) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(hash_tag_allowed);
				$("#highlightHtHolder").focus();
				$('#dcInprogressDiv').hide();
				$('#dcHtmlDiv').show();
				return false;
			}
			if( highlightHtHolder.charAt(0) != "#" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(leading_hash);
				$("#highlightHtHolder").focus();
				$('#dcInprogressDiv').hide();
				$('#dcHtmlDiv').show();
				return false;
			}
			$('#highlightHashTag').val( $('#highlightHtHolder').val() );
		
			var highlightTitleHolder=$.trim( stripHtmlTags($('#highlightTitleHolder').val()).replace(/&nbsp;/g,'') );
			highlightTitleHolder = highlightTitleHolder.trim();
			$('#highlightTitleHolder').val( highlightTitleHolder );
			if( highlightTitleHolder == "" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(highlight_title);
				$("#highlightTitleHolder").focus();
				$('#dcInprogressDiv').hide();
				$('#dcHtmlDiv').show();
				return false;
			}
			var invalidTitleCharacters = /[^a-z^A-Z^0-9^ ]/;
			if( invalidTitleCharacters.test( highlightTitleHolder ) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(category_chk_title);
				$("#highlightTitleHolder").focus();
				$('#dcInprogressDiv').hide();
				$('#dcHtmlDiv').show();
				return false;
			}
			$('#highlightTitle').val( $('#highlightTitleHolder').val() );

			var src = $('#uploadedCatImage0').prop('src');
			var imageNameArr = src.split( "/" );
			var imageName = imageNameArr[(imageNameArr.length)-1];
			if( imageName == "upload_img.png" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(highlight_image);
				$('#dcInprogressDiv').hide();
				$('#dcHtmlDiv').show();
				return false;
			}

			var metaTagsHolder=$.trim( stripHtmlTags($('#metaTagsHolder').val()).replace(/&nbsp;/g,'') );
			$('#metaTagsHolder').val( metaTagsHolder );
			$('#highlightKeywords').val( metaTagsHolder );

			$('#submitVal').val( "CStyle" );
			cStyleSet();
		}
	}
	
	function displayHighlightView( categoryId,hashName,categoryTitlee,settingId,catImage )
	{
		if( $( "#loggedInStatus" ).val() == "" ){
			if(settingId==3){
				var viewUrl='pre-vertical';
			}else{
				var viewUrl='pre-horizontal';
			}
		}else{
			if(settingId==3){
				var viewUrl='post-vertical';
			}else{
				var viewUrl='post-horizontal';
			}
		}

		var categoryTitle=categoryTitlee.replace(/ /g,'-');
		var hashNamee =hashName.substring(1);
		var displayCustomizationUrl = BASE_URL;
		displayCustomizationUrl += "/databox/"+viewUrl+"/"+categoryId+'+'+catImage+'+'+hashNamee+'+'+categoryTitle;
		$("#highlightDisplayLink"+categoryId).attr('target','_blank');
		$("#highlightDisplayLink"+categoryId).attr('href',displayCustomizationUrl);
	}

	function searchHighlights()
	{
		var searchTermHolder = $("#searchTermHolder").val().trim();
		if( searchTermHolder == "" )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(search_alerts);
			$("#searchTermHolder").focus();
			return false;
		}
		hashtagHolder = $("#searchTermHolder").val().replace(/^\s+/, "");
		hashtagHolder = hashtagHolder.replace(/\s+$/, "");
		var spc = " ";
		if( parseInt(hashtagHolder.indexOf(spc)) > parseInt("-1") )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(entered_underscore);
			$("#searchTermHolder").focus();
			return false;
		}
		var invalidHtCharacters = /[^a-z^A-Z^0-9^_^#]/;
		if( invalidHtCharacters.test( hashtagHolder ) )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(highlight_valid);
			$("#searchTermHolder").focus();
			return false;
		}
		
		$("#hgltsSearchTerm").val( hashtagHolder );
		
		$.ajax({
			  url: BASE_URL+"/databox/highlights-search-ajax",
			  type: "POST",
			  data:{hgltsSearchTerm:hashtagHolder},
			  success: function(data) {
				if( parseInt($( "#votepageSearched" ).val()) == parseInt("0") )
				{
					$( "#votepageSearched" ).val( 1 );
					$('#hgltsBackUpDiv').html($('#highlightsMainDiv').html());
				}
				$('#highlightsMainDiv').html(data);
			  }
		});
	}
	function likeDislikeCnt( newVoteValue,categoryId,rw_th,TotalvoteUp,Totalrw_lh )
	{
		var votingid=$('#voting'+categoryId).val();
		var voteUrl = BASE_URL + "/databox/vote-on-highlight";
		$.ajax({
		  url: voteUrl,
		  type: "POST",
		  data:{type:newVoteValue,categoryId:categoryId,rw_th:rw_th},
		  async: false,
		  success: function(data) {
			if(newVoteValue==1){
				if(votingid==3){
					var newTotalvoteUp=parseInt(TotalvoteUp) + parseInt("1");
					var newTotalrw_lh=parseInt(Totalrw_lh) + parseInt("1");
				}
				if(votingid==1){
					var newTotalvoteUp=parseInt(TotalvoteUp) + parseInt("1");
					var newTotalrw_lh=parseInt(Totalrw_lh) + parseInt("1");
				} 
				if(votingid==0){
					var newTotalvoteUp=parseInt(TotalvoteUp) + parseInt("1");
					var newTotalrw_lh=parseInt(Totalrw_lh);
				}
				
			}
			if(newVoteValue==0){
				if(Totalrw_lh=="" || Totalrw_lh==0){
					var newTotalvoteUp=parseInt(TotalvoteUp);		
					var newTotalrw_lh=parseInt(Totalrw_lh) + parseInt("1");
				}else{
					if(votingid==1){
						var newTotalvoteUp=parseInt(TotalvoteUp)- parseInt("1");		
						var newTotalrw_lh=parseInt(Totalrw_lh);
					}else if(votingid==0){
						var newTotalvoteUp=parseInt(TotalvoteUp);		
						var newTotalrw_lh=parseInt(Totalrw_lh);
					}else{
						var newTotalvoteUp=parseInt(TotalvoteUp);		
						var newTotalrw_lh=parseInt(Totalrw_lh) + parseInt("1");
						
					}
				}
				
			}
			var like=(parseInt(newTotalvoteUp)/parseInt(newTotalrw_lh))*100;
			like1 = Math.round(like * 100) / 100;
			$('#likes'+categoryId).html('<h2>'+like1+'% liked</h2>');
			if(newVoteValue==1){
				var clickAppendHtml='<img src="'+BASE_URL+'/public/img/love_ok.png" alt="" />  or  <a href="Javascript:void(0);" onClick="return likeDislikeCnt(0,'+categoryId+',1,'+newTotalvoteUp+','+newTotalrw_lh+')"><img src="'+BASE_URL+'/public/img/trash.png" alt="" /></a>';
				clickAppendHtml+='<input type="hidden" id="voting'+categoryId+'" name="voting'+categoryId+'" value="1">';
			}else{
				var clickAppendHtml='<a href="Javascript:void(0);" onClick="return likeDislikeCnt(1,'+categoryId+',1,'+newTotalvoteUp+','+newTotalrw_lh+')" ><img src="'+BASE_URL+'/public/img/love.png" alt="" /></a>  or  <img src="'+BASE_URL+'/public/img/untrash.png" alt="" />';
				clickAppendHtml+='<input type="hidden" id="voting'+categoryId+'" name="voting'+categoryId+'" value="0">';

			}
			$('#divCardLoveTrash'+categoryId).html(clickAppendHtml);
		  }
		});	
	}
	
	function voteOnHighlight( newVoteValue,categoryId )
	{
		var catVoteCount = $( '#catVoteCount' + categoryId ).html();
		var lod_image_set = '<img src="'+BASE_URL+'/public/images/social_media/bx_loader.gif" style="vertical-align:middle;width:15px;">';
		$( '#catVoteCount' + categoryId ).html( lod_image_set );

		var voteUrl = $('#baseUrl').val() + "/databox/vote-on-highlight";
		$.ajax({
		  url: voteUrl,
		  type: "POST",
		  data:{type:newVoteValue,categoryId:categoryId},
		  async: false,
		  success: function(data) {
		  	var takeTopEnableImgName = $('#basePath').val() + "/images/social_media/social_arrow_up.png";
			var listDownEnableImgName = $('#basePath').val() + "/images/social_media/social_arrow_down_enable.png";
			var takeTopDisableImgName = $('#basePath').val() + "/images/social_media/social_arrow_up_disable.png";
			var listDownDisableImgName = $('#basePath').val() + "/images/social_media/social_arrow_down.png";
			if( parseInt(newVoteValue) == parseInt("1") )
			{
				catVoteCount = parseInt(catVoteCount) + parseInt("1");

				newVoteLinkHtml = '<img src="'+takeTopDisableImgName+'"/><br/><b id="catVoteCount'+categoryId+'"></b><br/><a href="JavaScript:void(0);" onClick="voteOnHighlight(0,' + categoryId + ');" ><img id="voteStatusImg' + categoryId + '" src="' + listDownEnableImgName + '" /></a>';
				$( '#voteLinksDiv' + categoryId ).html( newVoteLinkHtml );

				$( '#catVoteCount' + categoryId ).html( catVoteCount );
			}
			if( parseInt(newVoteValue) == parseInt("0") )
			{
				catVoteCount = parseInt(catVoteCount) - parseInt("1");

				newVoteLinkHtml = '<a href="JavaScript:void(0);" onClick="voteOnHighlight(1,' + categoryId + ');" ><img id="voteStatusImg' + categoryId + '" src="' + takeTopEnableImgName + '" /></a><br/><b id="catVoteCount'+categoryId+'"></b><br/>'+'<img src="'+listDownDisableImgName+'"/>';
				$( '#voteLinksDiv' + categoryId ).html( newVoteLinkHtml );
				
				$( '#catVoteCount' + categoryId ).html( catVoteCount );
			}
			
		  }
		});
	}
	
function deleteHighlight( categoryId )
	{
		var deleteHighlightUrl = $('#baseUrl').val() + "/databox/delete-highlight";
		$.ajax({
		  url: deleteHighlightUrl,
		  type: "POST",
		  data:{categoryId:categoryId},
		  async: false,
		  success: function(data) {
			location.reload();
	    },
		  error: function(){
			alert( "error" );
		  }
		});
	}
	
function editHighlight( categoryId )
	{
		$('#categoryId').val( categoryId );

		var editHighlightUrl = $('#baseUrl').val() + "/databox/edit-highlight";

		$('#editHighlightFrm').attr( "action",editHighlightUrl );
		$('#editHighlightFrm').submit();
	}
	

	function nextSteps( submitVal )
	{
		if( submitVal == "Continue" )
		{
			if( dbPrimary() )
			{
				if( $('#categoryType').val() != "" )
				{
					var categoryType = $('#categoryType').val();
					if( parseInt(categoryType) == parseInt("0") )
					{
						if( privateDataBox() )
						{
							$('#submitVal').val( "CStyle" );
							cStyleSet();
						}
						else
						{
							$('#dcInprogressDiv').hide();
							$('#dcHtmlDiv').show();
							return false;
						}
					}
					else if( parseInt(categoryType) == parseInt("1") )
					{
						if( publicDataBox() )
						{
							$('#submitVal').val( "CStyle" );
							cStyleSet();
						}
						else
						{
							$('#dcInprogressDiv').hide();
							$('#dcHtmlDiv').show();
							return false;
						}
					}
				}
			}
			else
			{
				$('#dcInprogressDiv').hide();
				$('#dcHtmlDiv').show();
				return false;
			}
			
			return false;
		}
	}
// Scripts for social logins start
function openPopup(url)
	{
		var postHighlights = $( "#postHighlights" ).val();
		var nsfwMcSet = $( "#nsfwMcSet" ).val();
		
		if( (parseInt(postHighlights) == parseInt("1")) || (parseInt(nsfwMcSet) == parseInt("1")) )
		{
			$('#userMessage').html( "Opening social network..." );
			$('#userMessage').show();

			var setRedirectUrl = BASE_URL + "/databoxuser/set-redirect-session";

			if( parseInt(postHighlights) == parseInt("1") )
			{
				$.ajax
				({
				  url: setRedirectUrl,
				  type: "POST",
				  data:{postHighlights:postHighlights},
				  async: false,
				  success: function(data)
				  {
					var left = Number((screen.width/2)-(500/2));
					var top = Number((screen.height/2)-(500/2));
					
					var windowFeatures = 'channelmode=0,directories=0,fullscreen=0,location=0,menubar=0,resizable=0,scrollbars=0,status=0,width=500,height=450,top=' + top + ',left=' + left;
					$('#userMessage').hide();
					$('#userMessage').html( "" );
					window.location=url;
				  }
				});
			}
			else if( parseInt(nsfwMcSet) == parseInt("1") )
			{
				var categoryId = $( "#categoryId" ).val();
				var hashName = $( "#hashName" ).val();
				var categoryTitle = $( "#categoryTitle" ).val();
				var settingId = $( "#settingId" ).val();
				var categoryImage = $( "#categoryImage" ).val();
				$.ajax
				({
				  url: setRedirectUrl,
				  type: "POST",
				  data:{nsfwMcSet:nsfwMcSet,categoryId:categoryId,hashName:hashName,categoryTitle:categoryTitle,settingId:settingId,categoryImage:categoryImage},
				  async: false,
				  success: function(data)
				  {
					var left = Number((screen.width/2)-(500/2));
					var top = Number((screen.height/2)-(500/2));
					
					var windowFeatures = 'channelmode=0,directories=0,fullscreen=0,location=0,menubar=0,resizable=0,scrollbars=0,status=0,width=500,height=450,top=' + top + ',left=' + left;
					$('#userMessage').hide();
					$('#userMessage').html( "" );
					window.location=url;
				  }
				});
			}
		}
		else
		{
			var left = Number((screen.width/2)-(500/2));
			var top = Number((screen.height/2)-(500/2));
			
			var windowFeatures = 'channelmode=0,directories=0,fullscreen=0,location=0,menubar=0,resizable=0,scrollbars=0,status=0,width=500,height=450,top=' + top + ',left=' + left;
			window.location=url;
		}
	}
	
	function setRedirectSession()
	{
		var setRedirectUrl = BASE_URL + "/databoxuser/set-redirect-session";
		$.ajax
		({
		  url: setRedirectUrl,
		  type: "POST",
		  data:{sessUnset:1},
		  async: false,
		  success: function(data)
		  {
			$('#userMessage').hide();
			$('#userMessage').html( "" );
		  }
		});

		return false;
	}
	
// Scripts for social logins end
function displayCustomization( categoryId,hashName,categoryTitlee,settingId,catImage )
	{
		if( $( "#loggedInStatus" ).val() == "" ){
			if(settingId==3){
				var viewUrl='pre-vertical';
			}else{
				var viewUrl='pre-horizontal';
			}
		}else{
			if(settingId==3){
				var viewUrl='post-vertical';
			}else{
				var viewUrl='post-horizontal';
			}
		}
		var categoryTitle=categoryTitlee.replace(/ /g,'-');
		var hashNamee =hashName.substring(1);
		var displayCustomizationUrl = BASE_URL;
		displayCustomizationUrl += "/databox/"+viewUrl+"/"+categoryId+'+'+catImage+'+'+hashNamee+'+'+categoryTitle;
		window.location = displayCustomizationUrl;
	}
function displayCustomization1( categoryId,settingId )
	{
		if( $( "#loggedInStatus" ).val() == "" ){
			if(settingId==3){
				var viewUrl='pre-vertical';
			}else{
				var viewUrl='pre-horizontal';
			}
		}else{
			if(settingId==3){
				var viewUrl='post-vertical';
			}else{
				var viewUrl='post-horizontal';
			}
		}
		var displayCustomizationUrl = BASE_URL;
		displayCustomizationUrl += "/databox/"+viewUrl+"/"+categoryId;
		window.location = displayCustomizationUrl;
	}
function displayNsfwMc( categoryId,hashName,categoryTitlee,settingId,catImage )
	{
		if( $( "#loggedInStatus" ).val() == "" )
		{
			var categoryTitle=categoryTitlee.replace('-','~')
			var hashNamee =hashName.substring(1);
			
			$( "#postClicked" ).val( 0 );
			$( "#postHighlights" ).val( 0 );

			$( "#nsfwMcClicked" ).val( 1 );
			$( "#nsfwMcSet" ).val( 1 );
			
			$( "#categoryId" ).val( categoryId );
			$( "#hashName" ).val( hashNamee );
			$( "#categoryTitle" ).val( categoryTitle );
			$( "#settingId" ).val( settingId );
			$( "#categoryImage" ).val( catImage );

			$('#userMessage').show();
			$('#userMessage').html( "Please log in to view this content." );
			$('#open-pop-up-1').click();
		}
		else
		{
			displayCustomization( categoryId,hashName,categoryTitlee,settingId,catImage );
		}
	}
function displayNsfwMc1( categoryId,settingId )
	{
		if( $( "#loggedInStatus" ).val() == "" )
		{
			$( "#postClicked" ).val( 0 );
			$( "#postHighlights" ).val( 0 );
			$( "#nsfwMcClicked" ).val( 1 );
			$( "#nsfwMcSet" ).val( 1 );
			$( "#categoryId" ).val( categoryId );
			$( "#hashName" ).val( "" );
			$( "#categoryTitle" ).val( "" );
			$( "#settingId" ).val( settingId );
			$( "#categoryImage" ).val( "" );
			$('#userMessage').show();
			$('#userMessage').html( "Please log in to view this content." );
			$('#open-pop-up-1').click();
		}
		else
		{
			displayCustomization1( categoryId,settingId );
		}
	}
function displayUserCollection( userId )
	{
		var viewUrl='user-collection';
		var displayUserCollectionUrl = BASE_URL;
		displayUserCollectionUrl += "/"+viewUrl+"/"+userId;
		window.location = displayUserCollectionUrl;
	}
	

	function userCollectionLinks(type,catId)
	{
		var totalCount=$('#totalCount'+catId).val();
		var value=Math.ceil((parseInt(totalCount)/7))-1;
		var presentCount=$('#presentCount'+catId).val();
		if(type=='1'){
			var end=parseInt(presentCount)+7;
			var start=end-6;
		}else{
			var end=parseInt(presentCount)-7;
			var start=end-6;
		}
		var lod_image_set = '<div class="" id="montage_loder" style="position:absolute;top:45%;left:50%;"><img src="'+BASE_URL+'/public/images/social_media/bx_loader.gif"></div>';
		$('#montageRow'+catId).html(lod_image_set);
		$('.montage_row').css('height','136px');
		$.ajax({
			type		:	'POST',
			url			:  	BASE_URL+'/user-collection-links',
			data		:	{type:type,catId:catId,start:start,end:end},
			success: function(data){
				$('#montageRow'+catId).html(data);
				$('#presentCount'+catId).val(end);
				if(end>=totalCount){
					$('#nextPagination'+catId).hide();
				}
				if(end>7){
					$('#prevPagination'+catId).show();
				}
				if(start==1){
					$('#prevPagination'+catId).hide();
					$('#nextPagination'+catId).show();
				}
				if(end==value*7){
					$('#nextPagination'+catId).show();
				}
			}
		});
	}
function changeImage(input){
		if (input.files && input.files[0]){
			var filerdr = new FileReader();
			filerdr.onload = function(e){
				$('#MontageImage').attr('src', e.target.result);
			}
			filerdr.readAsDataURL(input.files[0]);
		}
		montageFormSubmit();
	}
function montageFormSubmit(){
		var fetchUploadedUrl = BASE_URL+'/upload-montage-image';
		var file_data = $('#change_image').prop('files')[0];
		var form_data = new FormData();
		form_data.append('montage_file', file_data);
		$.ajax({
			url: fetchUploadedUrl,
			dataType: 'text',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         
			type: 'post',
			success: function(data){
			}
		 });
	}
function montagePopUp(type){
		if(type==1){
			$('#hashMessageError').html('');
			$('#pop-up-hash').popUpWindow({action: "open"});
		}else if(type==2){
			$('#titleMessageError').html('');
			$('#pop-up-title').popUpWindow({action: "open"});
		}else if(type==3){
			$('#paragraphMessageError').html('');
			$('#pop-up-paragraph').popUpWindow({action: "open"});
		}
	}
function montagePopUpClose(type){
		if(type==1){
			$('#pop-up-hash').popUpWindow({action: "close"});
		}else if(type==2){
			$('#pop-up-title').popUpWindow({action: "close"});
		}else{
			$('#pop-up-paragraph').popUpWindow({action: "close"});
		}
	}
function saveMontage(typee){
		var name="";
		if(typee==1){
			name='hash';
			var text=$('#montage_hash_name').val().trim();
		}else if(typee==2){
			name='title';
			var text=$('#montage_title').val();
		}else if(typee==3){
			name='paragraph';
			$('textarea[name="montage_paragraph"]').val(CKEDITOR.instances.montage_paragraph.getData());
			var desc=$('#montage_paragraph').val().replace("<p>","");
			desc=desc.replace("</p>","");
			var text=desc;
		}
		if(text==""){
			if(typee==1){
				$('#hashMessageError').html('Please provide hash name');
			}else if(typee==2){
				$('#titleMessageError').html('Please provide title');
			}else{
				$('#paragraphMessageError').html('Please provide paragraph');
			}
		}else{
			if(typee==1){
				hashtagHolder = $("#montage_hash_name").val().replace(/^\s+/, "");
				hashtagHolder = hashtagHolder.replace(/\s+$/, "");
				var spc = " ";
				if( parseInt(hashtagHolder.indexOf(spc)) > parseInt("-1") )
				{
					$('#hashMessageError').html(space_hash);
					$("#montage_hash_name").focus();
					return false;
				}
				var invalidHtCharacters = /[^a-z^A-Z^0-9^#]/;
				if( invalidHtCharacters.test( hashtagHolder ) )
				{
					$('#hashMessageError').html(chk_valid_hash_tag);
					$("#montage_hash_name").focus();
					return false;
				}
				var hashCount = (hashtagHolder.match(/#/g) || []).length;
				if( parseInt(hashCount) > parseInt("1") || ((parseInt(hashCount) == parseInt("1")) && (hashtagHolder.charAt(0) != "#")) )
				{
					$('#hashMessageError').html(hash_tag_allowed);
					$("#montage_hash_name").focus();
					return false;
				}
				if( hashtagHolder.charAt(0) != "#" )
				{
					$('#hashMessageError').html(leading_hash);
					$("#montage_hash_name").focus();
					return false;
				}
				text = hashtagHolder.substring( 1,parseInt(hashtagHolder.length) );
			}

			$.ajax({
				type		:	'POST',
				url			:  	BASE_URL+'/montage-hash-name',
				data		:	{text:text,type:name},
				success: function(data){
					if(data.output==1){
						if(typee==1){
							if(text.length>30){
								var text1 = text.substring(0,30)+'..';
							}else{
								var text1=text;
							}
							$('#pop-up-hash').popUpWindow({action: "close"});
							$('#hash_name').html('<div  class="float_l"><h1>#'+text1+'</h1></div><div class="float_l"><a href="javascript:void(0);" onclick="montagePopUp(1)" title="Edit hash name" class="add_new_pick">EDIT</a></div>');
							$('#montage_hash_name').val("#"+text);
							if(text.length>15){
								var text2 = text.substring(0,15)+'..';
							}else{
								var text2=text;
							}
							$('#myCollectionDisplay').html('#'+text2);
						}else if(typee==2){
							$('#pop-up-title').popUpWindow({action: "close"});
							$('#your_title').html('<h2><span><a href="javascript:void(0);" onclick="montagePopUp(2)" class="">EDIT TITLE&nbsp;&nbsp;&nbsp;&nbsp;</a></span>'+text+'</h2>');
							$('#montage_title').val(text);
						}else{
							$('#pop-up-paragraph').popUpWindow({action: "close"});
							$('#your_paragraph').html('<div class="span700"><div id="montagPara_Div_id" class="montage_Paragraph_show"><P>'+text+'<span><a href="javascript:void(0);" title="Edit your paragraph" onclick="montagePopUp(3)" class="edit_paragraph_mon_empty">EDIT</a></span></P></div></div>');
							$('#montage_paragraph').val(text);
						}
					}
				}
			});
		}
	}
function paginationLinks(type,catId){
		var totalCount=$('#totalCount'+catId).val();
		var value=Math.ceil((parseInt(totalCount)/7))-1;
		var presentCount=$('#presentCount'+catId).val();
		if(type=='1'){
			var end=parseInt(presentCount)+7;
			var start=end-6;
		}else{
			var end=parseInt(presentCount)-7;
			var start=end-6;
		}
		var lod_image_set = '<div class="" id="montage_loder" style="position:absolute;top:45%;left:50%;"><img src="'+BASE_URL+'/public/images/social_media/bx_loader.gif"></div>';
		$('#montageRow'+catId).html(lod_image_set);
		$('.montage_row').css('height','136px');
		$.ajax({
			type		:	'POST',
			url			:  	BASE_URL+'/pagination-montage-links',
			data		:	{type:type,catId:catId,start:start,end:end},
			success: function(data){
				$('#montageRow'+catId).html(data);
				$('#presentCount'+catId).val(end);
				if(end>=totalCount){
					$('#nextPagination'+catId).hide();
				}
				if(end>7){
					$('#prevPagination'+catId).show();
				}
				if(start==1){
					$('#prevPagination'+catId).hide();
					$('#nextPagination'+catId).show();
				}
				if(end==value*7){
					$('#nextPagination'+catId).show();
				}
			}
		});
	}

//DashBoard

function saveChanges(catId)
	{
	
		if( $("#user_hashname"+catId).val() != $("#originialCatHash"+catId).val() )
		{
			$('#databoxChanged'+catId).val( 1 );
		}
		if( $("#category_title"+catId).val() != $("#originialCatTitle"+catId).val() )
		{
			$('#databoxChanged'+catId).val( 1 );
		}
		if( $("#catDescription"+catId).val() != $("#originialCatContext"+catId).val() )
		{
			$('#databoxChanged'+catId).val( 1 );
		}
		if( $("#meta_tags"+catId).val() != $("#originialKeywords"+catId).val() )
		{
			$('#databoxChanged'+catId).val( 1 );
		}
		if( $("#private-"+catId).prop('checked') )
		{
			$('#databoxChanged'+catId).val( 1 );
		}
		var catImagesrc = $("#dataBoxImage"+catId).prop('src');
		var imageNameArr = catImagesrc.split( "/" );
		var imageName = imageNameArr[(imageNameArr.length)-1];
		if( imageName != $("#originialCatImage"+catId).val() )
		{
			$('#databoxChanged'+catId).val( 1 );
		}
		
		if( parseInt($("#databoxChanged"+catId).val()) == parseInt("0") && parseInt($("#catBoxesChanged"+catId).val()) == parseInt("0") )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(dashboard_no_changes);
			return false;
		}
		
		var hashtagHolder = $("#user_hashname"+catId).val().trim();
		if( hashtagHolder == "" )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(chk_hash_tag);
			$("#user_hashname"+catId).focus();
			return false;
		}
		if( hashtagHolder != "" )
		{
			hashtagHolder = $("#user_hashname"+catId).val().replace(/^\s+/, "");
			hashtagHolder = hashtagHolder.replace(/\s+$/, "");
			var spc = " ";
			if( parseInt(hashtagHolder.indexOf(spc)) > parseInt("-1") )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(space_hash);
				window.setTimeout(function(){$("#user_hashname"+catId).focus();}, 100);
				return false;
			}
			
			var invalidHtCharacters = /[^a-z^A-Z^0-9^#]/;
			if( invalidHtCharacters.test( hashtagHolder ) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(chk_valid_hash_tag);
				$("#user_hashname"+catId).focus();
				return false;
			}
			var hashCount = (hashtagHolder.match(/#/g) || []).length;
			if( parseInt(hashCount) > parseInt("1") || ((parseInt(hashCount) == parseInt("1")) && (hashtagHolder.charAt(0) != "#")) )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(hash_tag_allowed);
				$("#user_hashname"+catId).focus();
				return false;
			}
			if( hashtagHolder.charAt(0) != "#" )
			{
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(leading_hash);
				$("#user_hashname"+catId).focus();
				return false;
			}
			
		}
		if($("#private-"+catId).prop('checked') == true){
			if($('#private-'+catId).val()=='Private'){
				$('#Public').html('Public');
				$('#Private').html('Private');
				$('#messagePrivate').html('up on confirming your "Unique code" and clicking on "Save"');
			}else{
				$('#Public').html('Private');
				$('#Private').html('Public');
				$('#messagePrivate').html('');
			}
			$('#pop-up-dashboard').popUpWindow({action: "open"});
			$('#categoryId').val(catId);
		}else{
			$('#categoryId').val(catId);
			updateDatabox();
		}
	}
	function updateDatabox(num){
		var catId=$('#categoryId').val();
		if(num==1){
			var sendEmail=$('#sendEmail').val();
			$('#sendEmail'+catId).val(sendEmail);
		}		
		// Added Code for urls editing
		if( $('#catBoxesChanged'+catId).val() != "" && parseInt($('#catBoxesChanged'+catId).val()) == parseInt("1") )
		{
			if( parseInt(validateLinks( catId )) == parseInt("0") )
			{
				return false;
			}
		}
		// End Code for urls editing
		
		$('#dashBoardForm'+catId).submit();
	}
	
	function checkUniqueCodeExists( uniqueCode )
	{
		var ucStatusReturn = 0;

		if( uniqueCode != "" )
		{
			var uniqueCodeUrl = BASE_URL + "/databox/unique-pvtcode";
			$.ajax({
			  url: uniqueCodeUrl,
			  type: "POST",
			  async:false,
			  data:{uniqueCode:uniqueCode},
			  success: function(data)
			  {
				ucStatusReturn = data.ucExistsStatus;
			  }
			});
		}

		return ucStatusReturn;
	}
	
	function getRandomString( requiredLength )
	{
		var uniqueCode = "";

		var ucExistsInDatabase = true;
		while( ucExistsInDatabase )
		{
			uniqueCode = randomString( requiredLength );
			
			var ucStatusReturn = checkUniqueCodeExists( uniqueCode );
			if( parseInt(ucStatusReturn) == parseInt("0") )
			{
				ucExistsInDatabase = false;
			}
		}

		return uniqueCode;
	}

	function changePublicPrivate(type,value){
		if(type=='Public'){
			if($("#private-"+value).prop('checked') == true){
				$('#ucodeTitle'+value).hide();
				$('#secret_code'+value).hide();
				$('#ucodeAnchor'+value).hide();
				var meta_tags_hid=$('#key_words_hid').val();
				$('#meta_tags'+value).val(meta_tags_hid);
				$('#meta_tags'+value).prop('disabled',false);
			}else{
				$('#ucodeTitle'+value).show();
				$('#secret_code'+value).show();
				$('#ucodeAnchor'+value).show();
				var meta_tags=$('#meta_tags'+value).val();
				$('#key_words_hid').val(meta_tags);
				$('#meta_tags'+value).val("");
				$('#meta_tags'+value).prop('disabled',true);
			}
		}else{
			if($("#private-"+value).prop('checked') == false){
				var meta_tags_hid=$('#key_words_hid').val();
				$('#ucodeTitle'+value).remove();
				$('#secret_code'+value).remove();
				$('#ucodeAnchor'+value).remove();
				$('#meta_tags'+value).val(meta_tags_hid);
				$('#meta_tags'+value).prop('disabled',false);
			}else{
				var meta_tags=$('#meta_tags'+value).val();
				$('#key_words_hid').val(meta_tags);
				$('#meta_tags'+value).val("");
				$('#meta_tags'+value).prop('disabled',true);
				var privatehtml="";
				var user_hashname=$('#user_hashname'+value).val();
				var seconds = new Date().getTime() / 1000;
				var secret_code=getRandomString(0);
				var Private="Private";
				var privatehtml='<div class="text_row">'+
					'<div class="float_l">'+
						'<div class="privatedatabox">'+
							'<input type="text" class="manage_textbox_pad" id="user_hashname'+value+'" name="user_hashname'+value+'" value="'+user_hashname+'"/>'+
							'<span id="ucodeTitle'+value+'">&nbsp;Unique Code:&nbsp;</span>'+
							'<input type="text" class="manage_textbox_pad" id="secret_code'+value+'"  name="secret_code'+value+'" value="'+secret_code+'">'+
							'<input type="hidden" id="pvtCodeClicks'+value+'" value="0">'+
							'<a href="javascript:void(0);" id="ucodeAnchor'+value+'"><img style="margin-top:5px;" src="'+BASE_PATH+'/images/social_media/refresh.png" onclick="refreshUcode('+value+')" /></a>'+
							'<span id="refreshingCodeDiv'+value+'" style="display:none;"><img style="vertical-align:middle;width:15px;" src="'+BASE_PATH+'/images/social_media/bx_loader.gif" /></span>'+
						'</div>'+
					'</div>'+
					'<p>'+
						'<span>Private</span><input type="checkbox" name="private-'+value+'" id="private-'+value+'" value="Private" onclick="changePublicPrivate('+Private+','+value+')" checked />'+
					'</p>'+
				'</div>';
				$('#publicToprivate'+value).html(privatehtml);
			}
		}
	}
	
	function randomString( requiredLength )
	{
		var lettersSet = 'abcdefghijklmnopqrstuvwxyz';
		var numbersSet = '0123456789';
		
		var codeLength = 0;
		if( parseInt(requiredLength) >= 7 )
		{
			codeLength = parseInt(requiredLength);
		}
		else
		{
			codeLength = Math.floor(Math.random() * (7 - 5 + 1)) + 5;
		}
		
		var numberA = Math.floor(Math.random() * ((codeLength-1) - 0 + 1)) + 0;
		var numberB = Math.floor(Math.random() * ((codeLength-1) - 0 + 1)) + 0;
		while( (numberA == numberB) || ((numberA+1) == numberB) || ((numberA-1) == numberB) )
		{
			numberB = Math.floor(Math.random() * ((codeLength-1) - 0 + 1)) + 0;
		}
		var numberC = 0;
		if( parseInt(requiredLength) >= 8 )
		{
			var numberC = Math.floor(Math.random() * ((codeLength-1) - 0 + 1)) + 0;
			while( (numberB == numberC) || ((numberB+1) == numberC) || ((numberB-1) == numberC) )
			{
				numberC = Math.floor(Math.random() * ((codeLength-1) - 0 + 1)) + 0;
			}
		}
		
		var randomString = '';
		for( var i = 0; i < codeLength; i++ )
		{
			if( parseInt(requiredLength) >= 8 )
			{
				if( (i == numberA) || (i == numberB)  || (i == numberC) )
				{
					var randomPoz = Math.floor(Math.random() * numbersSet.length);
					randomString += numbersSet.substring(randomPoz,randomPoz+1);
				}
				else
				{
					var randomPoz = Math.floor(Math.random() * lettersSet.length);
					randomString += lettersSet.substring(randomPoz,randomPoz+1);
				}
			}
			else
			{
				if( (i == numberA) || (i == numberB) )
				{
					var randomPoz = Math.floor(Math.random() * numbersSet.length);
					randomString += numbersSet.substring(randomPoz,randomPoz+1);
				}
				else
				{
					var randomPoz = Math.floor(Math.random() * lettersSet.length);
					randomString += lettersSet.substring(randomPoz,randomPoz+1);
				}
			}
		}
		
		return randomString ;
	}
	
	function refreshUcode(value){
		$('#refreshingCodeDiv'+value).show();

		$('#databoxChanged'+value).val( 1 );

		$('#pvtCodeClicks'+value).val( parseInt($('#pvtCodeClicks'+value).val()) + parseInt("1") )
		var requiredLength = 0;
		if( parseInt($('#pvtCodeClicks'+value).val()) > parseInt("10") )
		{
			requiredLength = 12;
		}
		else if( (parseInt($('#pvtCodeClicks'+value).val()) >= 5) && (parseInt($('#pvtCodeClicks'+value).val()) <= 10) )
		{
			requiredLength = parseInt($('#pvtCodeClicks'+value).val()) + parseInt("2");
		}
		else
		{
			requiredLength = 0;
		}
		$('#secret_code'+value).val(getRandomString(requiredLength));
		$('#refreshingCodeDiv'+value).hide();
	}

	function refreshUcodeDatabox()
	{
		$('#refreshingCodeDiv').show();
		
		$('#pvtCodeClicks').val( parseInt($('#pvtCodeClicks').val()) + parseInt("1") )

		var requiredLength = 0;
		if( parseInt($('#pvtCodeClicks').val()) > parseInt("10") )
		{
			requiredLength = 12;
		}
		else if( (parseInt($('#pvtCodeClicks').val()) >= 5) && (parseInt($('#pvtCodeClicks').val()) <= 10) )
		{
			requiredLength = parseInt($('#pvtCodeClicks').val()) + parseInt("2");
		}
		else
		{
			requiredLength = 0;
		}

		$('#ucHolder').val( getRandomString(requiredLength) );

		$('#refreshingCodeDiv').hide();
	}
	function showBrowseClick(imagelinkId){
		$('#pop-up-image-crop-new').popUpWindow({action: "open"});
		$('#cropategoryId').val(imagelinkId);
	}
	//DASH BOARD CROP OLD
/*	
	function showImage(input,value){
		var crop_width=0;
		var crop_height=0;
		var file, img;
		if ((file = input.files[0])) {
			img = new Image();
			img.src = _URL.createObjectURL(file);
			img.onload = function() {
				crop_width=this.width;
				crop_height=this.height;
				cropImageDashboard(crop_width,crop_height,img.src,value);
			};
		}
	}
	function cropImageDashboard(width,height,src,value){
		//alert(width+'-'+height);
		crop_value=value;
		if(width<=255 && height<=300){
			$('#dataBoxImage'+value).attr('src', src);
		}else if(width>255 && height<=300){
			$('#dataBoxImage'+value).attr('src', src);
		}else if(width<=255 && height>300){
			$('#dataBoxImage'+value).attr('src', src);
		}else{
			$('.resize-image').attr('src', src);
			$.getScript( BASE_PATH+"/js/cropjs/jquery-2.1.1.min.js" ).done(function( script, textStatus ) {
				$.getScript( BASE_PATH+"/js/cropjs/component.js" ).done(function( script, textStatus ) {
					$('#pop-up-image-crop').show();
				});
			});
		}
	}
	function dashBoardCrop(src){
		crop_value_count++;
		var fetchUploadedUrl = BASE_URL+'/upload-montage-image';
		$.ajax({
			type	: "POST",
			url		: fetchUploadedUrl,
			data	: { data: src,page:'dash',catId:crop_value},
			success: function(data){
				$('#pop-up-image-crop').hide();
				$('#dataBoxImage'+crop_value).attr('src', src);
				$('#imageId'+crop_value).val(data.imageId);
				$('#typeImageCrop'+crop_value).val(1);
			}
		});
	}*/
	//END
	function deleteDatabox(userCategoryId){
		var result = confirm("Are you sure want to delete data box?");
		if (result==true) {
			$.ajax({
				type		:	'POST',
				url			:  	BASE_URL+'/update-dash-board',
				data		:	{type:'delete',userCategoryId:userCategoryId},
				success: function(data){
					window.location='dashboard';
				}
			});
		}
	}
function voteRelevance( newVoteValue,categoryId )
	{
		var voteUrl = BASE_URL + "/databox/vote-on-relevance";
		$.ajax({
		  url: voteUrl,
		  type: "POST",
		  data:{newVoteValue:newVoteValue,categoryId:categoryId},
		  async: false,
		  success: function(data) {
		  
			var takeTopImgName = BASE_PATH + "/images/social_media/upparrow.png";
			var listDownImgName = BASE_PATH + "/images/social_media/downarrow_enable.png";
			var upDisabledImgName = BASE_PATH + "/images/social_media/upparrow_disable.png";
			var downDisabledImgName = BASE_PATH + "/images/social_media/downarrow.png";
			
			var newVoteLinkHtml = "";
			if( parseInt(newVoteValue) == parseInt("1") )
			{
				newVoteLinkHtml = '<img class="upparrow" src="' + upDisabledImgName + '" />';
				newVoteLinkHtml += '<a href="JavaScript:void(0);" onClick="voteRelevance(0,' + categoryId + ');" ><img class="downarrow" src="' + listDownImgName + '" /></a>';
				$( '#relevanceDiv' + categoryId ).html( newVoteLinkHtml );
			}
			if( parseInt(newVoteValue) == parseInt("0") )
			{
				newVoteLinkHtml = '<a href="JavaScript:void(0);" onClick="voteRelevance(1,' + categoryId + ');" ><img class="upparrow" src="' + takeTopImgName + '" /></a>';
				newVoteLinkHtml += '<img class="downarrow" src="' + downDisabledImgName + '" />';
				$( '#relevanceDiv' + categoryId ).html( newVoteLinkHtml );
			}
		
		  }
		});
	}
	
function voteWorth( newVoteValue,categoryId )
	{
		
		var voteUrl = BASE_URL + "/databox/vote-on-worth";
		$.ajax({
		  url: voteUrl,
		  type: "POST",
		  data:{newVoteValue:newVoteValue,categoryId:categoryId},
		  async: false,
		  success: function(data) {
		  
			var takeTopImgName = BASE_PATH + "/images/social_media/upparrow.png";
			var listDownImgName = BASE_PATH + "/images/social_media/downarrow_enable.png";
			var upDisabledImgName = BASE_PATH + "/images/social_media/upparrow_disable.png";
			var downDisabledImgName = BASE_PATH + "/images/social_media/downarrow.png";

			var newVoteLinkHtml = "";
			if( parseInt(newVoteValue) == parseInt("1") )
			{
				newVoteLinkHtml = '<img class="upparrow" src="' + upDisabledImgName + '" />';
				newVoteLinkHtml += '<a href="JavaScript:void(0);" onClick="voteWorth(0,' + categoryId + ');" ><img class="downarrow" id="worthStatusImg' + categoryId + '" src="' + listDownImgName + '" /></a>';
				$( '#worthDiv' + categoryId ).html( newVoteLinkHtml );
			}
			if( parseInt(newVoteValue) == parseInt("0") )
			{
				newVoteLinkHtml = '<a href="JavaScript:void(0);" onClick="voteWorth(1,' + categoryId + ');" ><img class="upparrow" id="worthStatusImg' + categoryId + '" src="' + takeTopImgName + '" /></a>';
				newVoteLinkHtml += '<img class="downarrow" src="' + downDisabledImgName + '" />';
				$( '#worthDiv' + categoryId ).html( newVoteLinkHtml );
			}
			
		  }
		});
	}
	
// montage page report start	
function montReportPopUpShow( categoryID,userID )
{
	$('#category_user_id').val(userID);
	$('#hid_cat_id').val(categoryID);
	$('#repMessage').html('');
	$("#personel_text").val('');
	$('input:radio[name=Personel]:first').attr('checked', true);
	$('#pop-up-report').popUpWindow({action: "open"});
			$('.pop-up-content').css('background','none');
	$('.pop-up-content').css('box-shadow','none');
}
function montReportSend()
{
	var categoryId=$('#hid_cat_id').val();
	custReportSend( categoryId );
}
// montage page report end	

// customization page report start	
function custReportPopUpShow()
	{
		$('#repMessage').html('');
		$("#personel_text").val('');
		$('input:radio[name=Personel]:first').attr('checked', true);
		$('#pop-up-report').popUpWindow({action: "open",size:"small"});
		//$('.pop-up-content').css('background','none');
		$('.pop-up-content').css('box-shadow','none');
	}
function custReportSend( categoryId )
	{
		var reportContent = $.trim( stripHtmlTags($("#personel_text").val()).replace(/&nbsp;/g,'') );
		reportContent = reportContent.trim();
		
		var reportVal = $('input:radio[name=Personel]:checked').val();
		if( parseInt(reportVal) == parseInt("5") )
		{
			if( reportContent == "" )
			{
				$('#repMessage').html('Please enter other content.');
				return false;
			}
		}
		else if(  parseInt(reportVal) == parseInt("1")  )
		{
			reportContent = "Spam Content";
		}
		else if(  parseInt(reportVal) == parseInt("2")  )
		{
			reportContent = "Abusive / hateful content";
		}
		else if(  parseInt(reportVal) == parseInt("3")  )
		{
			reportContent = "Nude / Vulgar Open content";
		}
		else if(  parseInt(reportVal) == parseInt("4")  )
		{
			reportContent = "Personal defamatory content";
		}

		$('#repMessage').html('Sending your report...');
		var reportedUserId = $('#category_user_id').val();
		var contactUrl = BASE_URL + "/admin/cust-report";
		$.ajax({
			  url: contactUrl,
			  type: "POST",
			  data:{userID:reportedUserId,categoryId:categoryId,content:reportContent},
			  success: function(data) {
				$('#repMessage').html('');
				$('#pop-up-report').popUpWindow({action: "close"});
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html("Thanks, Your report sent.");
			  }
			});
	}
// customization page report end	
	
// contact admin start
function contactPopUpShow()
	{
		$('#contactMessage').html('');
		$('#contact-pop-up-allpages').popUpWindow({action: "open"});
	}
function contactPopUpCancel()
	{
		$('#contact-pop-up-allpages').popUpWindow({action: "close"});
	}
function sendContactContent()
	{
		var contactSubject = $.trim( stripHtmlTags($("#contact_subject").val()).replace(/&nbsp;/g,'') );
		contactSubject = contactSubject.trim();
		var contactContent = $.trim( stripHtmlTags($("#contact_content").val()).replace(/&nbsp;/g,'') );
		contactContent = contactContent.trim();
		
		// alert(contactContent);
		if( contactContent == "" )
		{
			$('#contactMessage').html('Please enter your content');
			return false;
		}
		$('#contactMessage').html('Submitting your content...');
		var contactUrl = BASE_URL + "/admin/contact-admin";
		$.ajax({
			  url: contactUrl,
			  type: "POST",
			  data:{contactSubject:contactSubject,contactContent:contactContent},
			  success: function(data) {
				$('#contactMessage').html('Your content sent.');
				$('#contactMessage').show().delay(3000).fadeOut();
				$('#contact-pop-up-allpages').popUpWindow({action: "close"});
			  }
			}); 
	}

//  contact admin end

function reportPopUp(categoryID,userID){
		$('#reportMessageSend').html('');
		$('#report_text').val('');
		$('#hid_userid').val(userID);
		$('#hid_cat_id').val(categoryID);
		$('#pop-up-report').popUpWindow({action: "open"});
	}
function sendReportClose(){
		$('#pop-up-report').popUpWindow({action: "close"});
		$('#report_id_hid').val(0);
	}
// Send report 

function sendReport(){
	var flag=true;
	var userId=$('#hid_userid').val();
	var catId=$('#hid_cat_id').val();
	var reportText=$('#report_text').val();
	if(reportText==""){
		$('#reportMessageSend').html("Required report text");
		var flag=false;
	}
	if(flag==true){
	var voteUrl = BASE_URL + "/application/reports";
	if($('#report_id_hid').val()!=0){
		var option='update';
		var reportId=$('#report_id_hid').val();
	}else{
		var option='insert';
		var reportId=0;
	}
		$.ajax({
			  url: voteUrl,
			  type: "POST",
			  data:{userID:userId,categoryId:catId,content:reportText,option:option,reportId:reportId},
			  success: function(data) {
				if(data.output==1){
					$('#reportMessageSend').html('Report send successfully');
					$('#reportMessageSend').show().delay(1000).fadeOut();
					$('#pop-up-report').popUpWindow({action: "close"});
					$('#report_id_hid').val(0);
				}
			  }
			}); 
		}
	}
function disableDatabox(catId,val){
		if(val=='0'){
			$('#hash_note'+catId).prop('disabled',true);
			$('#disableEnable'+catId).html('<a href="javascript:void(0);" class="larg_btn" onclick="disableDatabox('+catId+',1)">Enable comments</a>');
		}else{
			$('#hash_note'+catId).prop('disabled',false);
			$('#disableEnable'+catId).html('<a href="javascript:void(0);" class="larg_btn" onclick="disableDatabox('+catId+',0)">Disable comments</a>');
		}
		
	}
function getDimensions(id) {
		var element = document.getElementById(id);
		if (element && element.tagName.toLowerCase() == 'img') {
			return {
				width: element.width,
				height: element.height
			};
		}
	}
	
	
	function saveCustCatDesc()
	{
		$('textarea[id="catDescCkeditor"]').val(CKEDITOR.instances.catDescCkeditor.getData());
		var custCatDescText=$("#catDescCkeditor").val().replace("<p>","");
		custCatDescText=custCatDescText.replace("</p>","");
		if( navigator.userAgent.toLowerCase().indexOf('chrome') == -1 )
		{
			custCatDescText=custCatDescText.replace(/&nbsp;/g,'');
		}
		if( parseInt(custCatDescText.length) > parseInt("2000") )
		{
			alert( "Maximum limit 2000 characters for description" );
			return false;
		}
		
		if( $('#categoryDescripton').html() != custCatDescText )
		{
			$('#viewPageChanged').val( 1 );
			$('#catDescChanged').val( 1 );

			$('#catDescIntro').html( $.trim(custCatDescText) + "&nbsp;" + "Edit" );

			var catDescIntroDiv = document.getElementById('catDescIntro');
			if( $('#hiddenDec').val()!="" )
			{
				var fullDescParaHtml = '';
				fullDescParaHtml += '<span id="categoryDescripton">' + $.trim(custCatDescText) + '</span>&nbsp;';
				fullDescParaHtml += '<span id="categoryDescLink"><a href="Javascript:void(0);" id="catDescAnchor" onclick="catDescCkEditorShow();">Edit</a></span>';
				$('#fullDescPara').html( fullDescParaHtml );

				if( ($('#fullCatDesc').length) && ($('#fullCatDesc').getNiceScroll()) )
				{
					$('#fullCatDesc').getNiceScroll().resize();
				}

				$('#view_vertical_hid_id').val( 1 );
				$('.view_vertical_relavence_btn_circle').css('display','inline-block');
				$('.view_vertical_relavence_btn_circle').click();
			}
			else
			{
				$('#fullDescPara').html( "" );
				$('.view_vertical_relavence_btn_circle').css('display','none');
				var introDescParaHtml = '<span id="categoryDescripton">' + $.trim(custCatDescText) + '</span>&nbsp;'; 
				introDescParaHtml += '<span id="categoryDescLink"><a href="Javascript:void(0);" id="catDescAnchor" onclick="catDescCkEditorShow();">Edit</a></span>';
				$('#catDescIntro').html( introDescParaHtml );
				$('.add_comment_bg').css('height','68px')
				$('#fullCatDesc').hide();
				$('#catDescIntro').show();
				$('.view_vertical_hg').css('margin-top','136px');
			}

			if( $.trim(custCatDescText) == "" )
			{
				$('#catDescAnchor').html( "Set Description" );
			}
			else
			{
				$('#catDescAnchor').html( "Edit" );
			}
			$('#catDescTopBtn').html($('#catDescAnchor').html().toUpperCase());
		}
		$('#pop-up-cat-description').popUpWindow({action: "close"});

		return false;
	}
	
	function catDescCkEditorCancel()
	{
		$('#pop-up-cat-description').popUpWindow({action: "close"});
		return false;
	}

	function catDescCkEditorShow()
	{
		if( parseInt($('#catDescCkEditorYes').val()) == parseInt("0") )
		{
			$('#catDescCkEditorYes').val( 1 );
			CKEDITOR.replace( 'catDescCkeditor',{} );
		}
		
		CKEDITOR.instances.catDescCkeditor.setData( $('#categoryDescripton').html() );
		$('#pop-up-cat-description').popUpWindow({action: "open"});
		return false;
	}

	function custCommentCkEditorCancel()
	{
		$('#commentMode').val( 0 );
		$('#pop-up-comment').popUpWindow({action: "close"});
		return false;
	}

	function custCommentCkEditorShow( existingValue,mode )
	{
		if( parseInt($('#viewPageYes').val()) == parseInt("0") )
		{
			$('#viewPageYes').val( 1 );
			CKEDITOR.replace( 'commentCkeditor',{customConfig: BASE_PATH + '/js/ckeditor/comm-config.js'} );
		}
		
		$('#commentMode').val( mode );
		
		CKEDITOR.instances.commentCkeditor.setData( existingValue );
		$('#pop-up-comment').popUpWindow({action: "open"});
		$('.pop-up-content').css('position','fixed');
		$('.pop-up-content').css('top','35%');
		return false;
	}
	
	function showActualComment()
	{
		
		$('textarea[id="commentCkeditor"]').val(CKEDITOR.instances.commentCkeditor.getData());
		var commentText=$("#commentCkeditor").val().replace("<p>","");
		commentText=commentText.replace("</p>","");
		
		if( parseInt($('#commentMode').val()) == parseInt("0") )
		{
			if( typeof(tzGridStackRef) == "object" )
			{
				newCommentText = commentText;
				tzGridStackRef.add_new_widget();
			}
			else
			{
				alert( "Adding comment not available presently." );
			}
		}
		else if( parseInt($('#commentMode').val()) > parseInt("0") )
		{
			commentBoxId = $('#commentMode').val();

			if( $('#data_'+commentBoxId).html() != commentText )
			{
				$('#data_'+commentBoxId).html( commentText );
				$('#viewPageChanged').val( 1 );
				
				/*
				$('#dragDropWindow'+commentBoxId).css('width','300px');
				$('#dragDropWindow'+commentBoxId).css('height','100px');
				$('#dragDropWindow'+commentBoxId).css('left','37%');

				// alert( $('#dragDropWindow'+commentBoxId).prop('scrollHeight') );
				if( parseInt($('#dragDropWindow'+commentBoxId).prop('scrollHeight')) > parseInt("104") && parseInt($('#dragDropWindow'+commentBoxId).prop('scrollHeight')) <= parseInt("236") )
				{
					$('#dragDropWindow'+commentBoxId).css('width','412px');
					$('#dragDropWindow'+commentBoxId).css('height','162px');
					$('#dragDropWindow'+commentBoxId).css('left','240px');
				}
				else if( parseInt($('#dragDropWindow'+commentBoxId).prop('scrollHeight')) > parseInt("236") )
				{
					$('#dragDropWindow'+commentBoxId).css('width','592px');
					$('#dragDropWindow'+commentBoxId).css('height','210px');
					$('#dragDropWindow'+commentBoxId).css('left','209px');
				}
				*/
			}

			$('#commentMode').val( 0 );
		}
		$('#pop-up-comment').popUpWindow({action: "close"});
		
		return false;
	}
	
	function editExistingComment( commentBoxId )
	{
		var existingCommentText = $('#data_'+commentBoxId).html();
		custCommentCkEditorShow( existingCommentText,commentBoxId );
		
		return false;
	}

	//View-Vertical---View Horizontal
function AddDiv(  commentText  ) {
	var incrValue=$('#incrId').val();
	var newValue=parseInt(incrValue)+1;
	$('#incrId').val(newValue);
	var valueSocialAloneH ='<input type="hidden" id="valueSocialAlone'+newValue+'" value="0">';
	
	var newTextBoxDiv = $(document.createElement('div')).attr(
																{
																	id				:	'dragDropWindow'+newValue,
																	class			:	'window',
																	style			:	'cursor:pointer;border:1px solid #d2d2d2;height:100px;width:300px;background:#f1f1f1;padding:5px;font-weight:bold;left:37%;top:45%;position:fixed;'
																}
															
															);

	var editImage = "";
	newTextBoxDiv.html('<div id="deleteComment'+newValue+'" style="position:absolute;top:-23px;right:-1px;background:#f1f1f1;border:1px solid #bfc4c8;width:50px;"><span style="margin-right:10px;"><a href="javascript:void(0)"; onclick="editExistingComment('+newValue+')"><img src="'+BASE_PATH+'/images/social_media/edit_ico.png"/></a></span><a href="javascript:void(0)"; onclick="deleteComment('+newValue+')" style="color:#f97e76" title="Close">X</a></div><span id="data_'+newValue+'" class="comment_text_u">'+commentText+'</span>'+valueSocialAloneH);
	newTextBoxDiv.appendTo("#drag-drop-demo");
	
	if( parseInt($('#dragDropWindow'+newValue).prop('scrollHeight')) > parseInt("104") && parseInt($('#dragDropWindow'+newValue).prop('scrollHeight')) <= parseInt("236") )
	{
		$('#dragDropWindow'+newValue).css('width','412px');
		$('#dragDropWindow'+newValue).css('height','162px');
		$('#dragDropWindow'+newValue).css('left','240px');
	}
	else if( parseInt($('#dragDropWindow'+newValue).prop('scrollHeight')) > parseInt("236") )
	{
		$('#dragDropWindow'+newValue).css('width','592px');
		$('#dragDropWindow'+newValue).css('height','210px');
		$('#dragDropWindow'+newValue).css('left','209px');
	}
	$('#viewPageChanged').val( 1 );
	
	var commMinAllowedWidth = 0;
	var commMinAllowedHeight = 0;
	var commScrollHTracker = false;
	var commScrollVTracker = false;
	
	if( window.location.href.indexOf("post-horizontal") > -1 )
	{
		$("#dragDropWindow"+newValue).resizable({
			resize: function(event, div) {
				var ridDiv=div.element.context.id;
				// Bhargav added: The following is to control comment.
				if( $('#'+ridDiv).attr('class').indexOf('items007') == -1 )
				{
					$('#'+ridDiv).addClass( "cust_comm_overflow" );
					if( commScrollHTracker )
					{
						if( div.size.height <= commMinAllowedHeight )
						{
							div.size.height = commMinAllowedHeight;
						}
						else
						{
							commScrollHTracker = false;
							commScrollVTracker = false;
						}
					}
					else
					{
						if( commScrollVTracker )
						{
							commScrollHTracker = true;
							commMinAllowedHeight = div.size.height + 5;
							div.size.height = commMinAllowedHeight;
						}
					}

					if( commScrollVTracker )
					{
						if( div.size.width <= commMinAllowedWidth )
						{
							div.size.width = commMinAllowedWidth;
						}
						else
						{
							commScrollVTracker = false;
							commScrollHTracker = false;
						}
					}
					else
					{
						if( commScrollHTracker )
						{
							commScrollVTracker = true;
							commMinAllowedWidth = div.size.width + 5;
							div.size.width = commMinAllowedWidth;
						}
					}

					if( document.getElementById(ridDiv).scrollHeight - $('#'+ridDiv).innerHeight() > 5  )
					{
						if( ! commScrollHTracker )
						{
							commScrollHTracker = true;
							commMinAllowedHeight = div.size.height + 5;
							div.size.height = commMinAllowedHeight;
						}
					}
					if( document.getElementById(ridDiv).scrollWidth - $('#'+ridDiv).innerWidth() > 5  )
					{
						if( ! commScrollVTracker )
						{
							commScrollVTracker = true;
							commMinAllowedWidth = div.size.width + 5;
							div.size.width = commMinAllowedWidth;
						}
					}
				}
				// End Added by bhargav.
			},
			stop: function( event, div )
			{
				var idDiv=div.element.context.id;
				$('#'+idDiv).removeClass( "cust_comm_overflow" );
			}
		});
	}
	
	
	if(window.location.href.indexOf("post-vertical") > -1) {
		$("#dragDropWindow"+newValue).resizable({
			resize: function(event, div) {
				var rdemoWidth = $("#drag-drop-demo").width();
				var ridDiv=div.element.context.id;
				var rboxWidth = $('#'+ridDiv).width();
				if( div.position.left > (rdemoWidth-rboxWidth)) {
					if(rcount==1){
						rwidth=div.size.width;
						$("#"+ridDiv).width(rwidth);
					}else{
						$("#"+ridDiv).width(rwidth);
					}
					rcount++;
				}else{
					rwidth=div.size.width;
				}
				
				/*// Bhargav added: The following is to control comment.
				if( $('#'+ridDiv).attr('class').indexOf('items007') == -1 )
				{
					$('#'+ridDiv).addClass( "cust_comm_overflow" );
					if( commScrollHTracker )
					{
						if( div.size.height <= commMinAllowedHeight )
						{
							div.size.height = commMinAllowedHeight;
						}
						else
						{
							commScrollHTracker = false;
							commScrollVTracker = false;
						}
					}
					else
					{
						if( commScrollVTracker )
						{
							commScrollHTracker = true;
							commMinAllowedHeight = div.size.height + 5;
							div.size.height = commMinAllowedHeight;
						}
					}

					if( commScrollVTracker )
					{
						if( div.size.width <= commMinAllowedWidth )
						{
							div.size.width = commMinAllowedWidth;
						}
						else
						{
							commScrollVTracker = false;
							commScrollHTracker = false;
						}
					}
					else
					{
						if( commScrollHTracker )
						{
							commScrollVTracker = true;
							commMinAllowedWidth = div.size.width + 5;
							div.size.width = commMinAllowedWidth;
						}
					}

					if( document.getElementById(ridDiv).scrollHeight - $('#'+ridDiv).innerHeight() > 5  )
					{
						if( ! commScrollHTracker )
						{
							commScrollHTracker = true;
							commMinAllowedHeight = div.size.height + 5;
							div.size.height = commMinAllowedHeight;
						}
					}
					if( document.getElementById(ridDiv).scrollWidth - $('#'+ridDiv).innerWidth() > 5  )
					{
						if( ! commScrollVTracker )
						{
							commScrollVTracker = true;
							commMinAllowedWidth = div.size.width + 5;
							div.size.width = commMinAllowedWidth;
						}
					}
				}*/
				// End Added by bhargav.
			},
			stop: function( event, div )
			{
				var idDiv=div.element.context.id;
				$('#'+idDiv).removeClass( "cust_comm_overflow" );
			}
		});
		//jsPlumb.draggable("dragDropWindow"+newValue, {
		$("#dragDropWindow"+newValue).multidraggable({
		start: function(event, ui) { $(this).css("z-index", 979); },
			drag: function(event, ui) {	
				rcount=0;
				$('#viewPageChanged').val( 1 );
				if( ui.position.left < 0){ ui.position.left=0;}
				if( ui.position.top < 75){ ui.position.top=75;}
				var demoWidth = $("#drag-drop-demo").width();
				var idUi=ui.helper.context.attributes.id;
				for(var key in idUi) {
					if(key=='value'){
						var idDvv=idUi[key];
					}
				}
				var boxWidth = $('#'+idDvv).width();
				if( ui.position.left > (demoWidth-boxWidth)) {
					ui.position.left= (demoWidth-boxWidth);
				}
			},
		stop: function(event, ui) { $(this).css("z-index", ""); }
		});
		$('#dragDropWindow'+newValue).css('position', 'absolute');
		var dialog = $( '#dragDropWindow'+newValue );
		dialog.position({
			my: "center",
			at: "center",
			of: window
		});
		//});
	}else{
		$(".window").resizable();
		$("#dragDropWindow"+newValue).multidraggable({
			axis:'x',
			start: function(event, ui) { $(this).css("z-index", 979); },
			drag: function(event, ui) {
				$('#viewPageChanged').val( 1 );
			},
			stop: function(event, ui) { $(this).css("z-index", ""); }
		});
		$('#dragDropWindow'+newValue).css('position', 'absolute');
		$( '#dragDropWindow'+newValue ).css('left',scrollleft+500);
	}
	
}


function savePositions(categoryId){

	var incrId=$('#incrId').val();
	var pubDddContainerHtml = $('#pubDddContainer').html();
	
	$('#tempRemDiv').html( pubDddContainerHtml );
	$('#tempRemDiv .ui-icon-gripsmall-diagonal-se').remove();
	
	pubDddContainerHtml = $('#tempRemDiv').html();

	$.ajax({
		url		: 	BASE_URL+'/databox/view-vertical',
		type	: 	"POST",
		data	:	{DivHtml:pubDddContainerHtml,catId:categoryId,incrId:incrId,user_id:$('#category_user_id').val()},
		success	: function(data) {
			$('#tempRemDiv').empty();
		
			if( parseInt($('#catDescChanged').val()) == parseInt("1") )
			{
				var catNewDesc = $('#categoryDescripton').html();
				$.ajax({
					url		: 	BASE_URL+'/databox/update-cat-desc',
					type	: 	"POST",
					data	:	{categoryId:categoryId,catNewDesc:catNewDesc},
					success	: function(data) {
						$('#catDescChanged').val( 0 );
						$('#viewPageChanged').val( 0 );
						$('#pop-up-alerts').popUpWindow({action: "open"});	
						$("#alert_header_meassge").html('');
						$("#alert_meassage").html(saved_success);
						$(".pop-up-content").css('position','fixed');
						$(".pop-up-content").css('width','30%');
					}
				});
			}
			else if( parseInt($('#catDescChanged').val()) == parseInt("0") )
			{
				$('#viewPageChanged').val( 0 );
				$('#pop-up-alerts').popUpWindow({action: "open"});	
				$("#alert_header_meassge").html('');
				$("#alert_meassage").html(saved_success);
				$(".pop-up-content").css('position','fixed');
				$(".pop-up-content").css('width','30%');
			}
		}
	});
	
	
}
function deleteComment(idValue){
	$('#viewPageChanged').val( 1 );
	$('#dragDropWindow'+idValue).remove();
}
function addFocus(idValue){
	$('#data_'+idValue).focus();
}

function relAndWorthCustomization( newVoteValue,categoryId,type,totalUp,totalDown,status ){
	if(type==0){
		var typeUrl='vote-on-worth';
		var value=0;
	}else{
		var typeUrl='vote-on-relevance';
		var value=1;
	}
	var voteUrl = BASE_URL + "/databox/" + typeUrl;
	$.ajax({
		url: voteUrl,
		type: "POST",
		data:{newVoteValue:newVoteValue,categoryId:categoryId},
		async: false,
		success: function(data){
			var takeTopImgName = BASE_PATH + "/images/social_media/upparrow.png";
			var listDownImgName = BASE_PATH + "/images/social_media/downarrow_enable.png";
			var upDisabledImgName = BASE_PATH + "/images/social_media/upparrow_disable.png";
			var downDisabledImgName = BASE_PATH + "/images/social_media/downarrow.png";
			if( parseInt(newVoteValue) == parseInt("1") ){
				totalUp++;
				if(status!=2){
					totalDown--;
				}
				$( '#upR'+type ).html( totalUp );
				$( '#up'+type ).html( '<img src="' + upDisabledImgName + '" />' );
				$( '#downR'+type ).html( totalDown );
				$( '#down'+type ).html( '<a href="JavaScript:void(0);" onClick="relAndWorthCustomization(0,' + categoryId + ','+value+','+totalUp+','+totalDown+',1);" ><img  src="' + listDownImgName + '" /></a>');
			}
			if( parseInt(newVoteValue) == parseInt("0") ){
				if(status!=2){
					totalUp--;
				}
				totalDown++;
				$( '#upR'+type ).html( totalUp );
				$( '#up'+type ).html( '<a href="JavaScript:void(0);" onClick="relAndWorthCustomization(1,' + categoryId + ','+value+','+totalUp+','+totalDown+',0);" ><img src="' + takeTopImgName + '" /></a>' );
				$( '#downR'+type ).html( totalDown );
				$( '#down'+type ).html( '<img src="' + downDisabledImgName + '" />' );
			}
		}
	});
}
$(document).ready(function() {
	if($(window).width()==1920){
		$('.wrapper').css('min-height','97.3%');
	}
});
function openCloseShareCus(){
	var openCloseValue=$('#countOpenCloseShare').val();
	if(openCloseValue=='0'){
		//$('.at-share-tbx-element .at-share-btn').css('margin-top','13px');
		$('#atstbx').css('margin-left','54px');
		$('#atstbx').css('width','260px');
		$('#social_sharing_btn').css('position','absolute');
		$('#social_sharing_btn').css('bottom','-32px');
		$('#social_sharing_btn').css('margin-left','8%');
		$('.c_BtnShare').css('z-index','1000');
		$('.addthis_sharing_toolbox').show();
		$('#countOpenCloseShare').val('1');
	}else{
		$('.addthis_sharing_toolbox').hide();
		$('#countOpenCloseShare').val('0');
	}
  }
  function updatemontageImage(){
		//$('#crop_heigh').removeClass('cropit-image-preview');
		//$('#crop_heigh').addClass('cropit-image-preview-montage');
		$('#pop-up-image-crop-new').popUpWindow({action: "open"});
	}
//END	

function removeMSelection(){
	$('div.window').each(function(i, slide){
	  $(this).removeClass('ui-multidraggable');
	});
}

function checkImageLoaded()
{
	var filename = $('#fileCropInp').val();
	
	var invalidExtension = false;
	
	if( filename.trim() != "" )
	{
		filename = filename.toLowerCase();
		var fnameArray = filename.split( "." );
		if( parseInt(fnameArray.length) > 0 )
		{
			if( (fnameArray[fnameArray.length-1] != "gif") && (fnameArray[fnameArray.length-1] != "jpg") && (fnameArray[fnameArray.length-1] != "jpeg") && (fnameArray[fnameArray.length-1] != "bmp") && (fnameArray[fnameArray.length-1] != "png") && (fnameArray[fnameArray.length-1] != "bpg") && (fnameArray[fnameArray.length-1] != "tiff") && (fnameArray[fnameArray.length-1] != "ppm") && (fnameArray[fnameArray.length-1] != "pgm") && (fnameArray[fnameArray.length-1] != "pbm") && (fnameArray[fnameArray.length-1] != "pnm") && (fnameArray[fnameArray.length-1] != "webp") )
			{
				invalidExtension = true;
			}
		}
		if( invalidExtension )
		{
			alert( crop_input_valid_file );
			$('#fileCropInp').value='';
			return false;
		}
	}
	return true;
}

function bakGetUrls( bkSoChKeyObject )
{
	for( var bkSoChKeyObjKey in bkSoChKeyObject )
	{
		bkSoChKeyObjKeyObj = bkSoChKeyObject[bkSoChKeyObjKey];
		if( bkSoChKeyObjKeyObj.hasOwnProperty("url") )
		{
			return true;
		}
		if( bkSoChKeyObjKeyObj.hasOwnProperty("children") )
		{
			var bkSfObject = bkSoChKeyObjKeyObj["children"];
			return bakGetUrls( bkSfObject );
		}
	}
}

function jsonGetUrls( jfSoChKeyObject )
{
	for( var jfSoChKeyObjKey in jfSoChKeyObject )
	{
		jfSoChKeyObjKeyObj = jfSoChKeyObject[jfSoChKeyObjKey];
		if( jfSoChKeyObjKeyObj.hasOwnProperty("uri") )
		{
			return true;
		}
		if( jfSoChKeyObjKeyObj.hasOwnProperty("children") )
		{
			var jsSfObject = jfSoChKeyObjKeyObj["children"];
			return jsonGetUrls( jsSfObject );
		}
	}
}

function reachJsonUri( bkmrksJsonObject )
{
	var actualBookmarkFound = false;
	
	if( bkmrksJsonObject.hasOwnProperty("children") )
	{
		var jsonFileChildrenObject = bkmrksJsonObject["children"];
		if( jsonFileChildrenObject.hasOwnProperty("0") )
		{
			var jfStartObject = jsonFileChildrenObject["0"];

			if( jfStartObject.hasOwnProperty("children") )
			{
				var jfSoChKeyObject = jfStartObject["children"];
				return jsonGetUrls( jfSoChKeyObject );
			}
		}
	}

	return actualBookmarkFound;
}

function reachBakUrl( bkmrksJsonObject )
{
	var actualBookmarkFound = false;

	if( bkmrksJsonObject.hasOwnProperty("roots") )
	{
		var bakFileRootsObject = bkmrksJsonObject["roots"];
		if( bakFileRootsObject.hasOwnProperty("bookmark_bar") )
		{
			var bkBookMarkBarObject = bakFileRootsObject["bookmark_bar"];

			var bakStartObject = bakFileRootsObject["bookmark_bar"];

			if( bakStartObject.hasOwnProperty("children") )
			{
				var bkSoChKeyObject = bakStartObject["children"];
				return bakGetUrls( bkSoChKeyObject );
			}
		}
	}

	return actualBookmarkFound;
}

function readBookMarksFile( bkmrksFile,onLoadCallback )
{
	if( window.File && window.FileReader && window.FileList && window.Blob )
	{
		if( bkmrksFile )
		{
			var r = new FileReader();
			r.onload = onLoadCallback;
			r.readAsText( bkmrksFile );
		}
		else
		{
			alert( "Failed to load file." );
			return false;
		}
	}
	else
	{
		alert( 'The File APIs are not fully supported by your browser.' );
	}
}

function isBkmrsFileValid( jsonExStr )
{
	var filename = $('#bookmarksFile').val();
	var bkmrksExtension = "";

	if( filename.trim() != "" )
	{
		filename = filename.toLowerCase();
		var fnameArray = filename.split( "." );
		if( parseInt(fnameArray.length) > 0 )
		{
			if( (fnameArray[fnameArray.length-1] == "json") || (fnameArray[fnameArray.length-1] == "bak") )
			{
				bkmrksExtension = fnameArray[fnameArray.length-1];
			}
		}
		
		var bookmarksFileValid = true;

		var bkmrksJsonObject = null;
		try
		{
			bkmrksJsonObject = jQuery.parseJSON( jsonExStr );

			if( bkmrksJsonObject )
			{
				// alert( "here" );
				if( bkmrksExtension == "json" )
				{
					return reachJsonUri( bkmrksJsonObject );
				}
				else if( bkmrksExtension == "bak" )
				{
					return reachBakUrl( bkmrksJsonObject );
				}
			}
			else
			{
				// alert( "Not Object" );
				return false;
			}
		}
		catch(err)
		{
			// alert( "error" );
			return false;
		}
	}

	// alert( "fr: " + bookmarksFileValid );
	return bookmarksFileValid;
}

function checkBkmrksUploaded()
{
	var filename = $('#bookmarksFile').val();
	
	var invalidExtension = false;
	var bkmrksExtension = "";
	
	if( filename.trim() != "" )
	{
		filename = filename.toLowerCase();
		var fnameArray = filename.split( "." );
		if( parseInt(fnameArray.length) > 0 )
		{
			if( (fnameArray[fnameArray.length-1] != "json") && (fnameArray[fnameArray.length-1] != "bak") )
			{
				invalidExtension = true;
			}
			else
			{
				bkmrksExtension = fnameArray[fnameArray.length-1];
			}
		}
		if( invalidExtension )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(bookmarks_valid_file);
			$('#bookmarksFile').value='';
			return false;
		}

		return true;
	}

	return false;
}


	function displayFooterContent( pageName )
	{
		var displayTermsUrl = BASE_URL;
		
		if( pageName == "terms" )
		{
			displayTermsUrl += "/display-terms";
			$("#termsLink").attr('target','_blank');
			$("#termsLink").attr('href',displayTermsUrl);
		}
		if( pageName == "pp" )
		{
			displayTermsUrl += "/display-privacy-policy";
			$("#ppLink").attr('target','_blank');
			$("#ppLink").attr('href',displayTermsUrl);
		}

		return false;
	}

	function showFpEmailIdForm()
	{
		window.location=BASE_URL + "/forgot-password";
	}

	function checkFpEmailId()
	{
		$('#forgotPwdSbmt').hide();
		$('#loadingFp').show();
		
		var emailId = $.trim( stripHtmlTags($('#fpEmailId').val()).replace(/&nbsp;/g,'') );
		$('#fpEmailId').val( emailId );
		if( emailId == "" )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(entered_email_id);
			$('#fpEmailId').focus();
			$('#loadingFp').hide();
			$('#forgotPwdSbmt').show();
			return false;
		}
		
		if( ! isInValidFormat(emailId)  )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(email_valid_format);
			$('#fpEmailId').focus();
			$('#loadingFp').hide();
			$('#forgotPwdSbmt').show();
			return false;
		}

		var forgotPasswordUrl = BASE_URL + "/forgot-password";
		$.ajax
		({
		  url: forgotPasswordUrl,
		  type: "POST",
		  data:{email:emailId},
		  async: false,
		  success: function(data)
		  {
			$('#loadingFp').hide();
			$('#forgotPwdSbmt').show();
			$('#forgotPwdSbmt').html( "You will receive password reset link on your registered email, if email registered." );
		  }
		});
	}

function getTpCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }
    else
    {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
        end = dc.length;
        }
    }
    return unescape(dc.substring(begin + prefix.length, end));
}	

	function checkSecondTp()
	{
		$('#tpName').html( "" );
		if( $('#socialLoginSecond').val() != ""  )
		{
			var socialLoginSecond = $('#socialLoginSecond').val();
			$('#socialLoginSecond').val( "" );
			var valGooglee=$('#checkGoogleLogin').val();
			var valGooglee1=$('#checkGoogleLogin1').val();
			if(valGooglee1==0){
				if(valGooglee==1){
					show_login_status( socialLoginSecond,true );
				}
			}
		}
	}
	
	function checkFpPassword()
	{
		$('#resetPwdSbmt').hide();
		$('#loadingFp').show();

		var newPassword = $.trim( stripHtmlTags($('#newPassword').val()).replace(/&nbsp;/g,'') );
		$('#newPassword').val( newPassword );
		if( newPassword == "" )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(new_fp_password);
			$('#newPassword').focus();
			$('#loadingFp').hide();
			$('#resetPwdSbmt').show();
			return false;
		}
		var confirmPassword = $.trim( stripHtmlTags($('#confirmPassword').val()).replace(/&nbsp;/g,'') );
		$('#confirmPassword').val( confirmPassword );
		if( confirmPassword == "" )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(confirm_fp_password);
			$('#confirmPassword').focus();
			$('#loadingFp').hide();
			$('#resetPwdSbmt').show();
			return false;
		}
		if( newPassword != confirmPassword )
		{
			$('#pop-up-alerts').popUpWindow({action: "open"});	
			$("#alert_header_meassge").html('');
			$("#alert_meassage").html(fp_no_match);
			$('#confirmPassword').focus();
			$('#loadingFp').hide();
			$('#resetPwdSbmt').show();
			return false;
		}
		var token = $('#hidtoken').val();

		var resetPasswordUrl = BASE_URL + "/databoxuser/reset-user-password";
		$.ajax
		({
		  url: resetPasswordUrl,
		  type: "POST",
		  data:{token:token,newPassword:newPassword},
		  async: false,
		  success: function(data)
		  {
			$('#loadingFp').hide();
			if( data.output == 1 )
			{
				$('#resetPwdSbmt').show();
				$('#resetPwdSbmt').html( "Your password reset successfully." );
			}
			else
			{
				$('#resetPwdSbmt').show();
				$('#resetPwdSbmt').html( "Not reset." );
			}
		  }
		});
		
	}
	

	function saveLinkCollection( catLinkId,currBoxUserId )
	{
		$.ajax({
			type		:	'POST',
			url			:  	BASE_URL+'/databox/my-collected-links',
			data		:	{catLinkId:catLinkId,currBoxUserId:currBoxUserId},
			success: function(data){
				if( data.output )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(user_link_collected);
					
					if( $('#tzUserCollCountSpan').length && $('#tzUserCollCountSpan').html().trim() != "" )
					{
						var newCollCount = parseInt($('#tzUserCollCountSpan').html().trim()) + parseInt("1");
						$('#tzUserCollCountSpan').html("").html(newCollCount);
					}
				}
			}
		});
			
		return false;
	}

	function deleteLinkCollection( collection_id )
	{
		$.ajax({
			type		:	'POST',
			url			:  	BASE_URL+'/databox/my-collected-links',
			data		:	{collection_id:collection_id},
			success: function(data){
				if( data.output )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html('Link deleted from your collection.');
					$("#collectedLink"+collection_id).remove();
				}
			}
		});
			
		return false;
	}

	function signUpWindow(){
	$('#pop-up-1').popUpWindow({action: "open"});
	$(".tg_users_title").text('Signup Using one of your Social network');
	$("#email_id_c").text('Signup using your email address');
	$("#c_password").text('Create a Password');
	$("#btnSubmit").text('SIGN UP');
	$(".crete_acc").fadeOut(100);
	$("#tgaccount").fadeIn(600);
	$("#forgot_password").fadeOut(100);
}
function loginWindow(){
	$(".tg_users_title").text('Login Using one of your Social network');
	$("#email_id_c").text('Your email ID');
	$("#c_password").text('Password');
	$("#btnSubmit").text('CONTINUE');
	$("#tgaccount").hide();
	$(".crete_acc").fadeIn(600);
	$("#forgot_password").fadeIn(600);
}
//SIGN UP 


