</div>
    <footer>
		
    </footer>
  </div> 
  
  <?php 
	$jsGroups = $this->GetJS();
	if( $jsGroups ):
  ?>
	<script src="/min/?g=<?php echo $jsGroups;?>"></script>
  <?php endif;?>
</body>
</html>