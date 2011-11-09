<?php require 'Header.template.php';?>

<h1>New Issue</h1>

<form method="post" action="">
	<input type="hidden" name="issue[openedBy]" value="1">
	
	<div class="formEntry" id="firstEntry">
		<label>
			Title
			<input type="text"
				   name="issue[title]" 
				   class="txt" 
				   placeholder="Enter a short summary of the request">
		</label>
	</div>
	
	<div class="formEntry">
		<label>
			Type
			<select name="issue[issue_type_id]" class="newBugSelect">
				<?php
				if( $issueTypes ):
					foreach( $issueTypes as $k => $t ):
					?>
					<option value="<?php echo $t->id;?>">
						<?php echo $t->name;?>
					</option>
					<?php
					endforeach;
				endif;
				?>
			</select>
		</label>
	</div>
	
	<div class="formEntry">
		<label>
			Description
			<textarea name="issue[description]"
					  id="issueDescription"
					  placeholder="Enter a description of the problem and reproduction steps"></textarea>
		</label>
	</div>
	
	<div class="formEntry">
		<input type="submit" value="Save">
	</div>	
</form>

<?php require 'Footer.template.php';?>