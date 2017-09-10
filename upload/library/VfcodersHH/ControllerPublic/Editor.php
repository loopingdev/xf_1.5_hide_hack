<?php

class VfcodersHH_ControllerPublic_Editor extends XFCP_VfcodersHH_ControllerPublic_Editor
{
    public function actionDialog()
	{
	     $controllerResponse = parent::actionDialog();
		 if ($controllerResponse->templateName == 'editor_dialog_hide')
		 {
		       $hidetype = $this->_input->filterSingle('hidetype', XenForo_Input::STRING);
			   $tagtitle = $this->_input->filterSingle('tagtitle', XenForo_Input::STRING);
			   
			   if ($hidetype == 'hposts' OR ($hidetype == 'hide' AND XenForo_Application::get('options')->vfchh_bbcode_hide_map == 'hp'))
			   {
                     $controllerResponse->params['description'] = new XenForo_Phrase('vfchh_min_post_count_to_see_x_tag_content', 
			           array('tag' => $tagtitle));	
					 $controllerResponse->params['_input'] = 'post';
			   }
			   elseif ($hidetype == 'stg' OR ($hidetype == 'hide' AND XenForo_Application::get('options')->vfchh_bbcode_hide_map == 'stg'))
			   {
					 $controllerResponse->params['description'] = new XenForo_Phrase('vfchh_stg_description');
					 $controllerResponse->params['_input'] = 'stg';
					 
					 $userGroups = XenForo_Model::create('XenForo_Model_UserGroup')->getAllUserGroups();
					 foreach ($userGroups AS $userGroupId => $userGroup)
		             { 
			               $controllerResponse->params['usergroups'][$userGroupId] = array(
				                'label' => $userGroup['title'],
				                'value' => $userGroup['user_group_id'],
				                'selected' => in_array($userGroup['user_group_id'], 
								    XenForo_Application::get('options')->vfchh_bbcode_stg_default_groups) ? 'checked="checked"' : ''
			               );
		             }					 					 
			   }
			   
			   $controllerResponse->params['tag'] = $tagtitle;
		 }
		 
		 return $controllerResponse;
	}	
}