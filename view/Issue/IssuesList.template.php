<?php
use application\View as View;
use application\Util as Util;
require '../view/Global/Header.template.php';

$issues = View::Get('issues');

if( $issues ):
	?>
	<table id="issues" class="zebra">
		<thead>
			<tr>
				<th id="issueListTitleTH">Title</th>
				<th>Status</th>
				<th>Opened</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach( $issues as $k => $b ):
				// Issue title
				$title = Util::Esc($b->title);

				// Safe search query
				if($safeSearchQuery):
					$title = str_ireplace($safeSearchQuery, 
									     sprintf('<span class=&quot;highlight&quot;>%s</span>', $safeSearchQuery), 
										 $title);
				endif;
				
				// Issue link tooltip
				$assigned = $b->assignedToUserID ? sprintf('Assigned to %s', $b->assignedToUserLogin) : 'Unassigned';
				$tooltip  = sprintf("#%d - %s - &quot;%s&quot;<br><br>
							 		 %s", 
							  		 $b->id, 
									 $b->severity, 
									 $title,
									 $assigned);
                
                // Comments on this issue                
                $comments     = $this->Get('commentCounts');
                $commentCount = isset($comments[$b->id]) ? $comments[$b->id]->commentCount : 0;
                
                $id        = $b->id;
                $slug      = $b->slug;
                $issuelink = sprintf('/issues/%d/%s', $id, $slug);
                $editLink  = sprintf('%s/edit', $issuelink);
                $commentLink = sprintf('%s#comments', $editLink);
			?>
				<tr>
					<td class="issueTitleTD">
						<a href="<?php echo $issuelink;?>"
						   title="<?php echo $tooltip;?>"><?php echo $title;?></a>
                           
                        <p class="issueCommentCount">
                            <a href="<?php echo $commentLink;?>" 
                               title="View comments on this issue"
                               class="commentLink">
                                <?php echo $commentCount;?> comments
                            </a>
                        </p>
					</td>
					
					<td class="issueStatusTD">
						<span class="issueStatus <?php echo $b->statusStyleName;?>"><?php echo $b->statusName;?></span>
					</td>
					
					<td class="issueCreatedTD">
						<abbr title="<?php echo $b->createdAt;?>"><?php echo $b->createdAt;?> by <?php echo $b->openedByUserLogin;?></abbr>
					</td>
				</tr>
			<?php
			endforeach;
			?>
		</tbody>
	</table>
	<?php
else:
	if(isset($safeSearchQuery)):
	?>
		No issues found matching your query.
	<?php else:?>
		There are no issues. Must be a pretty awesome product!
	<?php
	endif;
endif;

require '../view/Global/Footer.template.php';