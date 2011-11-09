<?php
require '../view/Global/Header.template.php';

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
			?>
				<tr>
					<td class="issueTitleTD">
						<a href="/issues/<?php echo $b->id;?>/<?php echo $b->slug;?>"
						   title="<?php echo $tooltip;?>"><?php echo $title;?></a>
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