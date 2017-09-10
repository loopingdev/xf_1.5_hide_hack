<?php

class VfcodersHH_Search_DataHandler_Thread extends XFCP_VfcodersHH_Search_DataHandler_Thread
{
      protected $_HHPermissionModel;
	  protected $_parser;
	  
      public function prepareResult(array $result, array $viewingUser)
	  {
	         if (isset($result['permissions']) AND count($result['permissions']))
			 {
	              $this->_getHHPermissionModel()->getTagPermission($result['node_id'], array('node_id' => $result['node_id']), 
			      $errorPhraseKey, $result['permissions'], $viewingUser);
				  
				  if (!$this->_parser)
				  $this->_parser = new VfcodersHH_Helper_Parse(false);
				  
				  $result['message'] = $this->_parser->parse_hidetags($result['message'], $result['node_id'], 
				   $result['thread_id'], $result['user_id'], $result['first_post_id']);
				  
			 } 
	         //node_permission_cache
			 return parent::prepareResult($result, $viewingUser);
	  }

	  /**
	 * @return XenForo_Model_Thread
	 */
	  protected function _getHHPermissionModel()
	  {
		  if (!$this->_HHPermissionModel)
		  {
			  $this->_HHPermissionModel = XenForo_Model::create('VfcodersHH_Model_Permission');
		  }

		  return $this->_HHPermissionModel;
	 }
}