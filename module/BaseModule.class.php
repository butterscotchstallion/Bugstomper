<?php
/*
 * BaseModule - base class for all modules
 *
 */
namespace module;
abstract class BaseModule 
{
    private $routes       = array();
    private $dependencies = array();
    
    public function __construct($dependencies)
    {
        $this->dependencies = $dependencies;
    }
    
    /*
     * Used as a before filter to limit access
     * to certain routes
     *
     * Available to all modules
     *
     */
    public function CheckSignIn()
    {
        $userIdentity = $this->GetUserSession()->UserID();
        
        if( ! $userIdentity )
        {
            $this->GetHTTPResponse()->Redirect('/user/signIn');
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
       
        //print_r($this->dependencies);
        
        /*
         * GET
         *
         */
        if( $getOrSet == 'get' )
        {
            // Can't find that dependency
            if( ! isset($this->dependencies[$property]) )
            {
                throw new \InvalidArgumentException(sprintf('Non-existent dependency: %s', $property));
            }
            
            return $this->dependencies[$property];
        }
        /*
         * SET
         *
         */
        elseif( $getOrSet == 'set' )
        {
            $this->dependencies[$property] = $propVal;
            return true;
        }
    }
}