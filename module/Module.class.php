<?php
/*
 * Module - parent class for all modules
 *
 */
namespace module;
abstract class Module 
{
    protected $routes = array();
    private $view;
    private $connection;

    public function GetConnection()
    {
        return $this->connection;
    }
    
    public function SetView($objView)
    {
        $this->view = $objView;
    }
    
    public function SetUserSession($objUserSesison)
    {
        $this->UserSession = $objUserSesison;
    }
    
    public function GetUserSession()
    {
        return $this->UserSession;
    }
    
    public function SetConnection($connection)
    {
        $this->connection = $connection;
    }
    
    protected function SetRoutes($routes)
    {
        $this->routes = $routes;
    }
    
    public function GetRoutes()
    {
        return $this->routes;
    }
    
    protected function GetView()
    {
        return $this->view;
    }
}