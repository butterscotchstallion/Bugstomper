<?php/* * LoaderTest - test functionality of class loader * */class LoaderTest extends PHPUnit_Framework_TestCase{		protected $backupGlobals = FALSE;	public function testLoad()	{		$request = 'GET /';				$this->objLoader->expects($this->any())						->method('Load')						->with($request)						->will($this->returnValue(true));				$actual = $this->objLoader->Load($request);				$this->assertTrue($actual);	}	public function setUp()	{		$map 			  = array('GET /' => array('name' => 'Index')							);									$this->objLoader  = $this->getMock('Loader', array(), $map);	}}