<?php
/*
 * BaseModule - base class for all modules
 *
 */
namespace module;
abstract class BaseModule 
{
    private $routes = array();
    private $view;
    private $connection;
    private $UserSession;
    private $httpResponse;
    
    /*
     * Used as a before filter to limit access
     * to certain routes
     *
     * Available to all modules
     *
     */
    public function CheckSignIn()
    {
        $userIdentity = $this->GetUserID();
        
        if( ! $userIdentity )
        {
            header('Location: /user/sign-in');
            die;
        }
        
        return true;
    }
    
    /*
     * Handles all get/set operations
     *
     */
    public function __call($name, $value)
    {
        $getOrSet = strtolower(substr($name, 0, 3));
        $property = substr($name, 3);
        $propVal  = isset($value[0]) ? $value[0] : ''; 
        
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
                
                case 'HTTPResponse':
                    return $this->httpResponse;
                    
                // Returns current user identity
                case 'UserID':
                    return $this->GetUserSession()->UserID();
                
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
                    $this->connection  = $propVal;
                    return true;
                
                case 'View':
                    $this->view        = $propVal;
                    return true;
                
                case 'UserSession':
                    $this->UserSession = $propVal;
                    return true;
                
                case 'Routes':
                    $this->routes      = $propVal;
                    return true;
                    
                case 'HTTPResponse':
                    $this->httpResponse = $propVal;
                    return true;
            }
        }
        
        throw new \BadMethodCallException(sprintf('Method %s not implemented on %s',
                                                   $name,
                                                   __CLASS__));
    }
}