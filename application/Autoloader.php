<?php 
/*
 * Init and autoloading
 *
 */
error_reporting(-1);
ini_set('display_errors', 1);

$root	   = str_replace('application', '', __DIR__);
$paths 	   = array();
$paths[]   = sprintf('%smodel', $root);
$paths[]   = sprintf('%sapplication', $root);
$paths[]   = sprintf('%stest', $root);
$paths[]   = get_include_path();

function autoload($className)
{
	switch( $className )
	{
		// TODO: Add these paths to the above array to eliminate the need for this
		// switch statement
		
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
			require sprintf('%s.class.php', $className);
			return true;
	}
}

set_include_path(implode(PATH_SEPARATOR, $paths));
spl_autoload_extensions('.class.php,.php');
spl_autoload_register('autoload');


