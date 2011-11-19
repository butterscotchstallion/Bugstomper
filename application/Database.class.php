<?php
/* 
 * Simple rudimentary database access
 *
 */
namespace application;
use \PDO as PDO;
class Database 
{
	protected $exMsg = '';
	
	public function __construct()
	{
	
	}

	// @param string $dsn - dsn for connection
	// @param string $username
	// @param string $password
	public function GetConnection($dsn, $user, $password)
	{
		try
		{
			$connection = new \PDO($dsn, $user, $password, 
                          array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			return $connection;
		}
		catch(PDOException $e)
		{
			$this->exMsg = $e->getMessage();
			return false;
		}
	}
	
	protected function AddExceptionMsg($msg)
	{
		$this->exMsg = $msg;
	}
	
	public function GetExceptionMsg()
	{
		return $this->exMsg;
	}
}