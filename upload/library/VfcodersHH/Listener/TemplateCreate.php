<?php

class VfcodersHH_Listener_TemplateCreate
{
	public static function preloader($templateName, array &$params, XenForo_Template_Abstract $template)
	{
		if ($templateName == 'editor')
		{
		    $template->addRequiredExternal('js', 'js/vfchh/hh_bb_code.js');
			$template->preloadTemplate('vfchh_bbcode_js');
		}
	}

}