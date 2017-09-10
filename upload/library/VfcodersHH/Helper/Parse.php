<?php

class VfcodersHH_Helper_Parse
{
      protected $_tags = array();
	  protected $_post;
	  protected $_canbypass = false;
	  protected $_db = null;
	  protected $_html;
	  private $_repliedthreads = array();
	  private $_likedpost = array();
	  public $_visitor = null;
	  
	  public function __construct($html = true)
	  {
	        $this->getTags();
			$this->_html = $html;
	  }
	  
	  protected function getTags()
      {		
	      $this->_tags = array(
		  
            'hide-posts' => array(
				'optionRegex' => '([0-9]+)',
				'callback'    => array($this, 'renderHposts'),
				'enabled'     => XenForo_Application::get('options')->vfchh_bbcode_hp ? true : false,
				'withoutOptionCheck' => true
			),			
		    'hide' => array(
				'optionRegex' => '([0-9,\s]+)',
				'hasOption' => in_array(XenForo_Application::get('options')->vfchh_bbcode_hide_map, array('hr', 'hrt', 'ht')) ? false : true,
				'callback' => array($this, 'renderHide'),
				'enabled'     => XenForo_Application::get('options')->vfchh_bbcode_hide,
				'withoutOptionCheck' => true
			),				
		    'hide-reply' => array(	
				'hasOption' => false,
				'callback' => array($this, 'renderHreply'),
				'enabled'     => XenForo_Application::get('options')->vfchh_bbcode_hr,
				'withoutOptionCheck' => true
			),
			'hide-thanks' => array(	
				'hasOption' => false,
				'callback' => array($this, 'renderHthanks'),
				'enabled'     => XenForo_Application::get('options')->vfchh_bbcode_ht,
				'withoutOptionCheck' => true
			),			
			'hide-reply-thanks' => array(	
				'hasOption' => false,
				'callback' => array($this, 'renderHrt'),
				'enabled'     => XenForo_Application::get('options')->vfchh_bbcode_hrt,
				'withoutOptionCheck' => true
			),	
			'showtogroups' => array(
				'optionRegex' => '([0-9,\s]+)',
				'callback'    => array($this, 'renderShowToGroups'),
				'enabled'     => XenForo_Application::get('options')->vfchh_bbcode_stg ? true : false,
				'withoutOptionCheck' => true
			),	
			
          );

          // remove globally disabled tags
   		  foreach ($this->_tags AS $tagName => $tag)
		  {
		        if (!$tag['enabled'])
				unset ($this->_tags["$tagName"]);
		  }
		   		
      }
	  
	  public function parse_hidetags($message = '', $node_id = 0, $thread_id = 0, $user_id = 0, $post_id = 0, $likeusers = null)
	  {
			$this->_post = array('node_id' => $node_id, 'thread_id' => $thread_id, 'user_id' => $user_id, 'post_id' => $post_id, 
			               'like_users' => isset($likeusers) ? @unserialize($likeusers) : '');
						   
			$this->_canbypass = $this->canByPassHideTags();
			
			// get visitor info
		    $this->_visitor = XenForo_Visitor::getInstance();
			
			if (count($this->_tags))
			{
			     foreach ($this->_tags AS $tagName => $tag)
				 {						 
				         if ($tag['withoutOptionCheck'])
						 {
						       $message = preg_replace_callback('/\\[' . $tagName . '\\](.*)\\[\/' . $tagName . '\\]/siU', 
							              $tag['callback'], $message);
						 }
						 
						 if (isset($tag['optionRegex']) AND !(isset($tag['hasOption']) AND !$tag['hasOption']))
						 {
						       $message = preg_replace_callback("/\[" . $tagName . "=(&quot;|\"|'|)" . $tag['optionRegex'] . "\\1\](.*)\[\/" . $tagName . "\]/siU", 
							              $tag['callback'], $message);
						 }
						 
				 }
			
			}
			
			return $message;
	  }
	  	  
