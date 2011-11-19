<?php 
/*
 * Init and autoloading
 *
 */
error_reporting(-1);
ini_set('display_errors', 1);

define('APP_ROOT', __DIR__);
$paths 	   = array();
$paths[]   = sprintf('%s/model', APP_ROOT);
$paths[]   = sprintf('%s/application', APP_ROOT);
$paths[]   = sprintf('%s/module', APP_ROOT);
$paths[]   = sprintf('%s/controller', APP_ROOT);
$paths[]   = sprintf('%s/test', APP_ROOT);
$paths[]   = get_include_path();

set_include_path(implode(PATH_SEPARATOR, $paths));
spl_autoload_extensions('.class.php,.php,.interface.php');
spl_autoload_register('autoload');

function autoload($className)
{
    //$tmp = explode("\\", $className);
    //$cn  = is_array($tmp) ? end($tmp) : $tmp;
    $cn = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    
    //var_dump($cn);
    
	switch( $cn )
	{
		// ***********************************************************************
        // TODO: Add these paths to the above array to eliminate the need for this
		// switch statement
		// ***********************************************************************
        case 'iModule':
            require sprintf('%s/module/iModule.interface.php', 
							str_replace('application', '', __DIR__));
			return true;
        
		// Password hashing
		case 'PasswordHash':
			require sprintf('%s/application/thirdparty/phpass/PasswordHash.php', 
							str_replace('application', '', __DIR__));
			return true;
		
		// OpenID 
		case 'LightOpenID':
			require sprintf('%s/application/thirdparty/lightopenid/openid.php', 
							str_replace('application', '', __DIR__));
			return true;
			
		// Everything else
		default:
			require sprintf('%s/%s.class.php', APP_ROOT, $cn);
			return true;
	}
}



