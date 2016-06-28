
	function callMontageSetFunc()
	{
		if( parseInt($("#montageActivated").val()) == parseInt("0") )
		{
			alert( "Please select montage." );
			$("#montagePriorOrder").val( 0 );
			return false;
		}

		var montagePriorOrder = 0;
		montagePriorOrder = $("#montagePriorOrder").val();
		if( parseInt(montagePriorOrder) == parseInt("0") )
		{
			alert( "Please select a prior order." );
			return false;
		}

		// alert(montagePriorOrder);
		setMontagePriorOrder();
	}

	function activateHashName()
	{
		var activeUserId=$('#activeUserId').val();
		$("#montageActivated").val( 1 );
		$("#hideLink").html( "HIDE" );

		if( parseInt($("#activeHighlightId").val()) > parseInt("0") )
		{
			var activeHighlightId = $("#activeHighlightId").val();
			$( "#p_highlight_"+activeHighlightId ).removeClass( "search_user_active" );
			$("#activeHighlightId").val( 0 );
			$("#activeHighEo").val( 0 );
			$("#activeHighSettingId").val( 0 );
			$("#activeHighBoxType").val( 0 );
		}
		if( parseInt($("#activeDataboxId").val()) > parseInt("0") )
		{
			var activeDataboxId = $("#activeDataboxId").val();
			$( "#p_databox_"+activeDataboxId ).removeClass( "search_user_active" );
			$("#activeDataboxId").val( 0 );
			$("#activeDataboxType").val( 0 );
			$("#activeExistingOrder").val( 0 );
			$("#activeSettingId").val( 0 );
			$("#activeBoxType").val( 0 );
		}
		$( "#montageHashName" ).addClass( "search_user_active" );
		// alert( activeUserId );

		return false;
	}

	function visitSelectedBox()
	{
		if( parseInt($("#activeDataboxId").val()) == parseInt("0") && parseInt($("#activeHighlightId").val()) == parseInt("0") )
		{
			alert( "Please select a databox or highlight to visit." );
			return false;
		}

		var viewUrl="";
		
		var displayCustomizationUrl = BASE_URL;

		if( parseInt($("#activeDataboxId").val()) > parseInt("0") )
		{
			// alert($("#activeSettingId").val());
			if(parseInt($("#activeSettingId").val())==3){
				viewUrl='pre-vertical';
			}else{
				viewUrl='pre-horizontal';
			}
			// alert(viewUrl);
			displayCustomizationUrl += "/databox/"+viewUrl+"/"+$("#activeDataboxId").val();
		}
		else if( parseInt($("#activeHighlightId").val()) > parseInt("0") )
		{
			if(parseInt($("#activeHighSettingId").val())==3){
				viewUrl='pre-vertical';
			}else{
				viewUrl='pre-horizontal';
			}
			displayCustomizationUrl += "/databox/"+viewUrl+"/"+$("#activeHighlightId").val();
		}
		window.open ( displayCustomizationUrl,'_blank' );

		return false;
	}

	function callDbSetFunc()
	{
		if( parseInt($("#activeDataboxId").val()) == parseInt("0") )
		{
			alert( "Please select a databox." );
			$("#dataBoxPriorOrder").val( 0 );
			return false;
		}

		var boxPriorOrder = 0;
		boxPriorOrder = $("#dataBoxPriorOrder").val();
		if( parseInt(boxPriorOrder) == parseInt("0") )
		{
			alert( "Please select a prior order." );
			return false;
		}

		setDataboxPriorOrder( $("#activeDataboxId").val(),$("#activeDataboxType").val(),$("#activeExistingOrder").val(),$("#activeBoxType").val() );
	}
	
	function callDbHighSetFunc()
	{
		if( parseInt($("#activeHighlightId").val()) == parseInt("0") )
		{
			alert( "Please select a highlight." );
			$("#highlightPriorOrder").val( 0 );
			return false;
		}

		var boxPriorOrder = 0;
		boxPriorOrder = $("#highlightPriorOrder").val();
		if( parseInt(boxPriorOrder) == parseInt("0") )
		{
			alert( "Please select a prior order." );
			return false;
		}

		setHighlightPriorOrder( $("#activeHighlightId").val(),$("#activeHighEo").val(),$("#activeHighBoxType").val() );
	}

	function setActiveDbValues( categoryId,publicPrivate,existingOrder,settingId,dbStatus,boxType )
	{
		// alert( settingId );
		//alert( $( "#p_databox_"+categoryId ).length );
		if( $( "#montageHashName" ).length )
		{
			$( "#montageHashName" ).removeClass( "search_user_active" );
		}

		if( parseInt($("#activeDataboxId").val()) > parseInt("0") )
		{
			var currentActiveId = $("#activeDataboxId").val();
			$( "#p_databox_"+currentActiveId ).removeClass( "search_user_active" );
		}
		$( "#p_databox_"+categoryId ).addClass( "search_user_active" );

		$("#activeDataboxId").val( categoryId );
		$("#activeDataboxType").val( publicPrivate );
		$("#activeExistingOrder").val( existingOrder );
		$("#activeSettingId").val( settingId );
		$("#activeBoxType").val( boxType );
		
		if( parseInt(dbStatus) == parseInt("3") )
		{
			$("#hideLink").html( "UNHIDE" );
		}
		else if( parseInt(dbStatus) == parseInt("1") )
		{
			$("#hideLink").html( "HIDE" );
		}

		if( parseInt($("#activeHighlightId").val()) > parseInt("0") )
		{
			var activeHighlightId = $("#activeHighlightId").val();
			$( "#p_highlight_"+activeHighlightId ).removeClass( "search_user_active" );
			$("#activeHighlightId").val( 0 );
			$("#activeHighEo").val( 0 );
			$("#activeHighSettingId").val( 0 );
			$("#activeHighBoxType").val( 0 );
		}

		return false;
	}

	function setActiveHighValues( categoryId,existingOrder,settingId,highStatus,boxType )
	{
		// alert( categoryId );
		//alert( $( "#p_highlight_"+categoryId ).length );
		if( $( "#montageHashName" ).length )
		{
			$( "#montageHashName" ).removeClass( "search_user_active" );
		}

		if( parseInt($("#activeHighlightId").val()) > parseInt("0") )
		{
			var currentActiveId = $("#activeHighlightId").val();
			$( "#p_highlight_"+currentActiveId ).removeClass( "search_user_active" );
		}
		$( "#p_highlight_"+categoryId ).addClass( "search_user_active" );

		$("#activeHighlightId").val( categoryId );
		$("#activeHighEo").val( existingOrder );
		$("#activeHighSettingId").val( settingId );
		$("#activeHighBoxType").val( boxType );

		if( parseInt(highStatus) == parseInt("3") )
		{
			$("#hideLink").html( "UNHIDE" );
		}
		else if( parseInt(highStatus) == parseInt("1") )
		{
			$("#hideLink").html( "HIDE" );
		}

		if( parseInt($("#activeDataboxId").val()) > parseInt("0") )
		{
			var activeDataboxId = $("#activeDataboxId").val();
			$( "#p_databox_"+activeDataboxId ).removeClass( "search_user_active" );
			$("#activeDataboxId").val( 0 );
			$("#activeDataboxType").val( 0 );
			$("#activeExistingOrder").val( 0 );
			$("#activeSettingId").val( 0 );
			$("#activeBoxType").val( 0 );
		}

		return false;
	}

	function setDataboxPriorOrder( categoryId,publicPrivate,existingOrder,boxType )
	{
		// alert(publicPrivate);
		if( parseInt(publicPrivate) == parseInt("0") )
		{
			$('#pvtDataboxPopup').popUpWindow({action: "open"});
			$("#dataBoxPriorOrder").val( 0 );
			return false;
		}

		var boxPriorOrder = 0;
		boxPriorOrder = $("#dataBoxPriorOrder").val();
		
		if( parseInt(existingOrder) == parseInt(boxPriorOrder) )
		{
			alert( "This databox already has prior order : " + existingOrder );
			return false;
		}
		/*
		if( parseInt(existingOrder) != parseInt("1500") )
		{
		}
		*/
		var setCheckPriorOrderUrl = BASE_URL + "/admin/check-prior-order-exists";
		$.ajax({
		  url: setCheckPriorOrderUrl,
		  type: "POST",
		  data:{categoryId:categoryId,boxPriorOrder:boxPriorOrder,boxType:boxType},
		  async: false,
		  dataType: "json",
		  success: function(data) {
			// alert( data );
			var setOrder = true;
			
			if( parseInt(data.orderExistsStatus) == parseInt("1") )
			{
				var replyStatus = confirm( "Prior Order " + boxPriorOrder + " already set for another databox. Replace Prior Order ?" );
				if( ! replyStatus )
				{
					setOrder = false;
				}
			}
			
			if( setOrder )
			{
				var carryingCatId = data.carryingCatId;
				// alert(carryingCatId);
				var setDataboxPriorOrderUrl = BASE_URL + "/admin/set-databox-prior-order";
				$.ajax({
				  url: setDataboxPriorOrderUrl,
				  type: "POST",
				  data:{categoryId:categoryId,boxPriorOrder:boxPriorOrder,carryingCatId:carryingCatId},
				  async: false,
				  success: function(data) {
					// alert( data );
					if( parseInt(data) )
					{
						alert( "Prior order set." );
						$("#dataBoxPriorOrder").val( 0 );

						var currentActiveId = $("#activeDataboxId").val();
						$( "#p_databox_"+currentActiveId ).removeClass( "search_user_active" );
						$("#activeDataboxId").val( 0 );
						$("#activeDataboxType").val( 0 );
						$("#activeExistingOrder").val( 0 );
						$("#activeBoxType").val( 0 );
						// location.reload();
					}
				  }
				});
			}
			else
			{
				$("#dataBoxPriorOrder").val( 0 );
			}
		  }
		});
		
	}
	
	function setHighlightPriorOrder( categoryId,existingOrder,boxType )
	{
		var boxPriorOrder = 0;
		boxPriorOrder = $("#highlightPriorOrder").val();
		
		if( parseInt(existingOrder) == parseInt(boxPriorOrder) )
		{
			alert( "This highlight already has prior order : " + existingOrder );
			return false;
		}
		/*
		if( parseInt(existingOrder) != parseInt("1500") )
		{
		}
		*/
		var setCheckPriorOrderUrl = BASE_URL + "/admin/check-prior-order-exists";
		$.ajax({
		  url: setCheckPriorOrderUrl,
		  type: "POST",
		  data:{categoryId:categoryId,boxPriorOrder:boxPriorOrder,boxType:boxType},
		  async: false,
		  dataType: "json",
		  success: function(data) {
			// alert( data );
			var setOrder = true;
			
			if( parseInt(data.orderExistsStatus) == parseInt("1") )
			{
				var replyStatus = confirm( "Prior Order " + boxPriorOrder + " already set for another highlight. Replace Prior Order ?" );
				if( ! replyStatus )
				{
					setOrder = false;
				}
			}
			
			if( setOrder )
			{
				var carryingCatId = data.carryingCatId;
				// alert(carryingCatId);
				var setDataboxPriorOrderUrl = BASE_URL + "/admin/set-highlight-prior-order";
				$.ajax({
				  url: setDataboxPriorOrderUrl,
				  type: "POST",
				  data:{categoryId:categoryId,boxPriorOrder:boxPriorOrder,carryingCatId:carryingCatId},
				  async: false,
				  success: function(data) {
					// alert( data );
					if( parseInt(data) )
					{
						alert( "Prior order set." );
						$("#highlightPriorOrder").val( 0 );

						var currentActiveId = $("#activeHighlightId").val();
						$( "#p_highlight_"+currentActiveId ).removeClass( "search_user_active" );
						$("#activeHighlightId").val( 0 );
						$("#activeHighEo").val( 0 );
						$("#activeHighBoxType").val( 0 );
						// location.reload();
					}
				  }
				});
			}
			else
			{
				$("#highlightPriorOrder").val( 0 );
			}
		  }
		});
		
	}

	function setMontagePriorOrder()
	{
		var montagePriorOrder = 0;
		montagePriorOrder = $("#montagePriorOrder").val();
		if( parseInt(montagePriorOrder) == parseInt("0") )
		{
			alert( "Please select a prior order." );
			return false;
		}
		var activeUserId=$('#activeUserId').val();
		// alert(activeUserId);
		
		/*
		if( parseInt(existingOrder) == parseInt(boxPriorOrder) )
		{
			alert( "This highlight already has prior order : " + existingOrder );
			return false;
		}
		if( parseInt(existingOrder) != parseInt("1500") )
		{
		}
		*/
		var setMontageOrderUrl = BASE_URL + "/admin/check-montage-order-exists";
		$.ajax({
		  url: setMontageOrderUrl,
		  type: "POST",
		  data:{userId:activeUserId,montagePriorOrder:montagePriorOrder},
		  async: false,
		  dataType: "json",
		  success: function(data) {
			// alert( data );
			var setOrder = true;
			
			if( parseInt(data.orderExistsStatus) == parseInt("1") )
			{
				var replyStatus = confirm( "Prior Order " + montagePriorOrder + " already set for another montage. Replace Prior Order ?" );
				if( ! replyStatus )
				{
					setOrder = false;
				}
			}
			
			if( setOrder )
			{
				var carryingUserId = data.carryingUserId;
				// alert(carryingUserId);
				var setMontagePriorOrderUrl = BASE_URL + "/admin/set-montage-prior-order";
				$.ajax({
				  url: setMontagePriorOrderUrl,
				  type: "POST",
				  data:{userId:activeUserId,montagePriorOrder:montagePriorOrder,carryingUserId:carryingUserId},
				  async: false,
				  success: function(data) {
					// alert( data );
					if( parseInt(data) )
					{
						alert( "Prior order set." );
						$("#montageActivated").val( 0 );
						$("#montagePriorOrder").val( 0 );
						if( $( "#montageHashName" ).length )
						{
							$( "#montageHashName" ).removeClass( "search_user_active" );
						}
					}
				  }
				});
			}
			else
			{
				$("#montagePriorOrder").val( 0 );
			}
		  }
		});
		
	}

	function removeDatabox(userCategoryId)
	{
		var result = confirm("Are you sure want to remove the databox ?");
		if( result==true )
		{
			$.ajax({
				type		:	'POST',
				url			:  	BASE_URL+'/admin/remove-databox',
				data		:	{userCategoryId:userCategoryId},
				success: function(data){
					if( parseInt(data.removeDataboxStatus) == parseInt("1") )
					{
						alert( "Databox removed." );
						$( "#p_databox_"+userCategoryId ).remove();
						$("#activeDataboxId").val( 0 );
						$("#activeDataboxType").val( 0 );
						$("#activeExistingOrder").val( 0 );
						$("#activeBoxType").val( 0 );
					}
				}
			});
		}
	}
	
	function removeHighlight(userCategoryId)
	{
		var result = confirm("Are you sure want to remove the highlight ?");
		if( result==true )
		{
			$.ajax({
				type		:	'POST',
				url			:  	BASE_URL+'/admin/remove-databox',
				data		:	{userCategoryId:userCategoryId},
				success: function(data){
					if( parseInt(data.removeDataboxStatus) == parseInt("1") )
					{
						alert( "Highlight removed." );
						$( "#p_highlight_"+userCategoryId ).remove();
						$("#activeHighlightId").val( 0 );
						$("#activeHighEo").val( 0 );
						$("#activeHighBoxType").val( 0 );
					}
				}
			});
		}
	}

	function hideDatabox(userCategoryId)
	{
		var dbStatus = 3;
		if( $("#hideLink").html() == "UNHIDE" )
		{
			dbStatus = 1;
		}
		var result = null;
		if( parseInt(dbStatus) == parseInt("1") )
		{
			result = confirm("Are you sure want to unhide the databox ?");
		}
		else if( parseInt(dbStatus) == parseInt("3") )
		{
			result = confirm("Are you sure want to hide the databox ?");
		}
		// alert(result);
		if( result==true )
		{
			// alert( dbStatus );
			
			$.ajax({
				type		:	'POST',
				url			:  	BASE_URL+'/admin/hide-databox',
				data		:	{userCategoryId:userCategoryId,dbStatus},
				success: function(data){
					if( parseInt(data.hideDataboxStatus) == parseInt("1") )
					{
						if( parseInt(dbStatus) == parseInt("1") )
						{
							alert( "Databox Unhidden." );
						}
						else if( parseInt(dbStatus) == parseInt("3") )
						{
							alert( "Databox hidden." );
						}
						// $( "#p_databox_"+userCategoryId ).remove();
						var currentActiveId = $("#activeDataboxId").val();
						$( "#p_databox_"+currentActiveId ).removeClass( "search_user_active" );
						$("#activeDataboxId").val( 0 );
						$("#activeDataboxType").val( 0 );
						$("#activeExistingOrder").val( 0 );
						$("#activeBoxType").val( 0 );
						$("#hideLink").html( "HIDE" );
						
						// alert($("#activeUserId").val());
						viewUserBoxes( $("#activeUserId").val() );
					}
				}
			});
		}
	}
	
	function hideHighlight(userCategoryId)
	{
		var dbStatus = 3;
		if( $("#hideLink").html() == "UNHIDE" )
		{
			dbStatus = 1;
		}
		var result = null;
		if( parseInt(dbStatus) == parseInt("1") )
		{
			result = confirm("Are you sure want to unhide the highlight ?");
		}
		else if( parseInt(dbStatus) == parseInt("3") )
		{
			result = confirm("Are you sure want to hide the highlight ?");
		}
		// alert(result);
		if( result==true )
		{
			$.ajax({
				type		:	'POST',
				url			:  	BASE_URL+'/admin/hide-databox',
				data		:	{userCategoryId:userCategoryId,dbStatus},
				success: function(data){
					if( parseInt(data.hideDataboxStatus) == parseInt("1") )
					{
						if( parseInt(dbStatus) == parseInt("1") )
						{
							alert( "Highlight Unhidden." );
						}
						else if( parseInt(dbStatus) == parseInt("3") )
						{
							alert( "Highlight hidden." );
						}
						// $( "#p_highlight_"+userCategoryId ).remove();
						var currentActiveId = $("#activeHighlightId").val();
						$( "#p_highlight_"+currentActiveId ).removeClass( "search_user_active" );
						$("#activeHighlightId").val( 0 );
						$("#activeHighEo").val( 0 );
						$("#activeHighBoxType").val( 0 );
						$("#hideLink").html( "HIDE" );
						
						// alert($("#activeUserId").val());
						viewUserBoxes( $("#activeUserId").val() );
					}
				}
			});
		}
	}

	function viewUserBoxes(id){
		$("#hideLink").html( "HIDE" );

		var hid=id;
		var hid=$('#hid_user_id').val(hid);
		var activeUserId=$('#activeUserId').val();
		$( "#list_user_"+activeUserId ).removeClass( "search_user_active" );
		$( "#list_user_"+id ).addClass( "search_user_active" );
		if( $( "#montageHashName" ).length )
		{
			$( "#montageHashName" ).removeClass( "search_user_active" );
		}
		$.ajax({
			  url: BASE_URL+"/admin/ajax-data",
			  type: "POST",
			  data:{user_id:id},
			  success: function(data) {
				$('.ajax_points').html(data.userPoints);
				$('.ajax_databox').html(data.dataBoxes);
				$('.ajax_high').html(data.highlightsBoxes);
				$('#ajax_hash_name').html(data.montage_hash_name);
				$('#ajax_reports').html(data.reports);
				$('#activeUserId').val(id);
			  }
		});
	}
	function getAjaxAppendUSers(){
		var val=parseInt($('#users_select_box').val());
		var totalDivs=parseInt($('#totalDivs').val());
		var lastDiv=parseInt($('#lastDiv').val());
		if(val<lastDiv){
			var startDiv=val+5;
			for(var i=startDiv;i<=lastDiv;i+=5){
				$('#users_'+i).remove();
			}
			$('#lastDiv').val(val);
		}else{
			var searchValue=$('#searchValue').val();
			if(searchValue==1){
				var offset=0;
				var limit=val+5;
			}else{
				if(lastDiv==(val-5)){
					var offset=val;
					var limit=5;
				}else{
					var offset=lastDiv+5;
					var limit=val;
				}
			}
			$.ajax({
				  url: BASE_URL+"/admin/append-ajax-users",
				  type: "POST",
				  data:{offset:offset,limit:limit},
				  success: function(data) {
					var datah="";
					if(searchValue==1){
						datah+='<p style="padding:0;line-height:0"><input type="text" id="search" name="search" placeholder="SEARCH USER"><img src="'+BASE_PATH+'/images/go_search_btn.png" onClick="Javascript:searchUsers();"/></p>';
						$('.users_ajax').html(datah);
						$('.users_ajax').append(data);
					}else{
						$('.users_ajax').append(data);
						$('#searchValue').val(0);
					}
					$('#lastDiv').val(val);
				  }
			});
		}
	}
	function searchUsers(){
		var search=$('#search').val();
		if(search==""){
			alert('Search user name required');
			return false;
		}else{
			$.ajax({
				  url: BASE_URL+"/admin/search-ajax-users",
				  type: "POST",
				  data:{search:search},
				  success: function(data) {
					$('.users_ajax').html(data);
					$('#searchValue').val(1);
					$('#lastDiv').val(0);
				  }
			});
		}
	}
	function removeUsers(type){

		var currentActiveDbId = $("#activeDataboxId").val();
		var activeHighlightId = $("#activeHighlightId").val();
	
		if(type=='remove'){
			if( parseInt(currentActiveDbId) > parseInt("0") )
			{
				removeDatabox( currentActiveDbId );
				return false;
			}
			if( parseInt(activeHighlightId) > parseInt("0") )
			{
				removeHighlight( activeHighlightId );
				return false;
			}
			var status=3;
			var result = confirm("Are you sure want to remove user?");
		}else{
			if( parseInt(currentActiveDbId) > parseInt("0") )
			{
				hideDatabox( currentActiveDbId );
				return false;
			}
			if( parseInt(activeHighlightId) > parseInt("0") )
			{
				hideHighlight( activeHighlightId );
				return false;
			}
			var status=2;
			var result = confirm("Are you sure want to hide user?");
		}
		var id=$('#hid_user_id').val();
		var imgSrc= BASE_PATH+"/images/social_media/wrong_link_icon.png";
		if (result==true) {
			$.ajax({
				  url: BASE_URL+"/admin/update-user-status",
				  type: "POST",
				  data:{id:id,status:status},
				  success: function(data) {
					$('#list_user_'+id).remove();
					if(type=='remove'){
						$('.alert_message').slideToggle('slow');
						$('.alert_message').html('Remove the user sucessfully<div class="alert_cancel"><a href="#"><img src="'+imgSrc+'" /></a></div>');
						$('.alert_message').delay(6000).fadeOut('slow');
					}else{
						$('.alert_message').slideToggle('slow');
						$('.alert_message').html('Hide the user sucessfully<div class="alert_cancel"><a href="#"><img src="'+imgSrc+'" /></a></div>');
						$('.alert_message').delay(6000).fadeOut('slow');
					}
				  }
			});
		}
	}
	function searchDatabox(){
		$('.ajax_comments').html('');
		var search=$('#searchComment').val();
		if(search==""){
			alert('Search databox required');
			return false;
		}else{
			$.ajax({
				  url: BASE_URL+"/admin/search-ajax-databoxs",
				  type: "POST",
				  data:{search:search},
				  success: function(data) {
					$('.users_ajax1').html(data);
					$('#searchValue2').val(1);
					$('#lastDiv2').val(0);
				  }
			});
		}
	}
	function viewDataBoxComments(id){
		var activeUserId=$('#activeCommentDataboxId').val();
		$( "#list_Comments_"+activeUserId ).removeClass( "search_user_active" );
		$( "#list_Comments_"+id ).addClass( "search_user_active" );
		$.ajax({
			  url: BASE_URL+"/admin/ajax-comments",
			  type: "POST",
			  data:{comment_databox_id:id},
			  success: function(data) {
				$('.ajax_comments').html(data.comments);
				$('#activeCommentDataboxId').val(id);
			  }
		});
	}
	function deleteComment(id){
		$.ajax({
			  url: BASE_URL+"/admin/delete-comment",
			  type: "POST",
			  data:{databox_comment_id:id},
			  success: function(data) {
				  if(data.output!=0) {
						$('#p_databox_comments_'+id).remove();
						alert('comment deleted');
					}else{
						alert('No data is found');
					}
			  }
		});
    }
