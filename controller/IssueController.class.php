<?php
/*
 * IssueController - handles all actions regarding bugs
 *
 */
class IssueController implements Controller
{
	private $connection;
	
	public function __construct($connection)
	{
		$this->connection = $connection;
	}
	
	
}