<?php

class VfcodersHH_Helper_Strip
{
       public static function strip_hidetags($string, $replacetext = '')
	   {
	         $orig_hidetags = VfcodersHH_Helper_Tags::$_originalhidetags;
			 $orig_hidetags = implode('|', $orig_hidetags);
			 
			 while ($string != ($newString = preg_replace('#\[(' . $orig_hidetags . ')(=[^\]]*)?\](.*)\[/\1\]#siU', $replacetext, $string)))
		     {
			      $string = $newString;
		     }

		     $string = str_replace('[*]', '', $string);

		     return $string;	   
	   }
}