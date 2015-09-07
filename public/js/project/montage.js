	$(document).ready(function () {
		$("a[id^='montagePic']").each(function(){
			$('#montagePic').click(function(event){
				$('#change_image').click();
			});
		});
	});
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
			var text=$('#montage_hash_name').val();
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
			$.ajax({
				type		:	'POST',
				url			:  	BASE_URL+'/montage-hash-name',
				data		:	{text:text,type:name},
				success: function(data){
					if(data.output==1){
						if(typee==1){
							$('#pop-up-hash').popUpWindow({action: "close"});
							$('#hash_name').html('<div  class="float_l"><h2>#'+text+'</h2></div><div class="float_l"><a href="javascript:void(0);" onclick="montagePopUp(1)" title="Edit hash name" class="add_new_pick">EDIT</a></div>');
							$('#montage_hash_name').val(text);
						}else if(typee==2){
							$('#pop-up-title').popUpWindow({action: "close"});
							$('#your_title').html('<P><span><a href="javascript:void(0);" onclick="montagePopUp(2)" class="">EDIT TITLE&nbsp;&nbsp;&nbsp;&nbsp;</a></span>'+text+'</P>');
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
		var presentCount=$('#presentCount').val();
		if(type=='1'){
			var end=parseInt(presentCount)+7;
			var start=end-6;
		}else{
			var end=parseInt(presentCount)-7;
			var start=end-6;
		}
		$.ajax({
			type		:	'POST',
			url			:  	BASE_URL+'/pagination-montage-links',
			data		:	{type:type,catId:catId,start:start,end:end},
			success: function(data){
				$('#montageRow'+catId).html(data);
				$('#presentCount').val(end);
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
	function saveChanges(catId){

		var hashtagHolder = $("#user_hashname"+catId).val().trim();
		if( hashtagHolder == "" )
		{
			alert( "Please enter hash tag." );
			$("#user_hashname"+catId).focus();
			return false;
		}
		if( hashtagHolder != "" )
		{
			hashtagHolder = $("#user_hashname"+catId).val().replace(/^\s+/, "");
			hashtagHolder = hashtagHolder.replace(/\s+$/, "");
			var spc = " ";
			// alert( hashtagHolder.indexOf(spc) );
			if( parseInt(hashtagHolder.indexOf(spc)) > parseInt("-1") )
			{
				alert( "Space not allowed in hashtag." );
				window.setTimeout(function(){$("#user_hashname"+catId).focus();}, 100);
				return false;
			}
			
			var invalidHtCharacters = /[^a-z^A-Z^0-9^#]/;
			if( invalidHtCharacters.test( hashtagHolder ) )
			{
				alert( "Only letters,digits and hash are valid." );
				$("#user_hashname"+catId).focus();
				return false;
			}
			var hashCount = (hashtagHolder.match(/#/g) || []).length;
			if( parseInt(hashCount) > parseInt("1") || ((parseInt(hashCount) == parseInt("1")) && (hashtagHolder.charAt(0) != "#")) )
			{
				alert( "Only leading hash is allowed." );
				$("#user_hashname"+catId).focus();
				return false;
			}
			if( hashtagHolder.charAt(0) != "#" )
			{
				alert( "Please enter leading hash." );
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
		$('#dashBoardForm'+catId).submit();
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
				var secret_code=randomString();
				var Private="Private";
				var privatehtml='<div class="text_row">'+
					'<div class="float_l">'+
						'<div class="privatedatabox">'+
							'<input type="text" class="manage_textbox_pad" id="user_hashname'+value+'" name="user_hashname'+value+'" value="'+user_hashname+'"/>'+
							'<span id="ucodeTitle'+value+'">&nbsp;Unique Code:&nbsp;</span>'+
							'<input type="text" class="manage_textbox_pad" id="secret_code'+value+'"  name="secret_code'+value+'" value="'+secret_code+'">'+
							'<a href="javascript:void(0);" id="ucodeAnchor'+value+'"><img style="margin-top:5px;" src="'+BASE_PATH+'/images/social_media/refresh.png" onclick="refreshUcode('+value+')" /></a>'+
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
	
	function randomString()
	{
		var lettersSet = 'abcdefghijklmnopqrstuvwxyz';
		var numbersSet = '0123456789';
		
		var codeLength = Math.floor(Math.random() * (7 - 5 + 1)) + 5;
		
		var numberA = Math.floor(Math.random() * ((codeLength-1) - 0 + 1)) + 0;
		var numberB = Math.floor(Math.random() * ((codeLength-1) - 0 + 1)) + 0;
		while( (numberA == numberB) || ((numberA+1) == numberB) || ((numberA-1) == numberB) )
		{
			numberB = Math.floor(Math.random() * ((codeLength-1) - 0 + 1)) + 0;
		}
		
		var randomString = '';
		for( var i = 0; i < codeLength; i++ )
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
		
		return randomString ;
	}
	
	function refreshUcode(value){
		var seconds = new Date().getTime() / 1000;
		$('#secret_code'+value).val(randomString());
	}
	function refreshUcodeDatabox(){
		var seconds = new Date().getTime() / 1000;
		$('#ucHolder').val( randomString() );
	}
	function showBrowseClick(imagelinkId){
		//crop_value_countt=0;
		//$('#change_image'+imagelinkId).click();
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
		// alert(BASE_PATH);
		// return false;
		// alert( $( '#relevanceDiv' + categoryId ).html() );

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
			// alert( "voted" );
			// alert( data );
		  }
		});
	}
	
	function voteWorth( newVoteValue,categoryId )
	{
		// alert(BASE_PATH);
		// return false;
		// alert( $( '#relevanceDiv' + categoryId ).html() );

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
			// alert( "voted" );
			// alert( data );
		  }
		});
	}
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