	  public function renderHide(array $matches)
	  {  
	        if (XenForo_Application::get('options')->vfchh_bbcode_hide)
			{
			    $mapped_to = XenForo_Application::get('options')->vfchh_bbcode_hide_map;
				switch ($mapped_to)
				{
				     case 'hp':
					     $text = $this->renderHposts($matches, true);
						 break;
					 case 'hr':
                         $text = $this->renderHreply($matches, true);
						 break;		
					 case 'hrt':
                         $text = $this->renderHrt($matches, true);
						 break;		
					 case 'ht':
                         $text = $this->renderHthanks($matches, true);
						 break;
					 case 'stg':
                         $text = $this->renderShowToGroups($matches, true);					 
						 break;								 
				}
				
				return $text;
			}
	  }
	  
	  public function renderHreply(array $matches, $hidetag = false)
	  {
	        $text = $matches[1];
			if (trim($text) === '')
		    {
			    return $text;
		    }
			
			$title = $hidetag ? 'vfc_hh' : 'vfc_hr';
			
			if (!$this->_canbypass AND (($this->_post['thread_id']) AND !$this->hasRepliedToThisThread($this->_post['thread_id'], $this->_visitor['user_id'])))
			{
			      return $this->inBbCode(array(0, $this->_post['node_id']), $title, $text);	
			}							
		    else
		    {
		          return $this->inBbCode(array(1, $this->_post['node_id']), $title, $text);		
		    }
			
			return $text;		
	  }
	  
	  public function renderHthanks(array $matches, $hidetag = false)
	  {
	        $text = $matches[1];
			if (trim($text) === '')
		    {
			    return $text;
		    }
			
			$title = $hidetag ? 'vfc_hh' : 'vfc_ht';
			
			if (!$this->_canbypass AND !$this->hasLikedThisPost($this->_post['post_id'], $this->_visitor['user_id']))
			{
			      return $this->inBbCode(array(0, $this->_post['node_id']), $title, $text);	
			}							
		    else
		    {
		          return $this->inBbCode(array(1, $this->_post['node_id']), $title, $text);
		    }
			
			return $text;	
	  }
	  
	  public function renderHrt(array $matches, $hidetag = false)
	  {
	        $text = $matches[1];
			if (trim($text) === '')
		    {
			    return $text;
		    }
			
			$title = $hidetag ? 'vfc_hh' : 'vfc_hrt';
			
			if (!$this->_canbypass AND !$this->hasRepliedToThisThread($this->_post['thread_id'], $this->_visitor['user_id']) AND 
			 !$this->hasLikedThisPost($this->_post['post_id'], $this->_visitor['user_id']))
			{
			      return $this->inBbCode(array(0, $this->_post['node_id']), $title, $text);	
			}							
		    else
		    {
		          return $this->inBbCode(array(1, $this->_post['node_id']), $title, $text);
		    }
			
			return $text;	
	  }
	  
	  public function renderHposts(array $matches, $hidetag = false)
	  {	       	  		   
	       if (isset($matches[2], $matches[3]))
		   {
		        $option = trim($matches[2]);
				$text = $matches[3];
		   }
		   else
		   {
		        $text = $matches[1];
		   }
		   
           if (trim($text) === '')
		   {
			    return $text;
		   }
		   
		   if (isset($option))
		   { 
			   $post_r = intval($option);
			   if ($post_r <= 0)
			   {
				   $post_r = XenForo_Application::get('options')->vfchh_bbcode_hp_min_post;
			   }
		   }
		   else
		   {
			   $post_r = XenForo_Application::get('options')->vfchh_bbcode_hp_min_post;
		   }
		   
		   $title = $hidetag ? 'vfc_hh' : 'vfc_hp';
		   
		   if ($this->_visitor['message_count'] < $post_r AND !$this->_canbypass)
		   {
				return $this->inBbCode(array(0, $this->_post['node_id'], $post_r), $title, $text);		
		   }
		   else
		   {
		        return $this->inBbCode(array(1, $this->_post['node_id'], $post_r), $title, $text);
		   }
		   
		   return $text;
           		   
	  }
	  
