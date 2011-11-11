<?php
/*
 * IssueController - handles all actions regarding issues
 *
 */
class IssueController
{
	private $connection;
	
	public function __construct($connection)
	{
		$this->connection = $connection;
	}
	
	public function Edit($editGETPattern)
	{
		// Edit mode on!
		$readOnly    = false;

		// Get issue ID out of URI
		preg_match_all($editGETPattern, 
					   $_SERVER['REQUEST_URI'], 
					   $matches);
	
		$objIssue  	 = new Issue($this->connection);
		$id		 	 = isset($matches[1][0]) ? intval($matches[1][0]) : 0;
		$issue     	 = $objIssue->GetIssueByID($id);
		
		$issueSeverity = $objIssue->GetSeverity();
		$issueStatus   = $objIssue->GetStatus();
		
		// List for assigned users
		$objUser     = new User($this->connection);
		$users		 = $objUser->GetUsers();

		// Issue comments
		$objComment    = new Comment($this->connection);
		$issueComments = $objComment->GetComments($id); 
		
		return array( 'issueComments' => $issueComments
				     ,'users' 		  => $users
				     ,'issue'		  => $issue
				     ,'issueSeverity' => $issueSeverity
				     ,'issueStatus'   => $issueStatus
				     ,'readOnly' 	  => $readOnly);
	}
}