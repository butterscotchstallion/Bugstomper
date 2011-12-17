<?php 
	$this->DisplayHeader();
    $user           = $this->Get('user');
	$userLogin      = htmlentities($user->login);
    $assignedIssues = $this->Get('assignedIssues');
    $openedIssues   = $this->Get('openedIssues');
    $comments       = $this->Get('comments');
?>

<h1>User profile for <?php echo $userLogin;?></h1>
<ul class="userProfileList">
	<li>
		<strong>Created:</strong> 
		<abbr title="<?php echo $user->createdAt;?>"><?php echo $user->createdAt;?></abbr>
	</li>
</ul>
 
<?php 
// Assigned issues
if($assignedIssues):
?>
<h2><?php echo $userLogin;?> is assigned <?php echo count($assignedIssues);?> issues</h2>
<ul class="userProfileList">
	<?php foreach($assignedIssues as $k => $i):?>
		<li>
			<a href="/issues/<?php echo $i->id;?>/<?php echo $i->slug;?>"
			   title="View this issue"><?php echo $i->title;?></a>
		</li>
	<?php endforeach?>
</ul>
<?php endif;?>

<?php 
// Opened issues
if($openedIssues):
?>
<h2><?php echo $userLogin;?> has opened <?php echo count($openedIssues);?> issues</h2>
<ul class="userProfileList">
	<?php foreach($openedIssues as $k => $i):?>
		<li>
			<a href="/issues/<?php echo $i->id;?>/<?php echo $i->slug;?>"
			   title="View this issue"><?php echo $i->title;?></a>
		</li>
	<?php endforeach?>
</ul>
<?php endif;?>

<?php 
// Comments
if($comments):
?>
<h2><?php echo $userLogin;?> has commented on <?php echo count($comments);?> issues</h2>
<ul class="userProfileList">
	<?php foreach($comments as $issueID => $c):?>
		<li>
			<a href="/issues/<?php echo $c->issueID;?>/<?php echo $c->issueSlug;?>"
			   title="View this issue"><?php echo $c->issueTitle;?></a> (<?php echo $c->commentCount;?>)
		</li>
	<?php endforeach?>
</ul>
<?php endif;?>

<?php $this->DisplayFooter();?>
	