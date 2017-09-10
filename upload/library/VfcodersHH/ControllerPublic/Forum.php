<?php

class VfcodersHH_ControllerPublic_Forum extends XFCP_VfcodersHH_ControllerPublic_Forum
{
     /**
	  * Displays a form to create a new thread in this forum.
	  *
	  * @return XenForo_ControllerResponse_Abstract
	  */
	 public function actionCreateThread()
	 {
	     $response = parent::actionCreateThread();
		 if(isset($response->params['forum'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['forum']['node_id'], $response->params['forum'], $errorPhraseKey);
		 }
		 
		 return $response;
	 }
	 
	 /**
	  * Shows a preview of the thread creation.
	  *
	  * @return XenForo_ControllerResponse_Abstract
	  */
	 public function actionCreateThreadPreview()
	 {
           $response = parent::actionCreateThreadPreview();
		   if(isset($response->params['forum'])){
		     $permission = $this->_getHHPermissionModel();		 
		     $permission->getTagPermission($response->params['forum']['node_id'], $response->params['forum'], $errorPhraseKey);
		   }

		   $parser = new VfcodersHH_Helper_Parse();
		   $response->params['message'] = $parser->parse_hidetags($response->params['message'], $response->params['forum']['node_id'], 
				   0, XenForo_Visitor::getUserId());
				   
		   return $response;		   		   
	 }
	 
     protected function _getHHPermissionModel()
	 {
		 return $this->getModelFromCache('VfcodersHH_Model_Permission');
	 }
}