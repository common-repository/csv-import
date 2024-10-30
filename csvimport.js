function posttypes()	{
	var type = document.getElementById('post_type_sm').value;
	if(type == 'select')	{
		jQuery("#importnow").hide();
		return false;
	}

	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data:	{
			'action':'smack_ci_select_post_type',
			'type':type
		},
		success:function(data)	{
		jQuery('.csv-body').html(data);
		jQuery('#your-modal').modal('toggle');
		jQuery('#your-modal').on('hidden.bs.modal',function (e){
		jQuery('.complete_button').hide();
		jQuery("#importnow").show();
		});
		},
		error: function(errorThrown)	{
		console.log(errorThrown);
		}
		});

	}

 function importrightnow()	{
	var type = document.getElementById('post_type_sm').value;
	var total_row_count = document.getElementById('total_row_count').value;
	var i = document.getElementById('i').value;
		jQuery("#log").show();
		jQuery('.ajax_loader').show();
	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data:	{
			'action':'smack_ci_import_records',
			'type':type,
			'i':i
		},
		success:function(data)	{
		var dat = JSON.parse(data);
		
//			alert(dat.next_import);
		if(parseInt(total_row_count) >= parseInt(dat.next_import))	{
			document.getElementById("i").value = dat.next_import;
				importrightnow();
		}
		else{
			jQuery('.ajax_loader').hide();
			jQuery('#importnow').hide();
			jQuery('.complete_button').show();
		}	
			
			dat = jQuery.parseJSON(data);
			jQuery('.log-body').append(dat.log);
			jQuery('.log-error').append(dat.errors);
			jQuery('.log-update').append(dat.update);
			jQuery('.log-insert').append(dat.insert);
			jQuery('#log-modal').modal('show');
			jQuery('#log-modal').on('hidden.bs.modal',function (e){
		//	jQuery('#log_link').show();
			});
		
		},
		error:	function(errorThrown)	{
		console.log(errorThrown);	
		}
		});
}
	
