<?php

class VfcodersHH_ControllerPublic_Post extends XFCP_VfcodersHH_ControllerPublic_Post
{
     /**
	  * Displays a form to edit an existing post.
	  *
	  * @return XenForo_ControllerResponse_Abstract
	  */
	  
	  public function actionEdit()
	  {
	        $response = parent::actionEdit();
			if(isset($response->params['forum'], $response->params['thread'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['thread']['node_id'], $response->params['forum'], $errorPhraseKey);
		    }
		 
		    return $response;
	  }
	  
	  /**
	   * Displays a simple form to edit an existing post inline
	   *
	   * @return XenForo_ControllerPublic_Abstract
	   */
	  public function actionEditInline()
	  {
	        $response = parent::actionEditInline();
			if(isset($response->params['forum'], $response->params['thread'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['thread']['node_id'], $response->params['forum'], $errorPhraseKey);
		    }
		 
		    return $response;
	  }
	  
	  public function actionLikeHideCheck()
	  {
	        $this->_assertPostOnly();
			$postId = $this->_input->filterSingle('post_id', XenForo_Input::UINT);

		    $ftpHelper = $this->getHelper('ForumThreadPost');
		    list($post, $thread, $forum) = $ftpHelper->assertPostValidAndViewable($postId);
			
			if ($this->_noRedirect())
		    {
			     $this->_request->setParam('thread_id', $thread['thread_id']);
			     return $this->responseReroute('XenForo_ControllerPublic_Thread', 'show-posts');
		    }
			else
			{
				 return $this->getPostSpecificRedirect($post, $thread);
			}
	  }
	  
	  public function actionQuote()
      {
            $response = parent::actionQuote();
			if (isset($response->params['quote']))
			{
			     $response->params['quote'] = VfcodersHH_Helper_Strip::strip_hidetags($response->params['quote'], 
				  new XenForo_Phrase('vfchh_strip_noquote'));
			} 
			
			return $response;
			
      }	  
 	  
	  protected function _getHHPermissionModel()
	  {
		    return $this->getModelFromCache('VfcodersHH_Model_Permission');
	  }
}