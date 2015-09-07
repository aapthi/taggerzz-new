function userCountryBirth(){
	$("#user_country").autocomplete({	
		source: function( request, response ) {
			var keywordsss = $('#user_country').val();
			var hashName='s';
			$.ajax({
				url: BASE_URL+'/users/search-country-names',
				dataType: "json",
				type	: "POST",
				data	:{value:keywordsss},
				success: function( data ) {
					if(data.output!=0) {		
						response( $.map( data.searchHashNames, function( item ) {
							return {
								label: item.ref,
							}
						}));
					}else{
						$(".ui-autocomplete").css("display","none");
					}
				}				
			});
		},
		minLength: 0,	
		open: function(event, ui) {				
			// $(".ui-autocomplete").css("width","876px !important");
		},
		select: function(event, ui) {
			$("#user_country").val(ui.item.label); 
			return false;
		},
		focus: function(event, ui) {
			return false;
		}			
	});
	$("#user_country").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		var hashname=item.label;		
		 return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
		 .data("item.uiAutocomplete", item)            
		 .appendTo(ul);				
	};
}
/******************** Country of Job **********************/
function countryOfJobs(){
	$("#user_country_job").autocomplete({
		source: function( request, response ) {
			var keywordsss = $('#user_country_job').val();
			var hashName='s';
			$.ajax({
				url: BASE_URL+'/users/search-country-names-jobs',
				dataType: "json",
				type	: "POST",
				data	:{value:keywordsss},
				success: function( data ) {
					if(data.output!=0) {		
						response( $.map( data.searchHashNames, function( item ) {
							return {
								label: item.ref,
							}
						}));
					}else{
						$(".ui-autocomplete").css("display","none");
					}
				}				
			});
		},
		minLength: 0,	
		open: function(event, ui) {				
			// $(".ui-autocomplete").css("width","876px !important");
		},
		select: function(event, ui) {
			$("#user_country_job").val(ui.item.label); 
			return false;
		},
		focus: function(event, ui) {
			return false;
		}			
	});
	$("#user_country_job").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		var hashname=item.label;		
		return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
		.data("item.uiAutocomplete", item)            
		.appendTo(ul);				
	};	
}
function JuniorCountry(){
	$("#user_junior_country").autocomplete({
		source: function( request, response ) {
			var keywordsss = $('#user_junior_country').val();
			var hashName='s';
			$.ajax({
				url: BASE_URL+'/users/search-country-names-jobs',
				dataType: "json",
				type	: "POST",
				data	:{value:keywordsss},
				success: function( data ) {
					if(data.output!=0) {		
						response( $.map( data.searchHashNames, function( item ) {
							return {
								label: item.ref,
							}
						}));
					}else{
						$(".ui-autocomplete").css("display","none");
					}
				}				
			});
		},
		minLength: 0,	
		open: function(event, ui) {				
			// $(".ui-autocomplete").css("width","876px !important");
			$("#user_state").val('');
			$("#user_district").val('');
			$("#user_colleges").val('');
		},
		select: function(event, ui) {
			$("#user_junior_country").val(ui.item.label); 
			return false;
		},
		focus: function(event, ui) {
			return false;
		}			
	});
	$("#user_junior_country").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		var hashname=item.label;		
		return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
		.data("item.uiAutocomplete", item)            
		.appendTo(ul);				
	};	
}
function getstates(){
	if($("#user_junior_country").val()!=''){
		$("#user_state").autocomplete({
			source: function( request, response ) {
				var country_name = $("#user_junior_country").val();
				var keywordsss = $('#user_state').val();
				var hashName='s';
				$.ajax({
					url: BASE_URL+'/users/get-states',
					dataType: "json",
					type	: "POST",
					data	:{value:keywordsss,country_name:country_name},
					success: function( data ) {
						if(data.output!=0) {		
							response( $.map( data.searchHashNames, function( item ) {
								return {
									label: item.ref,
								}
							}));
						}else{
							$(".ui-autocomplete").css("display","none");
						}
					}				
				});
			},
			minLength: 0,	
			open: function(event, ui) {				
				// $(".ui-autocomplete").css("width","876px !important");
				$("#user_district").val('');
				$("#user_colleges").val('');
			},
			select: function(event, ui) {
				$("#user_state").val(ui.item.label); 
				return false;
			},
			focus: function(event, ui) {
				return false;
			}			
		});
		$("#user_state").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			var hashname=item.label;		
			return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
			.data("item.uiAutocomplete", item)            
			.appendTo(ul);				
		};	
	}
}
function getDistricts(){
	if($("#user_state").val()!=''){
		$("#user_district").autocomplete({
			source: function( request, response ) {
				var country_name = $("#user_junior_country").val();
				var state_name = $('#user_state').val();
				var keywordsss = $('#user_district').val();
				var hashName='s';
				$.ajax({
					url: BASE_URL+'/users/get-districts',
					dataType: "json",
					type	: "POST",
					data	:{value:keywordsss,country_name:country_name,state_name:state_name},
					success: function( data ) {
						if(data.output!=0) {		
							response( $.map( data.searchHashNames, function( item ) {
								return {
									label: item.ref,
								}
							}));
						}else{
							$(".ui-autocomplete").css("display","none");
						}
					}				
				});
			},
			minLength: 0,	
			open: function(event, ui) {		
				$("#user_colleges").val('');
				// $(".ui-autocomplete").css("width","876px !important");
			},
			select: function(event, ui) {
				$("#user_district").val(ui.item.label); 
				return false;
			},
			focus: function(event, ui) {
				return false;
			}			
		});
		$("#user_district").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			var hashname=item.label;		
			return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
			.data("item.uiAutocomplete", item)            
			.appendTo(ul);				
		};	
	}
}
function getSchools(){
	if($("#user_district").val()!=''){
		$("#user_colleges").autocomplete({
			source: function( request, response ) {
				var country_name = $("#user_junior_country").val();
				var state_name = $('#user_state').val();
				var dist_name = $('#user_district').val();
				var keywordsss = $('#user_colleges').val();
				var hashName='s';
				$.ajax({
					url: BASE_URL+'/users/get-schools',
					dataType: "json",
					type	: "POST",
					data	:{value:keywordsss,country_name:country_name,state_name:state_name,dist_name:dist_name},
					success: function( data ) {
						if(data.output!=0) {		
							response( $.map( data.searchHashNames, function( item ) {
								return {
									label: item.ref,
								}
							}));
						}else{
							$(".ui-autocomplete").css("display","none");
						}
					}				
				});
			},
			minLength: 0,	
			open: function(event, ui) {				
				// $(".ui-autocomplete").css("width","876px !important");
			},
			select: function(event, ui) {
				$("#user_colleges").val(ui.item.label); 
				return false;
			},
			focus: function(event, ui) {
				return false;
			}			
		});
		$("#user_colleges").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			var hashname=item.label;		
			return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
			.data("item.uiAutocomplete", item)            
			.appendTo(ul);				
		};	
	}
}
function bachelorsCountry(){
	$("#user_bachelors_country").autocomplete({
		source: function( request, response ) {
			var keywordsss = $('#user_bachelors_country').val();
			var hashName='s';
			$.ajax({
				url: BASE_URL+'/users/search-country-names-jobs',
				dataType: "json",
				type	: "POST",
				data	:{value:keywordsss},
				success: function( data ) {
					if(data.output!=0) {		
						response( $.map( data.searchHashNames, function( item ) {
							return {
								label: item.ref,
							}
						}));
					}else{
						$(".ui-autocomplete").css("display","none");
					}
				}				
			});
		},
		minLength: 0,	
		open: function(event, ui) {		
			$("#user_bac_unversity").val('');
			$("#user_bac_college").val('');
			// $(".ui-autocomplete").css("width","876px !important");
		},
		select: function(event, ui) {
			$("#user_bachelors_country").val(ui.item.label); 
			return false;
		},
		focus: function(event, ui) {
			return false;
		}			
	});
	$("#user_bachelors_country").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		var hashname=item.label;		
		return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
		.data("item.uiAutocomplete", item)            
		.appendTo(ul);				
	};	
}
function bachelorsUniversity(){
	if($("#user_bachelors_country").val()!=''){
		$("#user_bac_unversity").autocomplete({
			source: function( request, response ) {
				var spec_id = $("#user_bac_speclization").val();				
				var country_name = $("#user_bachelors_country").val();				
				var keywordsss = $('#user_bac_unversity').val();
				var hashName='s';
				$.ajax({
					url: BASE_URL+'/users/get-bachelors-university',
					dataType: "json",
					type	: "POST",
					data	:{value:keywordsss,country_name:country_name,spec_id:spec_id},
					success: function( data ) {
						if(data.output!=0) {		
							response( $.map( data.searchHashNames, function( item ) {
								return {
									label: item.ref,
								}
							}));
						}else{
							$(".ui-autocomplete").css("display","none");
						}
					}				
				});
			},
			minLength: 0,	
			open: function(event, ui) {	
				$("#user_bac_college").val('');
				// $(".ui-autocomplete").css("width","876px !important");
			},
			select: function(event, ui) {
				$("#user_bac_unversity").val(ui.item.label); 
				return false;
			},
			focus: function(event, ui) {
				return false;
			}			
		});
		$("#user_bac_unversity").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			var hashname=item.label;		
			return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
			.data("item.uiAutocomplete", item)            
			.appendTo(ul);				
		};	
	}
}
function bachelorsColleges(){
	if($("#user_bac_unversity").val()!=''){
		$("#user_bac_college").autocomplete({
			source: function( request, response ) {
				var spec_id = $("#user_bac_speclization").val();				
				var country_name = $("#user_bachelors_country").val();				
				var univ_name = $("#user_bac_unversity").val();				
				var keywordsss = $('#user_bac_college').val();
				var hashName='s';
				$.ajax({
					url: BASE_URL+'/users/get-bachelors-colleges',
					dataType: "json",
					type	: "POST",
					data	:{value:keywordsss,country_name:country_name,spec_id:spec_id,univ_name:univ_name},
					success: function( data ) {
						if(data.output!=0) {		
							response( $.map( data.searchHashNames, function( item ) {
								return {
									label: item.ref,
								}
							}));
						}else{
							$(".ui-autocomplete").css("display","none");
						}
					}				
				});
			},
			minLength: 0,	
			open: function(event, ui) {				
				// $(".ui-autocomplete").css("width","876px !important");
			},
			select: function(event, ui) {
				$("#user_bac_college").val(ui.item.label); 
				return false;
			},
			focus: function(event, ui) {
				return false;
			}			
		});
		$("#user_bac_college").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			var hashname=item.label;		
			return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
			.data("item.uiAutocomplete", item)            
			.appendTo(ul);				
		};	
	}
}
function mastersCountry(){
	$("#user_masters_country").autocomplete({
		source: function( request, response ) {
			var keywordsss = $('#user_masters_country').val();
			var hashName='s';
			$.ajax({
				url: BASE_URL+'/users/search-country-names-jobs',
				dataType: "json",
				type	: "POST",
				data	:{value:keywordsss},
				success: function( data ) {
					if(data.output!=0) {		
						response( $.map( data.searchHashNames, function( item ) {
							return {
								label: item.ref,
							}
						}));
					}else{
						$(".ui-autocomplete").css("display","none");
					}
				}				
			});
		},
		minLength: 0,	
		open: function(event, ui) {				
			// $(".ui-autocomplete").css("width","876px !important");
			$("#user_mast_university").val('');
			$("#user_mast_college").val('');
		},
		select: function(event, ui) {
			$("#user_masters_country").val(ui.item.label); 
			return false;
		},
		focus: function(event, ui) {
			return false;
		}			
	});
	$("#user_masters_country").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		var hashname=item.label;		
		return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
		.data("item.uiAutocomplete", item)            
		.appendTo(ul);				
	};	
}
function mastersUniversity(){
	if($("#user_masters_country").val()!=''){
		$("#user_mast_university").autocomplete({
			source: function( request, response ) {
				var spec_id = $("#user_mast_spec").val();				
				var country_name = $("#user_masters_country").val();				
				var keywordsss = $('#user_mast_university').val();
				var hashName='s';
				$.ajax({
					url: BASE_URL+'/users/get-bachelors-university',
					dataType: "json",
					type	: "POST",
					data	:{value:keywordsss,country_name:country_name,spec_id:spec_id},
					success: function( data ) {
						if(data.output!=0) {		
							response( $.map( data.searchHashNames, function( item ) {
								return {
									label: item.ref,
								}
							}));
						}else{
							$(".ui-autocomplete").css("display","none");
						}
					}				
				});
			},
			minLength: 0,	
			open: function(event, ui) {				
				// $(".ui-autocomplete").css("width","876px !important");
				$("#user_mast_college").val('');
			},
			select: function(event, ui) {
				$("#user_mast_university").val(ui.item.label); 
				return false;
			},
			focus: function(event, ui) {
				return false;
			}			
		});
		$("#user_mast_university").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			var hashname=item.label;		
			return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
			.data("item.uiAutocomplete", item)            
			.appendTo(ul);				
		};	
	}
}
function mastersColleges(){
	if($("#user_mast_university").val()!=''){
		$("#user_mast_college").autocomplete({
			source: function( request, response ) {
				var spec_id = $("#user_mast_spec").val();				
				var country_name = $("#user_masters_country").val();				
				var univ_name = $("#user_mast_university").val();				
				var keywordsss = $('#user_mast_college').val();
				var hashName='s';
				$.ajax({
					url: BASE_URL+'/users/get-bachelors-colleges',
					dataType: "json",
					type	: "POST",
					data	:{value:keywordsss,country_name:country_name,spec_id:spec_id,univ_name:univ_name},
					success: function( data ) {
						if(data.output!=0) {		
							response( $.map( data.searchHashNames, function( item ) {
								return {
									label: item.ref,
								}
							}));
						}else{
							$(".ui-autocomplete").css("display","none");
						}
					}				
				});
			},
			minLength: 0,	
			open: function(event, ui) {				
				// $(".ui-autocomplete").css("width","876px !important");
				
			},
			select: function(event, ui) {
				$("#user_mast_college").val(ui.item.label); 
				return false;
			},
			focus: function(event, ui) {
				return false;
			}			
		});
		$("#user_mast_college").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			var hashname=item.label;		
			return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
			.data("item.uiAutocomplete", item)            
			.appendTo(ul);				
		};	
	}
}
function doctoralCountry(){
	$("#user_doctoral_country").autocomplete({
		source: function( request, response ) {
			var keywordsss = $('#user_doctoral_country').val();
			var hashName='s';
			$.ajax({
				url: BASE_URL+'/users/search-country-names-jobs',
				dataType: "json",
				type	: "POST",
				data	:{value:keywordsss},
				success: function( data ) {
					if(data.output!=0) {		
						response( $.map( data.searchHashNames, function( item ) {
							return {
								label: item.ref,
							}
						}));
					}else{
						$(".ui-autocomplete").css("display","none");
					}
				}				
			});
		},
		minLength: 0,	
		open: function(event, ui) {				
			// $(".ui-autocomplete").css("width","876px !important");
			$("#user_doctor_university").val('');
			$("#user_doctor_college").val('');
		},
		select: function(event, ui) {
			$("#user_doctoral_country").val(ui.item.label); 
			return false;
		},
		focus: function(event, ui) {
			return false;
		}			
	});
	$("#user_doctoral_country").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		var hashname=item.label;		
		return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
		.data("item.uiAutocomplete", item)            
		.appendTo(ul);				
	};	
}
function doctoralUniversity(){
	if($("#user_doctoral_country").val()!=''){
		$("#user_doctor_university").autocomplete({
			source: function( request, response ) {
				var spec_id = $("#user_doctor_spec").val();				
				var country_name = $("#user_doctoral_country").val();				
				var keywordsss = $('#user_doctor_university').val();
				var hashName='s';
				$.ajax({
					url: BASE_URL+'/users/get-bachelors-university',
					dataType: "json",
					type	: "POST",
					data	:{value:keywordsss,country_name:country_name,spec_id:spec_id},
					success: function( data ) {
						if(data.output!=0) {		
							response( $.map( data.searchHashNames, function( item ) {
								return {
									label: item.ref,
								}
							}));
						}else{
							$(".ui-autocomplete").css("display","none");
						}
					}				
				});
			},
			minLength: 0,	
			open: function(event, ui) {				
				// $(".ui-autocomplete").css("width","876px !important");
				$("#user_doctor_college").val('');
			},
			select: function(event, ui) {
				$("#user_doctor_university").val(ui.item.label); 
				return false;
			},
			focus: function(event, ui) {
				return false;
			}			
		});
		$("#user_doctor_university").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			var hashname=item.label;		
			return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
			.data("item.uiAutocomplete", item)            
			.appendTo(ul);				
		};	
	}
}

