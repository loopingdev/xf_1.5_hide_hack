<?php

class VfcodersHH_BbCode_Formatter_Base extends XFCP_VfcodersHH_BbCode_Formatter_Base
{
    protected $_tags;
	protected $_params = array();
		
    public function getTags()
    {
	    if ($this->_tags !== null)
		{
			    return $this->_tags;
		}
		   
	    if (class_exists('XenForo_Template_Public'))
	    {
		    XenForo_Template_Public::preloadTemplate('bb_code_tag_vfchh');
		}
		
		$this->_tags = parent::getTags();
		$orig_hidetags = VfcodersHH_Helper_Tags::$_originalhidetags;
		$hidetags = VfcodersHH_Helper_Tags::$_pseudohidetags;
		
		foreach ($hidetags AS $hidetag)
		{
		        $this->_tags["$hidetag"] = array('trimLeadingLinesAfter' => 2, 'stopAutoLink' => true, 'callback' => array($this, 'renderVfchhTags'));
		}
		   
		foreach ($orig_hidetags AS $hidetag)
		{
		        $this->_tags["$hidetag"] = array('replace' => array('', ''), 'stopAutoLink' => true);
		}
				
		
		return $this->_tags;	
    }
	
	/**
	 * Renders hide tags.
	 *
	 * @param array $tag Information about the tag reference; keys: tag, option, children
	 * @param array $rendererStates Renderer states to push down
	 *
	 * @return string Rendered tag
	 */
	public function renderVfchhTags(array $tag, array $rendererStates)
	{				
	    $content = $this->renderSubTree($tag['children'], $rendererStates);
		
		if ($content === '' OR !$tag['option'])
		{
			return '';
		}
		
		$parts = explode(',', $tag['option']);		
		$node_id = isset($parts[1]) ? intval(trim($parts[1])) : 0;
        $unhidden = intval($parts[0]) == 1 ? true : false;
	    $arg = isset($parts[2]) ? trim($parts[2]) : 0;
	
        if (!$node_id OR !XenForo_Visitor::getInstance()->hasNodePermissionsCached($node_id))
        return $content;
		
		$output = array();		
		switch ($tag['tag'])
		{
		     case 'vfc_hr':
			     $output = $this->renderVfcHR($content, $node_id, $unhidden);
			     break;	 
		     case 'vfc_ht':
			     $output = $this->renderVfcHT($content, $node_id, $unhidden);
			     break;	
		     case 'vfc_hrt':
			     $output = $this->renderVfcHRT($content, $node_id, $unhidden);
			     break;	
		     case 'vfc_hp':
			     $arg = intval($arg);
			     $output = $this->renderVfcHP($content, $node_id, $unhidden, $arg);
			     break;		            			 
			 case 'vfc_hh':
                 $output = $this->renderVfcHH($content, $node_id, $unhidden, $arg);
                 break;
             case 'vfc_stg':
			     $arg = $arg ? explode(';', $arg) : array();
                 $output = $this->renderVfcSTG($content, $node_id, $unhidden, $arg);
                 break;				 
		}
		
		if ($output['tagdisabled'])
		return $content;
				
		$title = $unhidden ? new XenForo_Phrase('vfchh_unhidden_content') : new XenForo_Phrase('vfchh_hidden_content');
		
		if ($this->_view)
		{
			$template = $this->_view->createTemplateObject('bb_code_tag_vfchh', array(
				'content' => $output['content'],
				'title'   => $title,
				'type'    => $unhidden ? 'Unhidden' : 'Hidden'
			));
			return $template->render();
		}
		else
		{
		    return '<pre style="margin: 1em auto" title="$title">' . $output['content'] . '</pre>';
		}
	}
	
	public function renderVfcHR($content, $node_id = 0, $unhidden = false)
	{
	    if (!XenForo_Application::get('options')->vfchh_bbcode_hr)
		    return array('tagdisabled' => true);
		else
		{
		    if (!$unhidden)
			$content = new XenForo_Phrase('vfchh_denied_hr');
			
            return array('content' => $content, 'tagdisabled' => false);		
		}	
	}
	
