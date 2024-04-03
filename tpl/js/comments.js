
	jQuery(document).ready(function()
	{
		
		jQuery('.status_bar').hide();
		jQuery('.status_off').hide();	

		jQuery('.input-form__input-file').fileupload(
		{
			dataType: 'json',
			progressall: function(e, data)
			{
				var progress = parseInt(data.loaded / data.total * 100, 10);
				
				onLoadInterfaceComment(1);	
			},
			done: function (e, json) 
			{	
				onLoadInterfaceComment(0);
			
				//jQuery('.input-form__captcha').attr('src', '/xnum.php?date=' + new Date().getTime());
				
					if(json.result == 0)
					{ 
						onLoadInterfaceComment(2);
					}
					else
					{
						switch(json.result.type)
						{
							case 'image' :
								jQuery('input[name=attach_files]').val(jQuery('input[name=attach_files]').val() + ',' + json.result.url);
								jQuery('input[name=attach_files_mini]').val(jQuery('input[name=attach_files_mini]').val() + ',' + json.result.url_mini);
		
								jQuery('.input-form__attachments-image').append('<a href="#" class="cancel-attachment"><img  class="image" src="' + json.result.url_mini + '" /></a>');
							break;
							
							case 'other' :
								jQuery('input[name=attach_files_doc]').val(jQuery('input[name=attach_files_doc]').val() + ',' + json.result.url);
		
								jQuery('.input-form__attachments-files').append('<a href="' + json.result.url + '"  class="cancel-attachment_doc" target="_blank">' + json.result.name + '</a>');
							break;
						}
					}
			
			
			
		
				
				
			}
		});	
		
		
		jQuery('a.cancel-attachment').live("click", function(e)
		{
			var url = $(this).find('img').attr('src');
			
			var value = jQuery('input[name=attach_files]').val().split(',');

				value.splice( jQuery.inArray(url, value), 1 );
				value.join(',');
			
			jQuery('input[name=attach_files]').val(value);
			jQuery('input[name=attach_files_mini]').val(value);
			jQuery(this).remove();
			
			e.preventDefault();
		});	
		
		
		
		
		jQuery('a.cancel-attachment_doc').live("click", function(e)
		{
			var url = $(this).find('a').attr('http');
			
			var value = jQuery('input[name=attach_files_doc]').val().split(',');
				value.splice( jQuery.inArray(url, value), 1 );
				value.join(',');
			
			jQuery('input[name=attach_files_doc]').val(value);
			jQuery(this).remove();
			
			e.preventDefault();
		});	
		


		function onLoadInterfaceComment(status)
		{
						
			switch(status)
			{
				case 0 :
					jQuery('.input-form__input-file').show();	
					jQuery('.status_bar').hide();	
					jQuery('.status_off').hide();					
				
					jQuery('#comment-form input').removeProp("disabled");
					jQuery('.ajax-loader').remove();
				break;
				
				case 1 :
					jQuery('.status_bar').hide();	
					jQuery('.status_off').hide();	
					jQuery('#comment-form input').prop("disabled", true);	
					jQuery('.input-form__input-file').hide();
					jQuery('.status_bar').after('&nbsp;&nbsp;&nbsp;<img class="ajax-loader" src="/tpl/img/loading.gif" />');
				break;
				
				case 2 :
					jQuery('.status_off').show();	
				break;
			}
		}
	});
