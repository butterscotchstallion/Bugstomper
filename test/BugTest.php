<?php
/*
 * BugTest - model for storing defects
 *
 *
 */
class BugTest extends PHPUnit_Framework_TestCase
{	
	protected $backupGlobals = FALSE;
	
	public function testUpdate()
	{
		$this->connection->expects($this->any())
						 ->method('prepare')
						 ->will($this->returnValue($this->stmt));
						
		$objBug = $this->getMock('Bug', array(), array($this->connection));
		
		$objExpectedBug 			 = new StdClass();
		$objExpectedBug->id          = 1;
		$objExpectedBug->title       = 'BugTest is buggy!';
		$objExpectedBug->description = 'I tried to run this test and it FAILED';
		$objExpectedBug->userID      = 1;
		
		$objBug->expects($this->any())
			   ->method('Update')
			   ->with($objExpectedBug)
			   ->will($this->returnValue(true));
		
		$this->assertTrue($objBug->Update($objExpectedBug));
	}
	
	public function testAdd()
	{
		$this->connection->expects($this->any())
						 ->method('prepare')
						 ->will($this->returnValue($this->stmt));
		$this->connection->expects($this->any())
						 ->method('lastInsertId')
						 ->will($this->returnValue(1));
						
		$objBug = $this->getMock('Bug', array(), array($this->connection));
		
		$objExpectedBug 			 = new StdClass();
		$objExpectedBug->title       = 'BugTest is buggy!';
		$objExpectedBug->description = 'I tried to run this test and it FAILED';
		$objExpectedBug->userID      = 1;
		
		$objBug->expects($this->any())
			   ->method('Add')
			   ->with($objExpectedBug)
			   ->will($this->returnValue(1));
		
		$this->assertSame(1, $objBug->Add($objExpectedBug));
	}
	
	public function testGetBugByID()
	{
		$this->connection->expects($this->any())
						 ->method('prepare')
						 ->will($this->returnValue($this->stmt));

		$objBug = $this->getMock('Bug', array(), array($this->connection));
		
		$objExpectedBug 			 = new StdClass();
		$objExpectedBug->id       	 = 1;
		$objExpectedBug->title       = 'BugTest is buggy!';
		$objExpectedBug->description = 'I tried to run this test and it FAILED';
		$objExpectedBug->userID      = 1;
		
		$objBug->expects($this->any())
			   ->method('GetBugByID')
			   ->with(1)
			   ->will($this->returnValue($objExpectedBug));
			   
		$this->assertSame($objExpectedBug, $objBug->GetBugByID($objExpectedBug->id));
	}
	
    public function testGetBugs()
	{
		$bug              = new StdClass();
		$bug->title       = 'BugTest is buggy!';
		$bug->description = 'I tried to run this test and it FAILED';
		$bug->userID      = 1;
		$expectedBugs     = array($bug);
		
		$this->connection->expects($this->any())
						 ->method('prepare')
						 ->will($this->returnValue($this->stmt));
			
		$objBug = $this->getMock('Bug', array(), array($this->connection));
		
		$objBug->expects($this->any())
			   ->method('GetBugs')
			   ->will($this->returnValue($expectedBugs));
			   
		$this->assertSame($expectedBugs, $objBug->GetBugs());
	}
	
    public function setUp()
    {
		$this->connection = $this->getMock('MockPDO');
		$this->stmt 	  = $this->getMock('PDOStatement');
    }
}

