<?php 
    $this->DisplayHeader();
    $displayName = $this->Get('userDisplayName');
?>

<h1>Settings</h1>

<form method="post" action="">
    <label>
        Display Name (max 50 characters)        
        <?php echo $this->Input(array('type'  => 'text',
                                      'name'  => 'settings[displayName]',
                                      'id'    => 'settingsDisplayNameBox',
                                      'maxlength' => 50,
                                      'placeholder' => 'How your name is displayed to others',
                                      'value' => $displayName));?>
    </label>

    
    <input type="submit" id="saveSettingsButton" value="Save">
</form>

<?php $this->DisplayFooter();?>