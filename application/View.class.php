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
    private $rootTitle = 'Bugstomper';
    private $pageTitle = '';
    
    public function __construct($objTemplate, $objAsset)
    {
        $this->objAsset    = $objAsset;
        $this->objTemplate = $objTemplate;
    }
    
    public function SetPageTitle($title)
    {
        $this->pageTitle = sprintf('%s - %s', 
                                    $this->rootTitle,
                                    $title);
    }
    
    public function GetPageTitle()
    {
        return $this->pageTitle;
    }
    
    public function Add($key, $value)
    {
        $this->tplVars[$key] = $value;
    }
    
	public function Display($settings = array())
	{
		$tpl 	 	    = isset($settings['tpl']) ? $settings['tpl'] : false;
        // Used += here because we don't want to overwrite any variables
        // set before this method is called (such as user login!)
		$this->tplVars += isset($settings['tplVars']) ? $settings['tplVars'] : array();
		
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
    
    public function GetTemplate() 
    {
        return $this->objTemplate;
    }
    
    public function GetAsset() 
    {
        return $this->objAsset;
    }
}