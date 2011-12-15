<?php
/*
 * Model - Parent object for all models
 *
 */
namespace model;
abstract class Model
{
	protected $connection;
	protected $oldObject;
	
	public function __construct($connection)
	{
		$this->connection = $connection;
	}
	
	// Used to compare against new object and generate
	// a change log
	public function SetOldObject($objOld)
	{	
		//echo sprintf('setting old object to: %s', var_export($objOld, true));
		$this->oldObject = $objOld;
	}
	
	/*
	 * @desc Creates an object with the changes to this object.
	 * Anything not changed will not be set
	 *
	 * @param  object $objOld    - old object
	 * @param  object $objNew    - new object
	 * @return object $objChange - object with changes
	 *
	 */
	protected function GenerateChangeLog($objOld, $objNew)
	{
		$objChange = new StdClass();
		
		foreach( $objOld as $k => $o )
		{
			if( isset($objNew->{$k}) && $objNew->{$k} != $o )
			{
				$objChange->{$k} = $o;
			}
		}
		
		return $objChange;
	}
	
	protected function Object2ParamArray($obj)
	{
		$tmp = get_object_vars($obj);
		
		if( $tmp )
		{
			$params = array();
			foreach( $tmp as $k => $o )
			{
				$key 		  = sprintf(':%s', $k);
				$params[$key] = $o;
			}
			
			return $params;
		}
		
		return false;
	}
	
	protected function FetchAll($query, $params = array())
	{
		try
		{
			$stmt = $this->Execute($query, $params);
			return $stmt->FetchAll();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}
	
	protected function Fetch($query, $params = array())
	{
		try
		{
			$stmt = $this->Execute($query, $params);
			return $stmt->Fetch();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}
	
	protected function Execute($query, $params = array())
	{
		try
		{
			$stmt 	 		= $this->connection->prepare($query);
			$success 		= $stmt->execute($params);
			return $success ? $stmt : false;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}
	
	protected function Save($query, $params = array())
	{
		try
		{
			$stmt 		  = $this->Execute($query, $params);
			$updateResult = $stmt || ($stmt && $stmt->rowCount() > 0);
			$insertResult = $this->connection->lastInsertId();
			
			return $this->IsInsertQuery($query) ? intval($insertResult) : $updateResult;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}
	
	protected function IsInsertQuery($query)
	{
		return strpos(strtolower($query), 'insert') === 0;
	}
}