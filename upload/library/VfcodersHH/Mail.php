<?php

class VfcodersHH_Mail extends XFCP_VfcodersHH_Mail
{
       public function __construct($emailTitle, array $params, $languageId = null)
	   {
	           parent::__construct($emailTitle, $params, $languageId);
			   
	           if (isset($this->_params['reply']['messageText']))
			       $this->_params['reply']['messageText'] = VfcodersHH_Helper_Strip::strip_hidetags($this->_params['reply']['messageText'], 
			           new XenForo_Phrase('vfchh_strip_email'));
			   
			   if (isset($this->_params['reply']['messageHtml']))
			       $this->_params['reply']['messageHtml'] = VfcodersHH_Helper_Strip::strip_hidetags($this->_params['reply']['messageHtml'], 
			           new XenForo_Phrase('vfchh_strip_email'));
	  }
}