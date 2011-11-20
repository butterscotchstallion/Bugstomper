<?php 
require '../view/Global/Header.template.php';

use application\View as View;
use application\Util as Util;
use application\Template as Template;

$issue = $this->Get('issue');
$readOnly = $this->Get('readOnly');

if( $issue ): 
	$title = Util::Esc($issue->title);
	$id    = $issue->id;
	$slug  = $issue->slug;
	$issuelink = sprintf('/issues/%d/%s', $id, $slug);
	$editLink = sprintf('%s/edit', $issuelink);
	$changelogLink = sprintf('%s/changes', $issuelink);
	
	if( ! $readOnly ):?>
		<form method="post" action="">
		<input type="hidden" name="issue[id]" value="<?php echo $id;?>">
		<input type="hidden" name="returnTo" value="<?php echo $issuelink;?>">
		
		<?php // Old issue ?>
		<input type="hidden" name="old[title]" value="<?php echo $title;?>">
		<input type="hidden" name="old[severity]" value="<?php echo $issue->severityID;?>">
		<input type="hidden" name="old[status]" value="<?php echo $issue->statusID;?>">
		<input type="hidden" name="old[assignedTo]" value="<?php echo $issue->assignedToUserID;?>">
		<input type="hidden" name="old[description]" value="<?php echo $issue->description;?>">
		<input type="hidden" name="old[issueID]" value="<?php echo $issue->id;?>">
		<input type="hidden" name="old[updatedByUserID]" value="1">
		
		<input id="editBugSaveButton" type="submit" value="Save">
	<?php endif;
	
	if( $readOnly ):
	?>
		<h1 class="issueTitle">
			<a href="<?php echo $issuelink;?>"
			   title="#<?php echo $issue->id;?> - <?php echo $title;?>">
				<?php echo $title;?>
			</a>
		</h1>
	<?php else:?>
		<?php echo $this->GetTemplate()->Input(array('type' 	   => 'text',
                                                     'name' => 'issue[title]',
                                                     'id' => 'editBugTitleBox',
                                                     'readOnly' => $readOnly,
                                                     'selected' => $issue->severityID,
                                                     'value'	   => $title));?>
	<?php endif;?>
	
	<p id="issueInfoTopArea">
		#<?php echo $id;?> Opened <abbr title="<?php echo $issue->createdAt;?>"><?php echo $issue->createdAt;?></abbr>
		by
		<a href="/user/<?php echo $issue->openedByUserID;?>"><?php echo $issue->openedByUserLogin;?></a>
		&nbsp;&mdash;&nbsp;
		<?php if($readOnly):?>
			<a href="<?php echo $editLink;?>" title="Edit this issue">Edit</a>
		<?php else:?>
			<a href="<?php echo $issuelink;?>" title="Cancel editing">Cancel</a>
		<?php endif;?>
		
		<?php
		/*
		&nbsp;
		&bull;
		&nbsp;
		
		<a href="<?php echo $changelogLink;?>" title="See changes made to this issue">Changes</a>
		*/
		?>
	</p>
	
	<section id="issueInfo">
		<table>					
		<tbody>
			<tr>
				<td class="issueInfoLbl">Severity</td>
				<td class="issueInfoValue" colspan="2">
					<?php echo $this->GetTemplate()->Input(array('type' 	   => 'select',
                                                                 'name' => 'issue[severity]',
                                                                 'readOnly' => $readOnly,
                                                                 'selected' => $issue->severityID,
                                                                 'textProperty' => 'name',
                                                                 'valueProperty' => 'id',
                                                                 'class'	=> array('issueSelect'),
                                                                 'value'	   => $issue->severityName,
                                                                 'options'  => self::Get('issueSeverity')));?>
				</td>
			</tr>
			
			<tr>
				<td class="issueInfoLbl">Status</td>
				<td class="issueInfoValue" colspan="2">
					<?php echo $this->GetTemplate()->Input(array('type' 	   => 'select',
                                                                 'name' => 'issue[status]',
                                                                 'id' => 'issueStatusSelect',
                                                                 'title' => 'description',
                                                                 'readOnly' => $readOnly,
                                                                 'textProperty' => 'name',
                                                                 'valueProperty' => 'id',
                                                                 'class'	=> array('issueSelect'),
                                                                 'selected' => $issue->statusID,
                                                                 'value'	   => $issue->statusName,
                                                                 'options'  => self::Get('issueStatus')));
					?>
											
					<span id="issueStatusDescription"><?php echo $issue->statusDescription;?></span>
				</td>
			</tr>
			
			
			<tr>
				<td class="issueInfoLbl">Assigned User</td>
				<td class="issueInfoValue" colspan="2">
					<?php 
					
					if( $readOnly ):
						if( $issue->assignedToUserID ):
					?>
						<a href="/user/<?php echo $issue->assignedToUserID;?>" title="View user profile">
							<?php echo $issue->assignedToUserLogin;?>
						</a>
					<?php
						else:
							?>Unassigned<?php
						endif;
					else:
						echo $this->GetTemplate()->Input(array('type' 	   => 'select',
                                                               'name' => 'issue[assignedTo]',
                                                               'readOnly' => $readOnly,
                                                               'defaultText' => 'Unassigned',
                                                               'textProperty' => 'login',
                                                               'valueProperty' => 'id',
                                                               'defaultText' => 'Unassigned',
                                                               'class'	=> array('issueSelect'),
                                                               'selected' => $issue->assignedToUserID,
                                                               'value'	   => $issue->assignedToUserID,
                                                               'options' => self::Get('users')));
					endif;
					?>
				</td>
			</tr>
			
			<tr>
				<td class="issueInfoLbl">Description</td>
				<td class="issueInfoValue" colspan="2" id="issueDetailsDescription">
					<?php echo $this->GetTemplate()->Input(array('type' 	   => 'textarea',
                                                                 'name' => 'issue[description]',
                                                                 'readOnly' => $readOnly,
                                                                 'class'    => array('issueDescriptionArea'),
                                                                 'value'	   => Util::Esc($issue->description)));?>
				</td>
			</tr>
			
			<?php if( $issue->images ): ?>
			<tr>
				<td class="issueInfoLbl">Images</td>
				<td class="issueInfoValue" colspan="2">
					<ul id="issueImagesList">
						<?php foreach( $issue->images as $k => $i ):?>
						<li>
							<a href="/issueImages/<?php echo $i->filename;?>"><img src="/issueImages/<?php echo $i->filename;?>"></a>
						</li>	
						<?php endforeach;?>
					</ul>
				</td>
			</tr>
			<?php endif;?>
			
			<tr>
				<td class="issueInfoLbl">Comments</td>
				<td class="issueInfoValue" colspan="2">
					<?php if( self::Get('issueComments') ):?>
						hi
					<?php else:?>
						No comments
					<?php endif;?>
				</td>
		</tbody>
		</table>
	</section>
	
	<?php if( ! $readOnly ):?>
		</form>
	<?php endif;?>
	
<?php else: ?>
	<h1>Issue Not Found!</h1>
	<p>Sorry, but we couldn't find that issue.</p>
<?php endif;

require '../view/Global/Footer.template.php';?>
	