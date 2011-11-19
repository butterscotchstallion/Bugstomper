<?php
/*
 * ErrorHandler - handles errors such as 404
 *
 */
namespace module;
class ErrorHandler extends Module implements iModule
{
    protected $view;
    protected $routes;
    
    public function __construct()
    {
        $routes   = array();
        
        // Sign in
        $routes[] = array('pattern'  => '#^/error/404$#',
                          'callback' => array($this, 'ErrorNotFound'));
                          
        $this->SetRoutes($routes);
    }
    
    public function ErrorNotFound()
    {
        $tpl = array('tpl' => '../view/Error/404.template.php');
        
        return $this->GetView()->Display($tpl);
    }
}