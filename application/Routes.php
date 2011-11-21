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

// Provides various template functions, such
// as HTML generation
$objTemplate = new application\Template();

// Grouping related functionalities
$objView     = new application\View($objTemplate, $objAsset);

// Enabled Modules
$modules = array('User', 
                 'Issue',
                 'PrettyGraphsAndStuff');

/*
 * Each module returns a callback
 * and a corresponding route 
 *
 */
foreach( $modules as $key => $m )
{
    // Supply dependencies to module
    $moduleName = sprintf("module\\%s", $m);
    $objModule  = new $moduleName();
    $objModule->SetView($objView);
    $objModule->SetConnection($connection);
    $objModule->SetUserSession($objUserSession);
    
    // Template variables available to all routes
    $objView->Add('userLogin', $userLogin);
    $objView->Add('userIdentity', $userIdentity);
    
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
 
try
{
    // Load route
    $objRouter->Route();
}
catch( module\NotFoundException $e )
{
    // Set up error handler
    $objHandler = new module\ErrorHandler();
    $objHandler->SetView($objView);
    $objHandler->ErrorNotFound();
}





