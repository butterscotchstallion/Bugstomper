<?php
/*
 * View - Handles displaying of given templates
 *
 */
namespace application;

class View
{
	private $tplVars   = array();
    private $rootTitle = 'Bugstomper';
    private $pageTitle = '';
    private $assets    = array();
    private $objUserSession;
    
    public function __construct()
    {
        $this->assets['js']  = array();
        $this->assets['css'] = array();
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
    
    /*
     * Add template variable
     *
     */
    public function Add($key, $value)
    {
        $this->tplVars[$key] = $value;
    }
    
    public function DisplayHeader()
    {
        require realpath('../view/Global/Header.template.php');
    }
    
    public function DisplayFooter()
    {
        require realpath('../view/Global/Footer.template.php');
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

    /*
     * Get template variable
     *
     */
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
    
    public function AddJS($groupName)
    {
        $this->assets['js'][] = $groupName;
    }
    
    public function AddCSS($groupName)
    {
        $this->assets['css'][] = $groupName;
    }
    
    public function GetJS()
	{
		return $this->assets['js'] ? implode(',', $this->assets['js']) : false;
	}
    
    public function GetCSS()
	{
		return $this->assets['css'] ? implode(',', $this->assets['css']) : false;
	}
    
    public function Input($attributes)
	{
		$value         = isset($attributes['value']) ? $attributes['value'] : '';
		$type          = isset($attributes['type']) ? $attributes['type'] : '';
		$textProperty  = isset($attributes['textProperty']) ? $attributes['textProperty'] : '';
		$valueProperty = isset($attributes['valueProperty']) ? $attributes['valueProperty'] : '';
		$readOnly      = isset($attributes['readOnly']) ? $attributes['readOnly'] : false;
		$options	   = isset($attributes['options']) ? $attributes['options'] : array();
		$selected      = isset($attributes['selected']) ? $attributes['selected'] : '';
		$class         = isset($attributes['class']) ? $attributes['class'] : array();
		$defaultText   = isset($attributes['defaultText']) ? $attributes['defaultText'] : '';
		$title         = isset($attributes['title']) ? $attributes['title'] : '';
		$placeholder   = isset($attributes['placeholder']) ? $attributes['placeholder'] : '';
		$maxlength     = isset($attributes['maxlength']) ? $attributes['maxlength'] : '';
		
		// Build attribute string
		$attrString    = $this->BuildAttributeString($attributes);
		
		switch( $type )
		{
			case 'select':
				include '../view/Form/select.template.php';
			break;
			
			case 'text':
				include '../view/Form/text.template.php';
			break;
			
			case 'textarea':
				include '../view/Form/textarea.template.php';
			break;
		}
	}
	
	public function BuildAttributeString($attributes)
	{
		if( $attributes )
		{
			$output = '';
			$type   = isset($attributes['type']) ? $attributes['type'] : 'text';
			
			// Some of these attributes are only for certain
			// tags: e.g. type for inputs but not for textarea
			$allowedKeys = array('type', 
								 'class', 
								 'name', 
								 'id',
								 'value',
                                 'placeholder',
                                 'maxlength');
			
			foreach( $attributes as $key => $value )
			{
				// Select tags don't need this
				if( $type == 'select' && ($key == 'type' || $key == 'value') )
				{
					continue;
				}
				
				if( $key == 'class' )
				{
					$value = implode(' ', $value);
				}
				
				if( ! in_array($key, $allowedKeys) )
				{
					continue;
				}
				
				$output .= sprintf(' %s="%s" ', $key, $value);
			}
			
			return $output;
		}
		
		return false;
	}
}