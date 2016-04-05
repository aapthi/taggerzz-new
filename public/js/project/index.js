$(document.body).click(function(e){
	$('#country_list_id').hide();
});
$(document).keypress(function(e) {
	var totalCount = $('#totalValue').val();
	switch(e.keyCode){	
	case 13://enter
		var hashName = $('#h'+searchVariable).html();
		if(hashName!=null){
			var hashName1= hashName.replace('<b>','');
			var hashName2 = hashName1.replace('</b>','');
			set_item(hashName2);
		}
			
	break;
	case 27://escape
	  $('#country_list_id').hide();
	  $('#searchTermHolder').blur();
	  break;
	case 38://up
	$('#searchTermHolder').blur();
		if(searchVariable >= 0)
		{
			removeCss();
			searchVariable--;
			applyCss();
		}
		else
		{
			searchVariable = totalCount;
		}
	break;	
	case 40://down
		$('#searchTermHolder').blur();
		if(searchVariable <= totalCount)
		{
			removeCss();
			searchVariable++;
			applyCss();
		}
		else
		{
			searchVariable = 0;
		}
	break;	
	}
});
$(document).on("mousedown", "#publicBoxesLink", function(e) {				
	if( e.which == 2 ) {
		$('#publicBoxesLink').attr('href',BASE_URL);
	}
});
$(document).on("mousedown", "#memCollectionsLink", function(e) {				
	if( e.which == 2 ) {
		$('#memCollectionsLink').attr('href',BASE_URL);
	}
});
$(document).on("mousedown", "#postHighliths", function(e) {	
			
	if( e.which == 2 ) {
		if( $( "#loggedInStatus" ).val() != "" )
		{
			var enlistHighlightsUrl = BASE_URL;
			enlistHighlightsUrl += "/databox/highlights-both";
			// window.location=enlistHighlightsUrl;
			$('#postHighliths').attr('href',enlistHighlightsUrl);
			// window.open(enlistHighlightsUrl);
		}else{
			$( "#nsfwMcClicked" ).val( 0 );
			$( "#nsfwMcSet" ).val( 0 );
			$( "#categoryId" ).val( 0 );
			$( "#settingId" ).val( 0 );
			$( "#hashName" ).val( "" );
			$( "#categoryTitle" ).val( "" );

			$( "#postClicked" ).val( 1 );
			$( "#postHighlights" ).val( 1 );

			$('#userMessage').show();
			$('#userMessage').html( "Please log in to post highlights." );
			$('#open-pop-up-1').click();
		}		
	}
});
$(document).ready(function(){
	//NEW CODE PUBLIC DATABOX
	$.fn.hasAncestor = function(a)
	{
		return this.filter(function(){
			return !!$(this).closest(a).length;
		});
	};
	//END 
	$( "#postHighliths" ).click(function(event)
	{
		if( $( "#loggedInStatus" ).val() == "" )
		{
			$( "#nsfwMcClicked" ).val( 0 );
			$( "#nsfwMcSet" ).val( 0 );
			$( "#categoryId" ).val( 0 );
			$( "#settingId" ).val( 0 );
			$( "#hashName" ).val( "" );
			$( "#categoryTitle" ).val( "" );

			$( "#postClicked" ).val( 1 );
			$( "#postHighlights" ).val( 1 );

			$('#userMessage').show();
			$('#userMessage').html( "Please log in to post highlights." );
			$('#open-pop-up-1').click();
		}
		else
		{
			var enlistHighlightsUrl = BASE_URL;
			enlistHighlightsUrl += "/databox/highlights-both";
			window.location=enlistHighlightsUrl;
		}
	});
	var nicesxPublic = null;
	var scrollPublicBoxesHandler = null;
	var windowtz = $( window );
	//alert(windowtz.getNiceScroll().length);
	if( $( "#homePageSection" ).length && windowtz )
	{
	//nicesxPublic = windowtz.niceScroll({cursorborder:"",touchbehavior:false,cursorcolor:"#acacac",cursoropacitymax:0.6,cursorwidth:8});	
	//windowtz .niceScroll({cursorborder:"",touchbehavior:false,cursorcolor:"#acacac",cursoropacitymax:0.6,cursorwidth:8});	
		 nicesxPublic = windowtz.getNiceScroll();
		var scrollPublicBoxesHandler = function()
		{
				var nearToBottom = 200;
				if (windowtz.scrollTop() + windowtz.height() > $(document).height() - nearToBottom) {
			//if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight)
			//{
				$('#publicBoxesLoadingDiv').show();
				windowtz.off("scroll",scrollPublicBoxesHandler);

				var publicBoxesPerPage = $( "#publicBoxesPerPage" ).val();
				
				if( $('#publicBoxesOffset').val() != "" )
				{
					$('#publicBoxesOffset').val( parseInt($('#publicBoxesPerPage').val()) + parseInt($('#publicBoxesOffset').val()) );
				}

				var publicBoxesOffset = $( "#publicBoxesOffset" ).val();
				
				var publicBoxesAjaxUrl = BASE_URL + "/databox/public-boxes-ajax";
				$.ajax({
				  url: publicBoxesAjaxUrl,
				  type: "POST",
				  data:{publicBoxesPerPage:publicBoxesPerPage,publicBoxesOffset:publicBoxesOffset},
				  async: false,
				  dataType: "json",
				  success: function(data) {
					//  var preScrollTop = nicesxPublic.getScrollTop();					
					if( parseInt(data.publicBoxesAllLoaded) == parseInt("0") )
					{
						var cards = jQuery.parseJSON(data.cards);
						jQuery.each(cards, function(index, value){
							var item = document.createElement('div');
	 						var grid = document.querySelector('#divDataboxWrapper');
	 						
						    salvattore['append_elements'](grid, [item]);
						 
						    item.outerHTML = value;
							//$("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html( $("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html() + data.publicBoxesHtml );
							$('#publicBoxesLoadingDiv').hide();
							windowtz.on('scroll',scrollPublicBoxesHandler);
							//$('#divDataboxWrapper').addClass('divCard');
							//nicesxPublic.resize();
							//nicesxPublic.setScrollTop(preScrollTop);
						});
					}
					else if( parseInt(data.publicBoxesAllLoaded) == parseInt("1") )
					{
					$( "#publicBoxesOn" ).val( 0 );
						var cards = jQuery.parseJSON(data.cards);
						jQuery.each(cards, function(index, value){
							var item = document.createElement('div');
	 						var grid = document.querySelector('#divDataboxWrapper');
						    salvattore['append_elements'](grid, [item]);
						    item.outerHTML = value;
							$('#publicBoxesLoadingDiv').hide();
						});
					}
				  },
				});
			}
		}
		
		windowtz.on('scroll',scrollPublicBoxesHandler);
	}	
	var nicesxHighlights = null;
	var scrollHighlightBoxesHandler = null;
	if( $( ".content_home_highlights" ).length )
	{
		nicesxHighlights = $(".content_home_highlights").niceScroll({cursorborder:"",touchbehavior:false,cursorcolor:"#acacac",cursoropacitymax:0.6,cursorwidth:8});
		
		var scrollHighlightBoxesHandler = function()
		{
			if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight)
			{
				$( ".content_home_highlights" ).off("scroll",scrollHighlightBoxesHandler);
				
				var highlightBoxesPerPage = $( "#highlightBoxesPerPage" ).val();
				
				if( $('#highlightBoxesOffset').val() != "" )
				{
					$('#highlightBoxesOffset').val( parseInt($('#highlightBoxesPerPage').val()) + parseInt($('#highlightBoxesOffset').val()) );
				}

				var highlightBoxesOffset = $( "#highlightBoxesOffset" ).val();
				
				var highlightBoxesAjaxUrl = BASE_URL + "/databox/highlight-boxes-ajax";
				$.ajax({
				  url: highlightBoxesAjaxUrl,
				  type: "POST",
				  data:{highlightBoxesPerPage:highlightBoxesPerPage,highlightBoxesOffset:highlightBoxesOffset},
				  async: false,
				  dataType: "json",
				  success: function(data) {
					var preScrollTop = nicesxHighlights.getScrollTop();
					if( parseInt(data.highlightBoxesAllLoaded) == parseInt("0") )
					{
						$('#list_highlights').html( $('#list_highlights').html() + data.highlightBoxesHtml );
						$( ".content_home_highlights" ).on('scroll',scrollHighlightBoxesHandler);
						nicesxHighlights.resize();
						nicesxHighlights.setScrollTop(preScrollTop);
					}
					else if( parseInt(data.highlightBoxesAllLoaded) == parseInt("1") )
					{
						$('#list_highlights').html( $('#list_highlights').html() + data.highlightBoxesHtml );
						nicesxHighlights.resize();
						nicesxHighlights.setScrollTop(preScrollTop);
					}
				  },
				});
			}
		}

		$( ".content_home_highlights" ).on('scroll',scrollHighlightBoxesHandler);
	}

	var scrollMontageBoxesHandler = function()
	{
		var nearToBottom = 200;
	    if (windowtz.scrollTop() + windowtz.height() > $(document).height() - nearToBottom) {
			windowtz.off("scroll",scrollMontageBoxesHandler);

			var montageBoxesPerPage = $( "#montageBoxesPerPage" ).val();
			
			if( $('#montageBoxesOffset').val() != "" )
			{
				$('#montageBoxesOffset').val( parseInt($('#montageBoxesPerPage').val()) + parseInt($('#montageBoxesOffset').val()) );
			}

			var montageBoxesOffset = $( "#montageBoxesOffset" ).val();
			var montageBoxesAjaxUrl = BASE_URL + "/user-collection-boxes-ajax";

			$.ajax({
			  url: montageBoxesAjaxUrl,
			  type: "POST",
			  data:{montageBoxesPerPage:montageBoxesPerPage,montageBoxesOffset:montageBoxesOffset},
			  async: false,
			  dataType: "json",
			  success: function(data) {
				//var preScrollTop = nicesxPublic.getScrollTop();
				if( parseInt(data.montageBoxesAllLoaded) == parseInt("0") )
				{
					var cards = jQuery.parseJSON(data.cards);
					jQuery.each(cards, function(index, value)
					{
						var item = document.createElement('div');
						var grid = document.querySelector('#divDataboxWrapper');
						salvattore['append_elements'](grid, [item]);
						item.outerHTML = value;
						// $("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html( $("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html() + data.publicBoxesHtml );
						// $('#divDataboxWrapper').addClass('divCard');
						// nicesxPublic.resize();
						// nicesxPublic.setScrollTop(preScrollTop);
					});
					windowtz.on('scroll',scrollMontageBoxesHandler);
					
					// $("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html( $("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html() + data.montageBoxesHtml );
					// $( ".container_main_cats_" ).on('scroll',scrollMontageBoxesHandler);
					// nicesxPublic.resize();
					// nicesxPublic.setScrollTop(preScrollTop);
				}
				else if( parseInt(data.montageBoxesAllLoaded) == parseInt("1") )
				{
					$( "#montageBoxesOn" ).val( 0 );
					var cards = jQuery.parseJSON(data.cards);
					jQuery.each(cards, function(index, value)
					{
						var item = document.createElement('div');
						var grid = document.querySelector('#divDataboxWrapper');
						salvattore['append_elements'](grid, [item]);
						item.outerHTML = value;
						// $("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html( $("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html() + data.publicBoxesHtml );
						// $('#divDataboxWrapper').addClass('divCard');
						// nicesxPublic.resize();
						// nicesxPublic.setScrollTop(preScrollTop);
					});
					
					// $("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html( $("div#divDataboxWrapper").hasAncestor("div#list_public_boxes").html() + data.montageBoxesHtml );
					// nicesxPublic.resize();
					// nicesxPublic.setScrollTop(preScrollTop);
				}
			  },
			});
		}
	}

	var publicBoxesScrollTop = null;
	if( $( ".container_main_cats_" ).length )
	{
		publicBoxesScrollTop = nicesxPublic.getScrollTop();
	}
	var montageBoxesScrollTop = null;
	if( $( "#memCollectionsLink" ).length )
	{
		$( "#memCollectionsLink" ).click(function(event)
		{
			if( parseInt($( "#homePageSection" ).val()) == parseInt("2") )
			{
				return false;
			}
			$( "#homePageSection" ).val( 2 );
			
			if( parseInt($( "#publicBoxesOn" ).val()) == parseInt("1") )
			{
				windowtz.off("scroll",scrollPublicBoxesHandler);
			}
			//publicBoxesScrollTop = nicesxPublic.getScrollTop();
			
			$('#publicBoxesDiv').html( $('#list_public_boxes').html() );
			$('#list_public_boxes').html( $('#memCollectionBoxesDiv').html() );
		

			/*nicesxPublic.resize();
			if( montageBoxesScrollTop !== null )
			{
				nicesxPublic.setScrollTop(montageBoxesScrollTop);
			}
			else
			{
				nicesxPublic.setScrollTop(0);
			}*/
			if( parseInt($( "#montageBoxesOn" ).val()) == parseInt("1") )
			{
				windowtz.on('scroll',scrollMontageBoxesHandler);
			}
		});
	}
	if( $( "#publicBoxesLink" ).length )
	{
		$( "#publicBoxesLink" ).click(function(event)
		{
			if( parseInt($( "#homePageSection" ).val()) == parseInt("1") )
			{
				return false;
			}
			$( "#homePageSection" ).val( 1 );
			
			if( parseInt($( "#montageBoxesOn" ).val()) == parseInt("1") )
			{
				windowtz.off("scroll",scrollMontageBoxesHandler);
			}
			//montageBoxesScrollTop = nicesxPublic.getScrollTop();

			$('#memCollectionBoxesDiv').html( $('#list_public_boxes').html() );
			$('#list_public_boxes').html( $('#publicBoxesDiv').html() );

			//nicesxPublic.resize();
			//nicesxPublic.setScrollTop(publicBoxesScrollTop);
			
			if( parseInt($( "#publicBoxesOn" ).val()) == parseInt("1") )
			{
				windowtz.on("scroll",scrollPublicBoxesHandler);
			}
		});
	}

	$( "#searchTermHolder" ).keyup(function(event)
	{
		var keyCode = event.which;
		if( parseInt(keyCode) == parseInt("13") )
		{
			$( "#homeSearchLink" ).click();
		}
		if( $( "#searchTermHolder" ).val().trim() == "" )
		{
			if( parseInt($( "#homePageSection" ).val()) == parseInt("1") && parseInt($( "#homepageSearched" ).val()) == parseInt("1") )
			{
				$('#list_public_boxes').html( $('#publicBoxesDiv').html() );

				//nicesxPublic.resize();
				//nicesxPublic.setScrollTop(publicBoxesScrollTop);
				
				if( parseInt($( "#pboxesOnBeforeSearch" ).val()) == parseInt("1") )
				{
					$( "#publicBoxesOn" ).val( $( "#pboxesOnBeforeSearch" ).val() );
					windowtz.on("scroll",scrollPublicBoxesHandler);
				}
				$( "#homepageSearched" ).val( 0 );
			}
		}
	});
	
	if( $("#pvtUniqueCodeHolder").length )
	{
		$( "#pvtUniqueCodeHolder" ).keypress(function(event)
		{
			var keyCode = event.which;
			if( parseInt(keyCode) == parseInt("13") )
			{
				$( "#homeSearchLink" ).click();
			}
		});
	}

	$( "#homeSearchLink" ).click(function(event)
	{
		var searchTermHolder = $("#searchTermHolder").val().trim();
		var search = $("#searchTermHolder").val();
		if( searchTermHolder == "" )
		{
			alert('Enter Hash tag');
			//$('#pop-up-alerts').popUpWindow({action: "open"});	
			//$("#alert_header_meassge").html('');
			//$("#alert_meassage").html(search_alerts);
			//$("#searchTermHolder").focus();
			return false;
		}
		searchTermHolder = $("#searchTermHolder").val().replace(/^\s+/, "");
		searchTermHolder = searchTermHolder.replace(/\s+$/, "");
		
		$(location).attr('href');
		var pathname = window.location.pathname;
		if(pathname!='/contentpage'){
			var serTerm = search.substring(1, (search.length)); 
			var serCode = $( "#pvtUniqueCodeHolder" ).val();
			var tzSearchUrl = "";
			if(serCode==''){
				tzSearchUrl = BASE_URL +'/contentpage?serTag='+serTerm;
			}else{
				tzSearchUrl = BASE_URL +'/contentpage?serTag='+serTerm+'&serCode='+serCode;
			}
			window.location.href = tzSearchUrl;
		}
		
		var publicPrivate = 1;
		var pvtUniqueCodeHolder = "";
		if( $("#pvtUniqueCodeHolder").length )
		{
			pvtUniqueCodeHolder = $("#pvtUniqueCodeHolder").val().trim();
			if( pvtUniqueCodeHolder != "" )
			{
				pvtUniqueCodeHolder = $("#pvtUniqueCodeHolder").val().replace(/^\s+/, "");
				pvtUniqueCodeHolder = pvtUniqueCodeHolder.replace(/\s+$/, "");
				publicPrivate = 0;
			}
		}
		
		if( parseInt(publicPrivate) == parseInt("1") )
		{
			var publicSearchAjaxUrl = BASE_URL + "/databox/public-search-ajax";

			$.ajax
			({
			  url: publicSearchAjaxUrl,
			  type: "POST",
			  data:{searchTermHolder:searchTermHolder},
			  async: false,
			  dataType: "json",
			  success: function(data)
			  {
				if( parseInt(data.zeroPublicBoxesFound) == parseInt("0") )
				{
					if( parseInt($( "#homepageSearched" ).val()) == parseInt("0") )
					{
						if( parseInt($( "#homePageSection" ).val()) == parseInt("1") )
						{
							//publicBoxesScrollTop = nicesscrollPublic.getScrollTop();
							$('#publicBoxesDiv').html( $('#list_public_boxes').html() );
						}
						$( "#pboxesOnBeforeSearch" ).val( $( "#publicBoxesOn" ).val() );
						$( "#homepageSearched" ).val( 1 );
						$( "#homePageSection" ).val( 1 );
					}
					$('#list_public_boxes').html( data.publicBoxesHtml );
					//nicesscrollPublic.resize();
					//nicesscrollPublic.setScrollTop(0);
				   windowtz.off('scroll',scrollPublicBoxesHandler);
					$( "#publicBoxesOn" ).val( 0 );
					$('html,body').animate({ scrollTop: $("#list_public_boxes").offset().top}, 1000);
				}
				else if( parseInt(data.zeroPublicBoxesFound) == parseInt("1") )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(search_public_alert);
				}
			  }
			});
		}
		else if( parseInt(publicPrivate) == parseInt("0") )
		{
			var privateSearchAjaxUrl = BASE_URL + "/databox/private-search-ajax";

			$.ajax
			({
			  url: privateSearchAjaxUrl,
			  type: "POST",
			  data:{searchTermHolder:searchTermHolder,pvtUniqueCodeHolder:pvtUniqueCodeHolder,},
			  async: false,
			  dataType: "json",
			  success: function(data)
			  {
				if( parseInt(data.zeroPrivateBoxFound) == parseInt("0") )
				{
					if( parseInt($( "#homepageSearched" ).val()) == parseInt("0") )
					{
						if( parseInt($( "#homePageSection" ).val()) == parseInt("1") )
						{
							//publicBoxesScrollTop = nicesxPublic.getScrollTop();
							$('#publicBoxesDiv').html( $('#list_public_boxes').html() );
						}
						$( "#pboxesOnBeforeSearch" ).val( $( "#publicBoxesOn" ).val() );
						$( "#homepageSearched" ).val( 1 );
						$( "#homePageSection" ).val( 1 );
					}
					$('#list_public_boxes').html( data.privateBoxHtml );
					windowtz.off('scroll',scrollPublicBoxesHandler);
					$( "#publicBoxesOn" ).val( 0 );
					$('html,body').animate({ scrollTop: $("#list_public_boxes").offset().top}, 1000);
				}
				else if( parseInt(data.zeroPrivateBoxFound) == parseInt("1") )
				{
					$('#pop-up-alerts').popUpWindow({action: "open"});	
					$("#alert_header_meassge").html('');
					$("#alert_meassage").html(search_private_alert);
				}
			  }
			});
		}
	});
});
