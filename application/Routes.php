<?php
/*
 * Routes - configure mapping of URLs to resources
 *
 */
use application\View                        as View;
use application\HTTPResponse                as HTTPResponse;
use application\UserSession                 as UserSession;
use application\Router                      as Router;
use module\ErrorHandler                     as ErrorHandler;

$objRouter       = new Router();	
$objUserSession  = new UserSession();
$objHTTPResponse = new HTTPResponse();
$userIdentity    = $objUserSession->UserID();
$userLogin 		 = $objUserSession->DisplayName();

$objView         = new View();
$objView->AddCSS('globalCSS');
$objView->AddJS('globalJS');

// Enabled Modules
$modules = array('UserModule', 
                 'IssueModule',
                 'PrettyGraphsModule',
                 'SettingsModule');
                 
/*
 * Each module returns a callback
 * and a corresponding route 
 *
 */
foreach( $modules as $key => $m )
{
    // Supply dependencies to module
    $moduleName = sprintf("module\\%s", $m);
    $objModule  = new $moduleName(array('Connection'   => $connection,
                                        'View'         => $objView,
                                        'HTTPResponse' => $objHTTPResponse,
                                        'Router'       => $objRouter,
                                        'UserSession'  => $objUserSession));
    
    // Template variables available to all modules
    $objView->Add('displayName', $userLogin);
    $objView->Add('userIdentity', $userIdentity);
    
    // Get routes from each module
    $routes     = $objModule->GetRoutes();
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
    // Set up error handler
    $objHandler = new ErrorHandler(array('View'         => $objView,
                                         'HTTPResponse' => $objHTTPResponse));
    
    // Load route
    $routeLoaded = $objRouter->Route();
    
    // No matching routes or callback is invalid
    if( ! $routeLoaded )
    {
        $objHandler->ErrorNotFound();
    }
}
catch( \RuntimeException $e )
{
    $objHandler->ErrorCritical($e);
}




