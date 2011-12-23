<?php
/*
 * ErrorHandler - handles errors
 *
 */
namespace module;

class ErrorHandler extends BaseModule
{
    public function __construct($dependencies)
    {
        parent::__construct($dependencies);
        
        $routes   = array();
        
        // 404
        //$routes[] = array('pattern'  => '#^/error/404$#',
        //                  'callback' => array($this, 'ErrorNotFound'));
                          
        $this->SetRoutes($routes);
    }
    
    // 404
    public function ErrorNotFound()
    {
        $this->GetHTTPResponse()->Send(404);
        $this->GetView()->Display(array('tpl' => '../view/Error/404.template.php'));
    }
    
    // 500
    public function ErrorCritical($exception)
    {
        // TODO: log exception
        
        $this->GetHTTPResponse()->Send(500);
        $this->GetView()->Display(array('tpl' => '../view/Error/500.template.php'));
    }
}

