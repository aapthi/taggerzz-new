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

	function openCloseShares(hash_name)
	{
		$('.addthis_sharing_toolbox').attr('data-title',hash_name+' |  - Taggerzz');
		$('#enlist-title').html(hash_name+' |  - Taggerzz');
		var currentShareCatId = $("#currentShareCatId").val();
		$("a[id^='highlightShareLink']").each(function()
		{
			var shareLinkId = parseInt(this.id.replace("highlightShareLink", ""));
			if( parseInt(shareLinkId) == parseInt(currentShareCatId) )
			{
				if( parseInt($("#moStatus"+shareLinkId).val()) == parseInt("0") )
				{
					$("#moStatus"+shareLinkId).val( 1 );
					$("#socialButtonsDiv"+shareLinkId).slideDown();
				}
				else if( parseInt($("#moStatus"+shareLinkId).val()) == parseInt("1") )
				{
					$("#moStatus"+shareLinkId).val( 0 );
					$("#socialButtonsDiv"+shareLinkId).slideUp();
				}
			}
			else
			{
				if( parseInt($("#moStatus"+shareLinkId).val()) == parseInt("1") )
				{
					$("#moStatus"+shareLinkId).val( 0 );
					$("#socialButtonsDiv"+shareLinkId).slideUp();
				}
			}
		});

		return false;
	}
	
	$(document).ready(function()
	{
		$("a[id^='highlightShareLink']").each(function()
		{
			var shareLinkId = parseInt(this.id.replace("highlightShareLink", ""));
			$("#highlightShareLink"+shareLinkId).click(function()
			{
				$("#currentShareCatId").val( shareLinkId );
				var hash_namee=$("#hgltHashTag"+shareLinkId).val();
				openCloseShares(hash_namee);
			});
		});
		$( "#searchTermHolder" ).keyup(function(event)
		{
			var keyCode = event.which;
			if( parseInt(keyCode) == parseInt("13") )
			{
				$( "#hgltsSearchLink" ).click();
			}
			if( $( "#searchTermHolder" ).val().trim() == "" )
			{
				if( parseInt($( "#votepageSearched" ).val()) == parseInt("1") )
				{
					$('#highlightsMainDiv').html($('#hgltsBackUpDiv').html());
					$( "#votepageSearched" ).val( 0 );
				}
			}
		});
		
		$("a[id^='highlightDisplayLink']").each(function()
		{
			var shareLinkId = parseInt(this.id.replace("highlightDisplayLink", ""));
			$("#highlightDisplayLink"+shareLinkId).mousedown(function(event)
			{
				var keyCode = event.which;
				if( parseInt(keyCode) == parseInt("2") )
				{
					event.preventDefault();
					$("#highlightDisplayLink"+shareLinkId).click();
				}
			});
		});
		
		
		$( "#searchTermHolder" ).focus();
	});
