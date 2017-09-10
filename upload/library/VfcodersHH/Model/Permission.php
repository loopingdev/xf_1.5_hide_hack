<?php

class VfcodersHH_Model_Permission extends XenForo_Model
{
    public static $permission = null;
 
	/**
	 * Permission for hide tags
	 *
	 * @param array $thread
	 * @param array $forum
	 * @param string $errorPhraseKey Returned phrase key for a specific error
	 * @param array|null $nodePermissions
	 * @param array|null $viewingUser
	 *
	 * @return array $permission
	 */
	public function getTagPermission($node_id = null, array $forum = null, &$errorPhraseKey = '', array $nodePermissions = null, array $viewingUser = null)
	{
		if (isset(self::$permission) AND !isset($nodePermissions))
		return self::$permission;
		
		$this->standardizeViewingUserReferenceForNode($node_id, $viewingUser, $nodePermissions);
		$options = XenForo_Application::getOptions();
		
		self::$permission['hide']    = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_hide') && 
		                                $options->vfchh_bbcode_hide) ? true : false;
		self::$permission['hposts']  = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_hp') && 
		                                $options->vfchh_bbcode_hp) ? true: false;
		self::$permission['hrt']     = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_hrt') && 
		                                $options->vfchh_bbcode_hrt) ? true: false;
		self::$permission['hreply']  = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_hr') && 
		                                $options->vfchh_bbcode_hr) ? true: false;
		self::$permission['hthanks'] = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_ht') && 
		                                $options->vfchh_bbcode_ht) ? true: false;
		self::$permission['stg']     = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_stg') &&
				                        $options->vfchh_bbcode_stg) ? true: false;		
		self::$permission['bypass']  = (XenForo_Permission::hasContentPermission($nodePermissions, 'vfchh_perm_bypass')) ? true: false;		
	    
        return self::$permission;
	}

    	
}