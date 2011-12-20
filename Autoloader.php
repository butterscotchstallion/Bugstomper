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
    
    // I don't want to try and cram namespaces into a third party
    // package so this is my solution
    if( $cn == 'Logger' )
    {
        require sprintf('%s/application/thirdparty/log4php/Logger.php', APP_ROOT);
        return true;
    }
    
    $path = sprintf('%s/%s.class.php', APP_ROOT, $cn);

    if( is_readable($path) )
    {
        require $path;
    }
    
    return true;
}



