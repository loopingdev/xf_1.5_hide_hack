<?php

class VfcodersHH_ControllerPublic_Thread extends XFCP_VfcodersHH_ControllerPublic_Thread
{
	/**
	 * Displays a form to add a reply to a thread.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */

	public function actionReply()
	{
		$response = parent::actionReply();

		if(isset($response->params['forum'], $response->params['thread'])){
			$permission = $this->_getHHPermissionModel();		 
			$permission->getTagPermission($response->params['thread']['node_id'], $response->params['forum'], $errorPhraseKey);
		}

		if ($quoteId = $this->_input->filterSingle('quote', XenForo_Input::UINT) AND isset($response->params['defaultMessage']))
		{
			$response->params['defaultMessage'] = VfcodersHH_Helper_Strip::strip_hidetags(
				$response->params['defaultMessage'], 
			  	new XenForo_Phrase('vfchh_strip_noquote')
			);
		}
		 
		return $response;
	}
		
	/**
	 * Inserts a new reply into an existing thread.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */	 
	public function actionAddReply()
	{
	      $response = parent::actionAddReply();
		  if(isset($response->params['forum'], $response->params['thread'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['thread']['node_id'], $response->params['forum'], $errorPhraseKey);
		  }
		  
		  if (isset($response->params['posts'], $response->params['forum']) AND count($response->params['posts']))
		  {			  



		      $parser = new VfcodersHH_Helper_Parse();
		      foreach ($response->params['posts'] AS &$post)
			  {
			       $post['message'] = $parser->parse_hidetags($post['message'], $response->params['forum']['node_id'], $post['thread_id'], 
				                      $post['user_id'], $post['post_id'], $this->_getAllLikes($post['post_id']));
			  }
		  }
		  return $response;
	}

	public function actionIndex()
	{
	     $response = parent::actionIndex();
		 if(isset($response->params['forum'], $response->params['thread'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['thread']['node_id'], $response->params['forum'], $errorPhraseKey);
		 }
		 
		 if (isset($response->params['posts'], $response->params['forum']) AND count($response->params['posts']))
		 {			  
		      $parser = new VfcodersHH_Helper_Parse();
		      foreach ($response->params['posts'] AS &$post)
			  {

			       $post['message'] = $parser->parse_hidetags($post['message'], $response->params['forum']['node_id'], $post['thread_id'], 
				                      $post['user_id'], $post['post_id'], $this->_getUserLikedPost($post['post_id']));
			  }
		 }
		 
		 // strip hidetags from meta description
		 if (isset($response->params['firstPost']['message']))
		 {
		      $response->params['firstPost']['message'] = VfcodersHH_Helper_Strip::strip_hidetags($response->params['firstPost']['message'], '');
		 }
		 
		 /* pre-load vfchh javascript template
		 if (class_exists('XenForo_Template_Public'))
	     {
		      XenForo_Template_Public::preloadTemplate('vfchh_js');
		 }*/
		 		 
		 return $response;
	}

	public function actionShowPosts()
	{
	     $response = parent::actionShowPosts();
		 if(isset($response->params['forum'], $response->params['thread'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['thread']['node_id'], $response->params['forum'], $errorPhraseKey);
		 }
		 
		 if (isset($response->params['posts'], $response->params['forum']) AND count($response->params['posts']))
		 {			  
		      $parser = new VfcodersHH_Helper_Parse();
		      foreach ($response->params['posts'] AS &$post)
			  {
			       $post['message'] = $parser->parse_hidetags($post['message'], $response->params['forum']['node_id'], $post['thread_id'], 
				                      $post['user_id'], $post['post_id'], $this->_getUserLikedPost($post['post_id']));
			  }
		 }
		 
		 return $response;	  
	} 

	/**
	 * Gets a preview of the first post in a thread.
	 *
	 * @return XenForo_ControllerResponse_Abstract
	 */
	public function actionPreview()
	{
	     $response = parent::actionPreview();
		 if(isset($response->params['forum'], $response->params['thread'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['thread']['node_id'], $response->params['forum'], $errorPhraseKey);
		 }
		 
		 if (isset($response->params['post'], $response->params['forum']))
		 {			  
		      $parser = new VfcodersHH_Helper_Parse();
		      if ($response->params['post']['post_id'])
			  {
			       $post =& $response->params['post'];
			       $post['message'] = $parser->parse_hidetags($post['message'], $response->params['forum']['node_id'], 
				   $post['thread_id'], $post['user_id'], $post['post_id'], 
				   $this->_getUserLikedPost($post['post_id']));
			  }
		 }

	     return $response;		 
	}

	/**
	* Shows a preview of the reply.
	*
	* @return XenForo_ControllerResponse_Abstract
	*/
	public function actionReplyPreview()
	{
	       $response = parent::actionReplyPreview();
		   if (isset($response->params['thread'], $response->params['forum'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['forum']['node_id'], $response->params['forum'], $errorPhraseKey);
		   }

		   $parser = new VfcodersHH_Helper_Parse();
		   $response->params['message'] = $parser->parse_hidetags($response->params['message'], $response->params['forum']['node_id'], 
				   $response->params['thread']['thread_id'], XenForo_Visitor::getUserId());
				   
		   return $response;	
	}

	public function actionAddReplyHideCheck()
	{
		if ($this->_noRedirect())
		{
		    return $this->responseReroute('XenForo_ControllerPublic_Thread', 'show-posts');
		}
	}

	public function actionAjaxFirstPost()
	{
	     $response = parent::actionAjaxFirstPost();
		 if(isset($response->params['forum'], $response->params['thread'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['thread']['node_id'], $response->params['forum'], $errorPhraseKey);
		 }
		 
		 if (isset($response->params['posts'], $response->params['forum']) AND count($response->params['posts']))
		 {			  
		      $parser = new VfcodersHH_Helper_Parse();
		      foreach ($response->params['posts'] AS &$post)
			  {
			       $post['message'] = $parser->parse_hidetags($post['message'], $response->params['forum']['node_id'], $post['thread_id'], 
				                      $post['user_id'], $post['post_id'], $this->_getUserLikedPost($post['post_id']));
			  }
		 }
		 
		 return $response;
	}

	public function actionMultiQuote() 
	{
		$response = parent::actionMultiQuote();
		if (isset($response->params['quote']))
		{
		    $response->params['quote'] = VfcodersHH_Helper_Strip::strip_hidetags($response->params['quote'], 
			new XenForo_Phrase('vfchh_strip_noquote'));
		} 

		if (isset($response->params['posts']) && !empty($response->params['posts'])) {
			foreach($response->params['posts'] AS &$post) {
				$post['message'] = VfcodersHH_Helper_Strip::strip_hidetags(
					$post['message'], 
					new XenForo_Phrase('vfchh_strip_noquote')
				);
			}
		}
		
		return $response;
	}

	protected function _getHHPermissionModel()
	{
		return $this->getModelFromCache('VfcodersHH_Model_Permission');
	}

	protected function _getUserLikedPost($postid)
    {
        // get viewing user_id
        $visitor = XenForo_Visitor::getInstance()->toArray();
        $userid = $visitor['user_id'];

        //check if we are viewing this as a guest
        if ($userid == 0)
            return false;

        //check if user has liked
        $query = "select like_id from xf_liked_content where like_user_id = $userid and content_type = 'post' and content_id = $postid";
        $db = $db = XenForo_Application::get('db');
        $query_results = $db->fetchAll($query);

       // var_dump($query_results);

        if (sizeof($query_results) == 1) {
            return true;
        } elseif (sizeof($query_results) == 0) {
            return false;
        } else
        {
            throw new XenForo_Exception('Multiple Thanks from the same user. Check your Database', true);
            return false;
        }

	}

}
