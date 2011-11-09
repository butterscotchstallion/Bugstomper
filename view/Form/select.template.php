<?php 
	if( $readOnly ):
		echo $value ? $value : $defaultText;
	else:
	?>
	<select<?php echo $attrString;?>>
		<?php if($defaultText):?>
			<option value=""><?php echo $defaultText;?></option>
		<?php
		endif;
		
		if( $options ):
			foreach( $options as $k => $o ):
				if( $textProperty && $valueProperty ):
					$value = isset($o->{$valueProperty}) ? $o->{$valueProperty} : '';
					$text = isset($o->{$textProperty}) ? $o->{$textProperty} : '';
				else:
					$value = $k;
					$text = $o;
				endif;
				
				if( isset($o->{$title}) ):
					$title = $o->{$title};
				endif;
				
				$optSelected = ($value == $selected) ? ' selected="selected"' : '';

				if( $text && $value ):
		?>
				<option value="<?php echo $value;?>"<?php echo $optSelected;?> 
						title="<?php echo $title;?>">
					<?php echo $text;?>
				</option>
		<?php
				endif;
			endforeach;
		endif;
		?>	
	</select>
	<?php
	endif;