	public function renderVfcHT($content, $node_id = 0, $unhidden = false)
	{
	    if (!XenForo_Application::get('options')->vfchh_bbcode_ht)
		    return array('tagdisabled' => true);
		else
		{
		    if (!$unhidden)
			$content = new XenForo_Phrase('vfchh_denied_ht');
			
            return array('content' => $content, 'tagdisabled' => false);		
		}	
	}
	
	public function renderVfcHRT($content, $node_id = 0, $unhidden = false)
	{
	    if (!XenForo_Application::get('options')->vfchh_bbcode_hrt)
		    return array('tagdisabled' => true);
		else
		{
		    if (!$unhidden)
			$content = new XenForo_Phrase('vfchh_denied_hrt');
			
            return array('content' => $content, 'tagdisabled' => false);		
		}	
	}
	
	public function renderVfcHH($content, $node_id = 0, $unhidden = false, $arg = null)
	{
	    if (!XenForo_Application::get('options')->vfchh_bbcode_hide)
		    return array('tagdisabled' => true);
		else
		{
		    $tagdisabled = false;
		    if (!$unhidden)
			{
			    $mapped_to = XenForo_Application::get('options')->vfchh_bbcode_hide_map;
			    switch ($mapped_to)
				{
				     case 'ht':
					 case 'hr':
					 case 'hrt':
						$content = new XenForo_Phrase('vfchh_denied_' . $mapped_to);
						break;
					 case 'hp':
					    if (!$arg) { $tagdisabled = true; }
						else {
						   $content = new XenForo_Phrase(array('vfchh_denied_hp_x', 'count' => $arg));	
						}   
                        break;			
					 case 'stg':
					     $arg = $arg ? explode(';', $arg) : array();
                         if (count($arg) && ($groups = $this->_getGroupTitles($arg)))
				                $content = new XenForo_Phrase(array('vfchh_denied_stg', 'groups' => $groups));
                         break;						 
						
				}
			}
            return array('content' => $content, 'tagdisabled' => $tagdisabled);		
		}	
	}
	
	public function renderVfcHP($content, $node_id = 0, $unhidden = false, $post_r = 0)
	{
	    if (!XenForo_Application::get('options')->vfchh_bbcode_hp OR $post_r == 0)
		    return array('tagdisabled' => true);
		else
		{
		    if (!$unhidden)
			$content = new XenForo_Phrase(array('vfchh_denied_hp_x', 'count' => $post_r));
			
            return array('content' => $content, 'tagdisabled' => false);		
		}	
	}
	
	public function renderVfcSTG($content, $node_id = 0, $unhidden = false, array $ugpids)
	{
	    if (!XenForo_Application::get('options')->vfchh_bbcode_stg OR !count($ugpids))
		    return array('tagdisabled' => true);
        else
        { 
		    if (!$unhidden)
			{
				 if (($groups = $this->_getGroupTitles($ugpids)))
				 $content = new XenForo_Phrase(array('vfchh_denied_stg', 'groups' => $groups));
			}
			
            return array('content' => $content, 'tagdisabled' => false);
        }		
	}
	
	private function _getGroupTitles($ugpids = array())
	{
	     $userGroups = XenForo_Model::create('XenForo_Model_UserGroup')->getAllUserGroupTitles();
		 $groups = array();
		 foreach ($userGroups AS $groupid => $groupTitle)
		 {
		        if (in_array($groupid, $ugpids))
				         $groups[] = $groupTitle;
		 }
		 
		 return count($groups) ? implode(', ', $groups) : false;
	}
	
	/**
	 * Renders a code tag.
	 *
	 * @param array $tag Information about the tag reference; keys: tag, option, children
	 * @param array $rendererStates Renderer states to push down
	 *
	 * @return string Rendered tag
	 */
	public function renderTagCode(array $tag, array $rendererStates)
	{
	     $content = parent::renderTagCode($tag, $rendererStates);
		 
		 if (strpos($content, '[vfc_') !== false)
		 {
		      $tag['option'] = 'contain_hidetags';
			  $content = parent::renderTagCode($tag, $rendererStates);
		 }
		 
		 return $content;		 
	}
	
	public function parseValidateTagCode(array $tagInfo, $tagOption)
	{
	     if ($tagOption = 'contain_hidetags')
		     return true;
		 else
	         return parent::parseValidateTagCode($tagInfo, $tagOption);
	}
	
			
}