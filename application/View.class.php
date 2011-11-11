<?php
/*
 * View - Handles displaying of given templates
 *
 */
class View
{
	private static $tplVars = array();
	
	public static function Display($settings = array())
	{
		$tpl 	 	   = isset($settings['tpl']) ? $settings['tpl'] : false;
		self::$tplVars = isset($settings['tplVars']) ? $settings['tplVars'] : array();
		
		if( $tpl )
		{
			$tplPath = sprintf('%s%s', TPL_DIR, $tpl);
			
			if( is_readable($tplPath) )
			{
				require $tplPath;
			}
		}
		
		return false;
	}

	public static function Get($key)
	{
		$tplVars = isset(self::$tplVars[$key]) ? self::$tplVars[$key] : '';
		
		if( is_string($tplVars) )
		{
			return self::Prepare($tplVars);
		}
		
		return $tplVars;
	}
	
	public static function Prepare($input)
	{
		return htmlentities($input, ENT_COMPAT, 'UTF-8');
	}
}