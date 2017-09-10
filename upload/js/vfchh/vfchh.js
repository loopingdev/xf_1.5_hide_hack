/*======================================================================*\
|| #################################################################### ||
                      vFcoders - Hide Hack
		Copyright ©2013 All rights reserved by www.vfcoders.com
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

/** @param {jQuery} $ jQuery Object */
!function($, window, document, _undefined)
{
    XenForo.HideThanks = function($link)
	{
        $link.click(function(e)
		{
			e.preventDefault();
			
			if (!$(this).closest("li.message").find('.bbCodeVfcHH').length)
			return;
												
            var url = this.href + '-hide-check';
			var $link = $(this);
			
			setTimeout(function() { 
			   XenForo.ajax(url, {}, function(ajaxData, textStatus)
			   {
				    if (XenForo.hasResponseError(ajaxData))
				    {
					     return false;
				    }
					
					if (XenForo.hasTemplateHtml(ajaxData, 'messagesTemplateHtml') || XenForo.hasTemplateHtml(ajaxData))
				    {
                         XenForo.showMessages(ajaxData, $link, 'fadeIn');
					}
					
			   }); }, 1000);
			
		});	
    };   
	
	XenForo.HideReply = function($form)
	{
		 $("#QuickReply").bind("QuickReplyComplete", function(e)
		 {
			      window.hc_postids = '';
			      $("li.message").each(function() {
						 if ($(this).find('div.HiddenContent').length)
						 {
							 window.hc_postids += '&' + encodeURIComponent('messageIds[]') + '=' + encodeURIComponent(this.id);
						 }
				  });
					
			      if (window.hc_postids != '')
				  {
					     var url = $form.attr('action') + '-hide-check';
						 XenForo.ajax(url, window.hc_postids, function(ajaxData, textStatus)
			             {
				              if (XenForo.hasResponseError(ajaxData))
				              {
					              return false;
				              }
							  
							  if (XenForo.hasTemplateHtml(ajaxData, 'messagesTemplateHtml') || XenForo.hasTemplateHtml(ajaxData))
				              {
                                    XenForo.showMessages(ajaxData, $form, 'fadeIn');
					          }
					    });
			      }	
		 });
    }; 
	
	// register the HideThanks functionality
	XenForo.register('a.LikeLink', 'XenForo.HideThanks');
    XenForo.register('#QuickReply', 'XenForo.HideReply');	
}
(jQuery, this, document);	