<?php
	$safeSearchQuery = Util::Esc($query);
?>

<section id="issueListingFilterBar">
	<form method="get" action="">
		<ul id="issueListingStatusList">
		<?php 
		foreach($statusList as $k => $s):
			$checked = in_array($s->id, $statusFilters) ? 'checked="checked"' : '';
		?>
			<li>
				<label>
				<input type="checkbox" 
					   name="s[]"
					   value="<?php echo $s->id;?>"
					   <?php echo $checked;?>>
				<?php echo $s->name;?>
				</label>
			</li>
		<?php endforeach;?>
		</ul>
		
		<select name="sv" id="issueFilterSeveritySelector">
			<option value="">Any severity</option>
			<?php 
			foreach($issueSeverity as $k => $s):
				$selected = $s->id == $severityFilter ? 'selected="selected"' : '';
			?>
				<option value="<?php echo $s->id;?>"
						<?php echo $selected;?>>
					<?php echo $s->name;?>
				</option>
			<?php 
			endforeach;
			?>
		</select>
		
		<select id="issueListingAssignedSelect" name="a">
			<option value="">Assigned to anyone</option>
			<option value="-1" <?php if($assignedFilter === -1):?>selected="selected"<?php endif;?>>Unassigned</option>
			<?php 
			foreach($users as $k => $u):
				$selected = $assignedFilter == $u->id ? 'selected="selected"' : '';
			?>
				<option value="<?php echo $u->id;?>"
						<?php echo $selected;?>>	
					<?php echo $u->login;?>
				</option>
			<?php 
			endforeach;
			?>
		</select>
		
		<input type="text" 
			   name="q"
			   id="issueListingSearchBox"
			   placeholder="Search"
			   autocomplete="off"
			   maxlength="140"
			   value="<?php echo $safeSearchQuery;?>">
		
		<input type="submit"	
			   value="GO">
		
	</form>
</section>