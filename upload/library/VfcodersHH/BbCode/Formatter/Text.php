<?php

class VfcodersHH_BbCode_Formatter_Text extends XFCP_VfcodersHH_BbCode_Formatter_Text
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
		        $this->_tags["$hidetag"] = array ('callback' => array($this, 'handleTag'));
				$this->_advancedReplacements["$hidetag"] = array('$this', 'handleVfchhTags');
		   }
		   
		   foreach ($orig_hidetags AS $hidetag)
		   {
		        $this->_tags["$hidetag"] = array ('callback' => array($this, 'handleTag'));
		   }
		   
		   return $this->_tags;		   
	  }
	  
	  public function handleVfchhTags(array $tag, array $rendererStates)
	  {
	       $output = $this->renderSubTree($tag['children'], $rendererStates);
	       if ($tag['option'])
		   {
	           $parts = explode(',', $tag['option']);	
		   
		       $node_id = isset($parts[1]) ? intval(trim($parts[1])) : 0;
               $unhidden = intval($parts[0]) == 1 ? true : false;
			   
			   if (!$node_id OR !XenForo_Visitor::getInstance()->hasNodePermissionsCached($node_id))
               return $output;
			   
			   return (($unhidden) ? $output : new XenForo_Phrase('vfchh_preview_hidden_content'));
		   }
		   
		   return $output;
	  }

}