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
    
    public function ErrorNotFound()
    {
        var_dump($this->GetView());
        return $this->GetView()->Display(array('tpl' => '../view/Error/404.template.php'));
    }
}

