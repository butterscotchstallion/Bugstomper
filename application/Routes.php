<?php
/*
 * Routes - configure mapping of URLs to resources
 *
 */
$objRouter = new application\Router();	
$objRouter->SetDebug(true);

// User tracking
$objUserSession = new application\UserSession();
$userIdentity   = $objUserSession->UserID();
$userLogin 		= $objUserSession->UserLogin();

// Asset management
$objAsset = new application\Asset();
$objAsset->SetJSGroups(array('global'));

$objTemplate = new application\Template();

// Pass necessary dependencies to View
$objView     = new application\View($objTemplate, $objAsset);

// Enabled Modules
$modules = array('module\\User', 'module\\Issue');

/*
 * Each module returns a callback
 * and a corresponding route 
 *
 */
$enabledModules = array();
foreach( $modules as $key => $m )
{
    // Supply dependencies to module
    $objModule = new $m();
    $objModule->SetView($objView);
    $objModule->SetConnection($connection);
    $enabledModules[] = $objModule;
    
    // Get routes from each module
    $routes = $objModule->GetRoutes();
    foreach( $routes as $k => $route )
    {
        $objRouter->AddRoute($route);
    }
}

/*
 * Dispatch routes
 *
 */
$objHandler = new module\ErrorHandler();
$objHandler->SetView($objView);
$error404Callback = function() use($objHandler) 
{
    $objHandler->ErrorNotFound();
};
$objRouter->SetErrorHandler(404, $error404Callback);
$objRouter->Route();





