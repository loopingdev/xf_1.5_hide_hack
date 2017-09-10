<?php

class VfcodersHH_ControllerPublic_Member extends XFCP_VfcodersHH_ControllerPublic_Member
{
     public function actionRecentActivity()
	 {
		$response = parent::actionRecentActivity();

		if (isset($response->params['newsFeed']) && !empty($response->params['newsFeed']))
		{	  
			foreach ($response->params['newsFeed'] AS &$feed)
			{    
				if (!isset($feed['content']['message']))
				continue;
		 
				$replace = ($feed['user_id'] ==  XenForo_Visitor::getUserId()) ? '\3' : new XenForo_Phrase('vfchh_strip_email');			 
				$feed['content']['message'] = VfcodersHH_Helper_Strip::strip_hidetags($feed['content']['message'], $replace);
			}
		}

		return $response;
     }
}