	  public function renderShowToGroups(array $matches, $hidetag = false)
	  {
	       if (isset($matches[2], $matches[3]))
		   {
		        $option = trim($matches[2]);
				$text = $matches[3];
		   }
		   else
		   {
		        $text = $matches[1];
		   }
		   
           if (trim($text) === '')
		   {
			    return $text;
		   }
		   
		   $option = isset($option) ? explode(',', $option) : XenForo_Application::get('options')->vfchh_bbcode_stg_default_groups;
		   if (is_array($option) AND count($option))
		   {
		        foreach ($option AS $ugpid)
				{     
				      if ($this->_visitor->isMemberOf($ugpid))
					  {
					        $ismember = true;
					        break;
					  }
				}

				$title = $hidetag ? 'vfc_hh' : 'vfc_stg';
				$option = implode(';', $option);
                if (!$this->_canbypass AND !isset($ismember))			         
                      return $this->inBbCode(array(0, $this->_post['node_id'], $option), $title, $text);	
                else
				      return $this->inBbCode(array(1, $this->_post['node_id'], $option), $title, $text);                				
		   }
		   
	       return $text;
	  }
	  	  
	  private function inBbCode(array $args, $title, $text)
      {
	       if ($this->_html)		   
               return ('[' . $title . '="' . implode(',', $args) . '"]' . $text . '[/' . $title . ']');
           else
               return (($args[0] == 0) ? new XenForo_Phrase('vfchh_preview_hidden_content') : $text);	   
      }
	  
	  private function canByPassHideTags()
	  {
	       if (!is_array($this->_post))
	       return;
		   
		   // get visitor info
		   $this->_visitor = XenForo_Visitor::getInstance();
		   
		   if ($this->_post['user_id'] == $this->_visitor['user_id'] OR VfcodersHH_Model_Permission::$permission['bypass'] OR 
              $this->_visitor->hasNodePermission($this->_post['node_id'], 'editAnyPost') OR $this->_visitor->isSuperAdmin())
           {
		       return true;
           }

           return false;		   
	  }
	  
	  /**
	   * Checks whether user has replied to this thread or not
	   *
	   * @param integer $threadId
	   * @param integer $userId
	   *
	   * @return array|false
	   */
	  public function hasRepliedToThisThread($threadId = 0, $userId = 0, $cache = true)
	  {
		  if (isset($this->_repliedthreads[$userId][$threadId]) AND $cache)
		  return $this->_repliedthreads[$userId][$threadId];
		 
		  $this->_repliedthreads[$userId][$threadId] = is_array($this->_getDb()->fetchRow('
			SELECT post.post_id
			FROM xf_post AS post
			WHERE user_id = ? AND thread_id = ? AND message_state = \'visible\'
		  ', array($userId, $threadId))) ? true : false;
		
		  return $this->_repliedthreads[$userId][$threadId];
	  }
	  
	  
	  /**
	   * Checks whether visitor has liked this post or not
	   *
	   * @param integer $postId
	   * @param integer $userId
	   *
	   * @return array|false
	   */
	  public function hasLikedThisPost($postId = 0, $userId = 0, $cache = true)
	  {
		  if (isset($this->_likedpost[$userId][$postId]) AND $cache)
		  return $this->_likedpost[$userId][$postId];
		 
		  if (is_array($this->_post['like_users']) AND count($this->_post['like_users']))
		  {
		       foreach ($this->_post['like_users'] AS $user)
			   {
			        if ($userId == $user['user_id'])
					return ($this->_likedpost[$userId][$postId] = true);
			   }
		  }
		  elseif ($this->_post['like_users'] == '')
		  {
		       $this->_likedpost[$userId][$postId] = is_array($this->_getDb()->fetchRow('
			   SELECT like_id
			   FROM xf_liked_content
			   WHERE content_type = \'post\' AND like_user_id = ? AND content_id = ?
		       ', array($userId, $postId))) ? true : false;
			   return $this->_likedpost[$userId][$postId];
		  }
		  
		  return ($this->_likedpost[$userId][$postId] = false);
	  }
	  
	  /**
	   * Helper method to get the database object.
	   *
	   * @return Zend_Db_Adapter_Abstract
	   */
	  protected function _getDb()
	  {
		  if ($this->_db === null)
		  {
			  $this->_db = XenForo_Application::getDb();
		  }

		  return $this->_db;
	  }
	  
}