<script type="text/javascript">
	jQuery(document).ready(function()
	{
		var postreplyid = '#' + 'comment-post_';
		
		jQuery('a.add_coment').live("click", function(e)
		{
			jQuery('.comment-block__input-form').show();
		
		});
		
		
		jQuery('.comment-post__reply-button').live("click", function()
		{
			if(jQuery(this).hasClass('active'))
			{
				jQuery(this).html('Ответить');
				jQuery('.comment-post__reply-button').removeClass('active');
				
				jQuery('#pcomment_value').val(0);
				jQuery('.comment-block__input-form').insertAfter('.comment-empty');
			}
			else
			{
				jQuery(this).html('Отмена');
				jQuery(this).addClass('active');
				
				jQuery('input[name=pcomment]').val(this.id)
				jQuery('.comment-block__input-form').insertAfter(postreplyid + this.id);
			}
			return false;
		});
		
		jQuery('#comment-form').live("submit", function()
		{
			var alertE = ''; var _self = this;
			
			jQuery.ajax(
			{
				url: this.action + '/status',
				data: jQuery(this).serialize(),
				type: 'POST',
				beforeSend: function() 
				{					
					onLoadInterfaceComment(1);
				},
				success: function(status) 
				{
					onLoadInterfaceComment(0);
				
					if(status.success == true)
					{
						jQuery('.comment-block').load(_self.action + '/comments/json');
					}
					
					alert(status.message);
				}
			});			
		
			return false;
		});
		
		function onLoadInterfaceComment(status)
		{
			switch(status)
			{
				case 0 :
					jQuery('.submit-button').show();
					jQuery('#comment-form input').removeProp("disabled");
					jQuery('.ajax-loader').remove();
					
					jQuery('.captcha').attr('src', '/xnum.php?date=' + new Date().getTime());			
				break;
				
				case 1 :
					jQuery('.submit-button').hide();
					jQuery('#comment-form input').prop("disabled", true);				
					jQuery('.submit-button').after('<img class="ajax-loader" src="/tpl/img/loading.gif" />');
				break;
			}
		}
	});
</script>