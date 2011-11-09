<?php 
	if( $readOnly ):
		echo $value;
	else:
	?>
	<input type="<?php echo $type;?>" <?php echo $attrString;?>>
	<?php
	endif;