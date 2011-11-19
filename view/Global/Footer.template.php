</div>
    <footer>
		
    </footer>
  </div> 
   
  <?php 
	$jsGroups = $this->objAsset->GetJSGroups();
	if( $jsGroups ):
  ?>
	<script src="/min/index.php?g=<?php echo $jsGroups;?>"></script>
  <?php endif;?>
  
  <script>
	  $(document).ready(function() {
		$('abbr').timeago();
		$('.zebra tr:odd').addClass('tblStripe');
		
		// tooltip
		$('a[title]').qtip({
			position: {
				my: 'top center',
				at: 'bottom center',
				viewport: $(window)
			},
			style: {
			  classes: 'ui-tooltip-tipsy ui-tooltip-shadow'
		    }
		});
		
		// Info value TD hover
		$('.issueInfoValue').hover(function() {
			$(this).find('span,a').css('color', '#fff');
		},
		function() {
			$(this).find('span,a').css('color', '#000');
		});
		
		// Status change description swap
		$('#issueStatusSelect').click(function() {
			//var status;
		});
	  });
  </script>	
</body>
</html>