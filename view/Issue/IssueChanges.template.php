<?php 
	require 'Header.template.php';
	
	if( $issue ): 
		$title = Util::Esc($issue->title);
		$id    = $issue->id;
		$slug  = $issue->slug;
		$issuelink = sprintf('/issues/%d/%s', $id, $slug);
		$editLink = sprintf('%s/edit', $issuelink);
		$changelogLink = sprintf('%s/changes', $issuelink);
?>

<h1 class="issueTitle">
	<a href="<?php echo $issuelink;?>"
	   title="#<?php echo $issue->id;?> - <?php echo $title;?>">
		<?php echo $title;?>
	</a>
</h1>

<p id="issueInfoTopArea">
	#<?php echo $id;?> Opened <abbr title="<?php echo $issue->createdAt;?>"><?php echo $issue->createdAt;?></abbr>
	by
	
	<?php echo $issue->openedByUserLogin;?>
	&nbsp;&mdash;&nbsp;
	<?php if($readOnly):?>
		<a href="<?php echo $editLink;?>" title="Edit this issue">Edit</a>
	<?php else:?>
		<a href="<?php echo $issuelink;?>" title="Cancel editing">Cancel</a>
	<?php endif;?>
	
	&nbsp;
	&bull;
	&nbsp;
	
	<a href="<?php echo $changelogLink;?>" title="See changes made to this issue">Changes</a>
</p>
	
hmmm	
	
	
<?php 
	endif;
	require 'Footer.template.php';
?>
