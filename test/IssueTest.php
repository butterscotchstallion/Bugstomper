<?php
/*
 * IssueTest - model for storing issues
 *
 */
class IssueTest extends PHPUnit_Framework_TestCase
{	
	protected $backupGlobals = FALSE;
	
	public function testUpdate()
	{
		$this->connection->expects($this->any())
						 ->method('prepare')
						 ->will($this->returnValue($this->stmt));
						
		$objBug = $this->getMock('Issue', array(), array($this->connection));
		
		$objExpectedIssue 			 = new StdClass();
		$objExpectedIssue->id          = 1;
		$objExpectedIssue->title       = 'IssueTest is issuegy!';
		$objExpectedIssue->description = 'I tried to run this test and it FAILED';
		$objExpectedIssue->userID      = 1;
		
		$objBug->expects($this->any())
			   ->method('Update')
			   ->with($objExpectedIssue)
			   ->will($this->returnValue(true));
		
		$this->assertTrue($objBug->Update($objExpectedIssue));
	}
	
	public function testAdd()
	{
		$this->connection->expects($this->any())
						 ->method('prepare')
						 ->will($this->returnValue($this->stmt));
		$this->connection->expects($this->any())
						 ->method('lastInsertId')
						 ->will($this->returnValue(1));
						
		$objBug = $this->getMock('Issue', array(), array($this->connection));
		
		$objExpectedIssue 			 = new StdClass();
		$objExpectedIssue->title       = 'IssueTest is issuegy!';
		$objExpectedIssue->description = 'I tried to run this test and it FAILED';
		$objExpectedIssue->userID      = 1;
		
		$objBug->expects($this->any())
			   ->method('Add')
			   ->with($objExpectedIssue)
			   ->will($this->returnValue(1));
		
		$this->assertSame(1, $objBug->Add($objExpectedIssue));
	}
	
	public function testGetBugByID()
	{
		$this->connection->expects($this->any())
						 ->method('prepare')
						 ->will($this->returnValue($this->stmt));

		$objBug = $this->getMock('Issue', array(), array($this->connection));
		
		$objExpectedIssue 			 = new StdClass();
		$objExpectedIssue->id       	 = 1;
		$objExpectedIssue->title       = 'Issue test is buggy!';
		$objExpectedIssue->description = 'I tried to run this test and it FAILED';
		$objExpectedIssue->userID      = 1;
		
		$objBug->expects($this->any())
			   ->method('GetIssueByID')
			   ->with(1)
			   ->will($this->returnValue($objExpectedIssue));
			   
		$this->assertSame($objExpectedIssue, $objBug->GetBugByID($objExpectedIssue->id));
	}
	
    public function testGetBugs()
	{
		$issue              = new StdClass();
		$issue->title       = 'BugTest is issuegy!';
		$issue->description = 'I tried to run this test and it FAILED';
		$issue->userID      = 1;
		$expectedBugs     = array($issue);
		
		$this->connection->expects($this->any())
						 ->method('prepare')
						 ->will($this->returnValue($this->stmt));
			
		$objBug = $this->getMock('Issue', array(), array($this->connection));
		
		$objBug->expects($this->any())
			   ->method('GetIssues')
			   ->will($this->returnValue($expectedBugs));
			   
		$this->assertSame($expectedBugs, $objBug->GetBugs());
	}
	
    public function setUp()
    {
		$this->connection = $this->getMock('MockPDO');
		$this->stmt 	  = $this->getMock('PDOStatement');
    }
}

