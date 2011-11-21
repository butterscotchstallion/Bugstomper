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

    public function __call($name, $value)
    {
        $getOrSet = strtolower(substr($name, 0, 3));
        $property = substr($name, 3);
        
        //var_dump($property);
        //var_dump($getOrSet);
        //var_dump($value);
        
        /*
         * GET
         *
         */
        if( $getOrSet == 'get' )
        {
            switch($property)
            {
                case 'Connection':
                    return $this->connection;
                
                case 'View':
                    return $this->view;
                
                case 'UserSession':
                    return $this->UserSession;
                    
                case 'Routes':
                    return $this->routes;
            }
        }
        /*
         * SET
         *
         */
        elseif( $getOrSet == 'set' )
        {
            switch($property)
            {
                case 'Connection':
                    $this->connection = $value[0];
                    return true;
                
                case 'View':
                    $this->view = $value[0];
                    return true;
                
                case 'UserSession':
                    $this->UserSession = $value[0];
                    return true;
                    
                case 'Routes':
                    $this->routes = $value[0];
                    return true;
            }
        }
        
        throw new \BadMethodCallException(sprintf('Method %s not implemented on %s',
                                                   $name,
                                                   __CLASS__));
    }
}