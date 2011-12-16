<?php 
/*
 * Init and autoloading
 *
 */
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
    $cn = str_replace("\\", DIRECTORY_SEPARATOR, $className);

    $path = sprintf('%s/%s.class.php', APP_ROOT, $cn);

    if( is_readable($path) )
    {
        require $path;
    }
    
    return true;
}



