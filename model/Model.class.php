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
		$objChange = new \StdClass();
		
		foreach( $objOld as $k => $o )
		{
			if( isset($objNew->{$k}) && $objNew->{$k} != $o )
			{
				$objChange->{$k} = $o;
			}
		}
		
		return $objChange;
	}
	
    /**
     * BuildUpdateQuery - Generate assignment 
     * statement and token based on the property 
     * names of the object supplied
     *
     * @param array $props - properties to update
     *
     */
    protected function BuildUpdateQuery($properties)
    {
        $query = '';
        
        if( $properties )
        {
            foreach( $properties as $prop => $val )
            {
                $query .= sprintf(' %s = :%s, ', $prop, $prop);
            }
            
            $query = rtrim($query,', ');
        }
        
        return $query;
    }
    
    /*
     * Builds a parameter array based on input
     * @param array $properties - key/value pairs 
     * @return array - parameters
     *
     */
	protected function BuildParams($properties)
	{
        $params = array();
        
		if( $properties )
		{
			foreach( $properties as $k => $o )
			{
				$key 		  = sprintf(':%s', $k);
				$params[$key] = $o;
			}
		}
		
		return $params;
	}
	
	protected function FetchAll($query, $params = array())
	{
        $stmt = $this->Execute($query, $params);
        return $stmt->FetchAll();
	}
	
	protected function Fetch($query, $params = array())
	{
        $stmt = $this->Execute($query, $params);
        return $stmt->Fetch();
	}
	
    /**
     * Executes a SQL query with optional bound parameters
     * @param array $params - key/value pairs to bind as parameters
     *
     */
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
            var_dump($e);
			$this->HandleException($e);
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
			$this->HandleException($e);
		}
	}
	
	protected function IsInsertQuery($query)
	{
		return strpos(strtolower($query), 'insert') === 0;
	}
    
    protected function HandleException($e)
    {
        throw new \RuntimeException($e->getMessage());
    }
}