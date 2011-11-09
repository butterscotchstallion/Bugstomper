<?php
/*
 * Template - handles view operations
 *
 *
 */
class Template
{
	public function Input($attributes)
	{
		$value      = isset($attributes['value']) ? $attributes['value'] : '';
		$type       = isset($attributes['type']) ? $attributes['type'] : '';
		$textProperty = isset($attributes['textProperty']) ? $attributes['textProperty'] : '';
		$valueProperty = isset($attributes['valueProperty']) ? $attributes['valueProperty'] : '';
		$readOnly   = isset($attributes['readOnly']) ? $attributes['readOnly'] : false;
		$options	= isset($attributes['options']) ? $attributes['options'] : array();
		$selected = isset($attributes['selected']) ? $attributes['selected'] : '';
		$class = isset($attributes['class']) ? $attributes['class'] : array();
		$defaultText = isset($attributes['defaultText']) ? $attributes['defaultText'] : '';
		$title = isset($attributes['title']) ? $attributes['title'] : '';
		
		// Build attribute string
		$attrString = $this->BuildAttributeString($attributes);
		
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
			$output 	 = '';
			$type = isset($attributes['type']) ? $attributes['type'] : 'text';
			
			// Some of these attributes are only for certain
			// tags: e.g. type for inputs but not for textarea
			$allowedKeys = array('type', 
								 'class', 
								 'name', 
								 'id',
								 'value');
			
			foreach( $attributes as $key => $value )
			{
				// Select tags don't need this
				if( $type == 'select' && ($key == 'type' || $key == 'value'))
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