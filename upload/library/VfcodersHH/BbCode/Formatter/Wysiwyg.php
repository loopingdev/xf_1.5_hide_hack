<?php

class VfcodersHH_BbCode_Formatter_Wysiwyg extends XFCP_VfcodersHH_BbCode_Formatter_Wysiwyg
{
	  protected $_tags = null;
	  
      public function getTags()
	  {
	       if ($this->_tags !== null)
		   {
			    return $this->_tags;
		   }
		   
	       $this->_tags = parent::getTags();
		   $orig_hidetags = VfcodersHH_Helper_Tags::$_originalhidetags;
		   $hidetags = VfcodersHH_Helper_Tags::$_pseudohidetags;
		   
		   foreach ($hidetags AS $hidetag)
		   {
				$this->_tags["$hidetag"] = array('callback' => array($this, 'renderTagUndisplayable'));
		   }
		   
		   foreach ($orig_hidetags AS $hidetag)
		   {
		        $this->_tags["$hidetag"] = array('callback' => array($this, 'renderTagUndisplayable'));
		   }
		   
		   return $this->_tags;		   
	  }
}