<?php
/*
 * UserController - handles all actions regarding users
 *
 */
class IssueController
{
	private $connection;
	
	public function __construct($connection)
	{
		$this->connection = $connection;
	}
    
    public function SignIn()
    {
        
        return array();
    }
}