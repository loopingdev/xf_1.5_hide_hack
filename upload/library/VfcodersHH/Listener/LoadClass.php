<?php

class VfcodersHH_Listener_LoadClass
{
    public static function BbCode($class, array &$extend)
    {
        if ($class == 'XenForo_BbCode_Formatter_Base')
        {
            $extend[] = 'VfcodersHH_BbCode_Formatter_Base';
        }
		
		if ($class == 'XenForo_BbCode_Formatter_Text')
		{
		    $extend[] = 'VfcodersHH_BbCode_Formatter_Text';
		}
		
		if ($class == 'XenForo_BbCode_Formatter_BbCode_AutoLink')
		{
		    $extend[] = 'VfcodersHH_BbCode_Formatter_BbCode_AutoLink';
		}
		
		if ($class == ' XenForo_BbCode_Formatter_Wysiwyg')
		{
			$extend[] = 'VfcodersHH_BbCode_Formatter_Wysiwyg';
		}
	}	
	
	public static function Controller($class, array &$extend)
    {
	    $classes = array(
			'ControllerPublic_Editor',
			'ControllerPublic_Thread',
			'ControllerPublic_Forum',
			'ControllerPublic_Post',
			'ControllerPublic_Account',
			'ControllerPublic_Member',
		);
		
		foreach($classes AS $_class)
		{
		       if ($class == 'XenForo_' .$_class)
               {
                    $extend[] = 'VfcodersHH_' . $_class;
               }
		}        
    }
	
	public static function SearchData($class, array &$extend)
    {
	    $classes = array(
			'Search_DataHandler_Thread',
			'Search_DataHandler_Post'
		);
		
		foreach($classes AS $_class)
		{
		       if ($class == 'XenForo_' .$_class)
               {
                    $extend[] = 'VfcodersHH_' . $_class;
               }
		}
	}
	
	public static function Mail($class, array &$extend)
    {
        if ($class == 'XenForo_Mail')
        {
            $extend[] = 'VfcodersHH_Mail';
        }
	}
}