<?php

class VfcodersHH_ControllerPublic_Account extends XFCP_VfcodersHH_ControllerPublic_Account
{
    /**
	 * Show the most recent items for a user's news feed
	 *
	 * @return XenForo_ControllerResponse_View
	 */
	public function actionNewsFeed()
	{
	     $response = parent::actionNewsFeed();
		 foreach ($response->subView->params['newsFeed'] AS &$feed)
		 {    
		     if (!isset($feed['content']['message']))
			 continue;
			 
		     $feed['content']['message'] = VfcodersHH_Helper_Strip::strip_hidetags($feed['content']['message'], 
			   new XenForo_Phrase('vfchh_strip_email'));
		 }
		 
		 return $response;
	}

}