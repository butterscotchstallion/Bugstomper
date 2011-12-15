<?php
/*
 * ErrorHandler - handles errors such as 404
 *
 */
namespace module;

class ErrorHandler extends Module
{
    public function __construct()
    {
        $routes   = array();
        
        // Sign in
        $routes[] = array('pattern'  => '#^/error/404$#',
                          'callback' => array($this, 'ErrorNotFound'));
                          
        $this->SetRoutes($routes);
    }
    
    // 404
    public function ErrorNotFound()
    {
        $this->GetHTTPResponse()->Send(404);
        $this->GetView()->Display(array('tpl' => '../view/Error/404.template.php'));
    }
    
    // 500
    public function ErrorCritical()
    {
        $this->GetHTTPResponse()->Send(500);
        $this->GetView()->Display(array('tpl' => '../view/Error/500.template.php'));
    }
}

