!function($, window, document, _undefined)
{	
	if(typeof RedactorPlugins == 'undefined')				
		RedactorPlugins = {};
	
	RedactorPlugins['vfcHH'] = 
	{
		init: function()
		{
		    if (typeof ActiveHideButtons == 'undefined')
			return;
			
		    var a = XenForo.BbCodeWysiwygEditor.prototype,
            		self = this,
			hideButtons = this.createHideButtons(a);					
			$.extend(self.opts.toolbar, hideButtons);	
 						
			var addToolbarButton = function($toolbar, key)
			{
				if (key !== '|' && typeof self.opts.toolbar[key] !== 'undefined')
				{
					var s = self.opts.toolbar[key];
					$toolbar.append($('<li class="redactor_btn_container_' + key + '">').append(self.buildButton(key, s)));
				}
			};
					
			$.each(ActiveHideButtons, function(i,key)
			{
				if ($.isArray(key))
				{
					var $list = $('<ul />');
					$.each(key, function(j, child) {
						addToolbarButton($list, child);
					});

					self.$toolbar.append($('<li class="redactor_btn_group" />').append($list));
				}
				else
				{
					addToolbarButton(self.$toolbar, key);
				}
			});
		},
		createHideButtons: function(a)
		{
			var c = this;			
			var hide_mappedto = function (b) {
				switch (htmt) {
					case 'hp':						
						c.getHidePostsModal(b, 'hide', 'HIDE', a);
						break;
					case 'stg':
						c.getSTGModal(b, 'hide', 'HIDE', a);
						break;
					case 'hr':
					case 'hrt':
					case 'ht':
					default:
						a.wrapSelectionInHtml(b, "[HIDE]", "[/HIDE]", 0);
						break;
				}
			}
			
			return {
				hreply: {
					title: 'HIDE-REPLY',				
					callback: function (b) {
                        			a.wrapSelectionInHtml(b, "[HIDE-REPLY]", "[/HIDE-REPLY]", 0);
                   			 }				
				},
				hthanks: {
					title: 'HIDE-THANKS',				
					callback: function (b) {
                        			a.wrapSelectionInHtml(b, "[HIDE-THANKS]", "[/HIDE-THANKS]", 0);
                   			 }				
				},
				hrt: {
					title: 'HIDE-REPLY-THANKS',				
					callback: function (b) {
                        			a.wrapSelectionInHtml(b, "[HIDE-REPLY-THANKS]", "[/HIDE-REPLY-THANKS]", 0);
                   			 }
				},
				hposts: {
					title: 'HIDE-POSTS',
					callback: function (b) {
						c.getHidePostsModal(b, 'hposts', 'HIDE-POSTS', a);
					}
				},	
				stg: {	
					title: 'SHOWTOGROUPS',
					callback: function (b) {
						c.getSTGModal(b, 'stg', 'SHOWTOGROUPS', a);
					}
				},
                		hide: {
					title: 'HIDE',
					callback: hide_mappedto
                		}				
			}
		},

		getHidePostsModal: function (a, ht, tt, d) {
            		var c = this;
			a.saveSelection();			
	   		a.modalInit(RELANG.vfchh.hidecontent, {
		        	url: c.opts.dialogUrl + "&dialog=hide&hidetype=" + ht + "&tagtitle=" + tt
		   	 }, 600, $.proxy(function () {
				$("#redactor_insert_hide_btn").click(function (b) {
				    b.preventDefault();
				    c.insertHideContent(b, a, tt);
				});
		        setTimeout(function () {
		            $("#redactor_hide_postcount").focus();
		        }, 100)
		    }, a))
		},
		
		getSTGModal: function (a, ht, tt, d) {
     	        	var c = this;
			a.saveSelection();			
		   	a.modalInit(RELANG.vfchh.showtogroups, {
				url: c.opts.dialogUrl + "&dialog=hide&hidetype=" + ht + "&tagtitle=" + tt
		    	}, 800, $.proxy(function () {
				$("#redactor_insert_hide_btn").click(function (b) {
				    b.preventDefault();
				    c.insertHideContent(b, a, tt);
				});
		    	}, a))
        	},
		insertHideContent: function (a, c, b)
		{
		    var e, o, val = [];
			switch ($("#vfchh_hidetype").val())
			{
				case 'post':
					o = $("#redactor_hide_postcount").val(); o = (o > 0) ? '=' + o : '';
						break;
				case 'stg':
					if ($(".stg:checked").length) {
							$('.stg:checked').each(function(i){ 
								val[i] = $(this).val(); 
							});							  
					}
					o = val.length ? '=' + val.join(",") : '';
                         	break;						 
			}
			
		    b = "[" + b + o + "]" + this.getHideContent(e, c) + "[/" + b + "]";           
		    c.restoreSelection();
		    c.execCommand("inserthtml", b);
		    c.modalClose();
		},
		getHideContent: function(e, c)
		{
			e = $("#redactor_hide_code").val();
			e = e.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/\t/g, "    ").replace(/  /g, "&nbsp; ").replace(/\n/g, "</p>\n<p>");
			e.match(/\n/) && (e = ("<p>" + e + "</p>").replace(/<p><\/p>/,
                "<p>" + (!$.browser.msie ? "<br>" : "&nbsp;") + "</p>"));				
			var d = c.getSelectedHtml(),
			d = d.replace(/^(<p[^>]*>)?/, "$1").replace(/(<\/p>)?$/, "$1");			
			return (d + e);
		}
	}	

}(jQuery, this, document);
