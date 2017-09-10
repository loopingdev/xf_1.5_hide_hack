<?php

class VfcodersHH_BbCode_Formatter_BbCode_AutoLink extends XFCP_VfcodersHH_BbCode_Formatter_BbCode_AutoLink
{
      protected $_tags = null;
	   
	  public function __construct()
	  {
	        $original_hide_tags = VfcodersHH_Helper_Tags::$_originalhidetags;
			//$this->_disableAutoLink = array_merge($this->_disableAutoLink, $original_hide_tags); 
										 
			parent::__construct();							 
	  }
	 	  
      public function getTags()
	  {
	       if ($this->_tags !== null)
		   {
			    return $this->_tags;
		   }
		   
	       $this->_tags = parent::getTags();
		   $orig_hidetags = VfcodersHH_Helper_Tags::$_originalhidetags;
		   
		   foreach ($orig_hidetags AS $hidetag)
		   {
		        $this->_tags["$hidetag"] = $this->_generalTagCallback ? array ('callback' => $this->_generalTagCallback) : array();
		   }
		   
		   return $this->_tags;		   
	  }	   
}
