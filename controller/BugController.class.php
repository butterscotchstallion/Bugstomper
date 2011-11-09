<?php
/*
 * BugController - handles all actions regarding bugs
 *
 */
class BugController implements Controller
{
	private $connection;
	
	public function __construct($connection)
	{
		$this->connection = $connection;
	}
	
	public function Dispatch()
	{
		
	}
}