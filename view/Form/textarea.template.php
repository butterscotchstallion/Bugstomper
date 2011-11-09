
<?php 
if($readOnly):
	echo $value;
else:
?> 
	<textarea<?php echo $attrString;?>><?php echo $value;?></textarea>
<?php endif;?>