function doctoralColleges(){
	if($("#user_doctor_university").val()!=''){
		$("#user_doctor_college").autocomplete({
			source: function( request, response ) {
				var spec_id = $("#user_doctor_spec").val();				
				var country_name = $("#user_doctoral_country").val();				
				var univ_name = $("#user_doctor_university").val();				
				var keywordsss = $('#user_doctor_college').val();
				var hashName='s';
				$.ajax({
					url: BASE_URL+'/users/get-bachelors-colleges',
					dataType: "json",
					type	: "POST",
					data	:{value:keywordsss,country_name:country_name,spec_id:spec_id,univ_name:univ_name},
					success: function( data ) {
						if(data.output!=0) {		
							response( $.map( data.searchHashNames, function( item ) {
								return {
									label: item.ref,
								}
							}));
						}else{
							$(".ui-autocomplete").css("display","none");
						}
					}				
				});
			},
			minLength: 0,	
			open: function(event, ui) {				
				// $(".ui-autocomplete").css("width","876px !important");
			},
			select: function(event, ui) {
				$("#user_doctor_college").val(ui.item.label); 
				return false;
			},
			focus: function(event, ui) {
				return false;
			}			
		});
		$("#user_doctor_college").data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			var hashname=item.label;		
			return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
			.data("item.uiAutocomplete", item)            
			.appendTo(ul);				
		};	
	}
}
function getEntranceExams(type){
	var idType =  type;
	switch (idType) {		
		case 1:
			idType = 1;
			break;
		case 2:
			idType = 2;
			break;
		case 3:
			idType = 3;
			break;
	} 
	$("#user_entrance_exam_"+idType).autocomplete({
		source: function( request, response ) {
			var keywordsss = $('#user_entrance_exam_'+idType).val();
			var hashName='s';
			$.ajax({
				url: BASE_URL+'/users/get-entrance-exams',
				dataType: "json",
				type	: "POST",
				data	:{value:keywordsss},
				success: function( data ) {
					if(data.output!=0) {		
						response( $.map( data.searchHashNames, function( item ) {
							return {
								label: item.ref,
							}
						}));
					}else{
						$(".ui-autocomplete").css("display","none");
					}
				}				
			});
		},
		minLength: 0,	
		open: function(event, ui) {				
			// $(".ui-autocomplete").css("width","876px !important");
		},
		select: function(event, ui) {
			$("#user_entrance_exam_"+idType).val(ui.item.label); 
			return false;
		},
		focus: function(event, ui) {
			return false;
		}			
	});
	$("#user_entrance_exam_"+idType).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		var hashname=item.label;		
		return $("<li><a data-value='"+item.label+"'>" + item.label + "</a></li>")
		.data("item.uiAutocomplete", item)            
		.appendTo(ul);				
	};	

}