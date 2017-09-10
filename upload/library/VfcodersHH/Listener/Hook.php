<?php

class VfcodersHH_Listener_Hook
{	  
	  public static function add_buttons($hookName, &$contents, $hookParams, $template)
	  {			  
	       switch ($hookName)
		   {		       
				case 'editor':
				    if ($taglist = self::fetch_tag_list())
					{
					    $params = array('activetags' => $taglist, 'htmt' => XenForo_Application::get('options')->vfchh_bbcode_hide_map);
						$contents .= $template->create('vfchh_bbcode_js', $params);					
						$contents .= $template->addRequiredExternal('css', 'vfchh');
					}	
                    break;				   
                case 'thread_view_qr_before':
                    $contents .= $template->addRequiredExternal('js', 'js/vfchh/vfchh.js');
                    break;					
		   }		   
	  }
	  
	  protected static function getTagPermission($node_id = null)
	  {	        			
			if (class_exists('VfcodersHH_Model_Permission') AND !$node_id)
            {
		        $permissions = VfcodersHH_Model_Permission::$permission;
		    }
		   
		    if ($node_id)
			{
			    $nodePermissions = XenForo_Visitor::getInstance()->getNodePermissions($node_id);
				$options = XenForo_Application::getOptions();
			    $permissions = array();
			
			    $permissions['hide']    = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_hide') && 
		                                  $options->vfchh_bbcode_hide) ? true : false;
		        $permissions['hposts']  = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_hp') && 
		                                  $options->vfchh_bbcode_hp) ? true: false;
		        $permissions['hrt']     = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_hrt') && 
		                                  $options->vfchh_bbcode_hrt) ? true: false;
		        $permissions['hreply']  = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_hr') && 
		                                  $options->vfchh_bbcode_hr) ? true: false;
		        $permissions['hthanks'] = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_ht') && 
		                                  $options->vfchh_bbcode_ht) ? true: false;
				$permissions['stg']     = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_stg') && 
		                                  $options->vfchh_bbcode_stg) ? true: false;
		        $permissions['bypass'] = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_bypass')) ? true: false;
			}
			
            return (isset($permissions)) ? $permissions : false;	
	  }
	  
	  protected static function fetch_tag_list($node_id = null)
	  {
	        $permissions = self::getTagPermission($node_id);
	        if (is_array($permissions) AND count($permissions))
			{
			      $taglist = array();
				  foreach ($permissions AS $key => $value)
				  {
				       if ($value AND $key != 'bypass')
					   $taglist[] = "'$key'";
				  }
				  
				  return count($taglist) ? implode(',', $taglist) : false;
			}
			else
			{
			     return;
			}
	  }
}