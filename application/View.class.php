<?php
/*
 * View - Handles displaying of given templates
 *
 */
namespace application;
class View
{
	private $tplVars = array();
	private $objTemplate;
	private $objAsset;
    
    public function __construct($objTemplate, $objAsset)
    {
        $this->objAsset    = $objAsset;
        $this->objTemplate = $objTemplate;
    }
    
	public function Display($settings = array())
	{
		$tpl 	 	   = isset($settings['tpl']) ? $settings['tpl'] : false;
		$this->tplVars = isset($settings['tplVars']) ? $settings['tplVars'] : array();
		
		if( $tpl )
		{
			$tplPath = sprintf('%s%s', TPL_DIR, $tpl);
			
			if( is_readable($tplPath) )
			{
				require $tplPath;
                return true;
			}
		}
		
		return false;
	}

	public function Get($key)
	{
		$tplVars = isset($this->tplVars[$key]) ? $this->tplVars[$key] : '';
		
		if( is_string($tplVars) )
		{
			return $this->Prepare($tplVars);
		}
		
		return $tplVars;
	}
	
	public function Prepare($input)
	{
		return htmlentities($input, ENT_COMPAT, 'UTF-8');
	}
}