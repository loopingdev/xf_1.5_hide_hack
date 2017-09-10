<?php

class VfcodersHH_ViewPublic_Helper_Editor {

	public static function setUpEditorOptions(XenForo_View $view, $formCtrlName, $message = '', array &$editorOptions = array(), &$showWysiwyg)
	{
		$editorOptions['json']['editorOptions']['plugins'][] = 'vfcHH';
	}
}