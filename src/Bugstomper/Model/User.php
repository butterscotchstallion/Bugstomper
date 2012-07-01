<?php
/*
 * User - operations on the User model
 *
 */
namespace Bugstomper\Model;

class User
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Updates user model based on properties
     * specified 
     *
     * @param array $properties - these columns will be 
     * updated
     *
     * required properties: id
     *
     */
	public function update($properties)
	{
        $query  = 'UPDATE user SET ';
        $query .= $this->BuildUpdateQuery($properties);
        $query .= ' WHERE id = :userID ';
        
        $params      = array(':userID' => intval($properties['id']));
        $moreParams  = $this->BuildParams($properties);
        $params     += $moreParams;

		return $this->Save($query, $params);
	}
	
	public function add($objUser)
	{
		$q    = 'INSERT INTO user(login, password, created_at)
			     VALUES(:login, :password, NOW())';
		return $this->Save($q, array(':login' 		=> $objUser->login,
									 ':password' 	=> $objUser->password));
	}
	
	public function getUserByID($userID)
	{
		$q    = "SELECT u.login,
                        u.id,
						u.created_at AS createdAt,
                        CASE WHEN LENGTH(u.display_name) > 0
                        THEN u.display_name
                        WHEN LENGTH(u.login) > 0
                        THEN u.login
                        END AS displayName
				 FROM user u
                 LEFT JOIN openid_account o ON o.user_id = u.id
				 WHERE u.id = :userID";
                 
        $stmt = $this->db->prepare($q);
        $stmt->execute(array(':userID' => $userID));
        
		$user = $stmt->fetch();
        
        if ($user) {
            $user['assignedIssues'] = $this->getIssuesAssignedToUser($user['id']);
            $user['openedIssues']   = $this->getIssuesOpenedByUser($user['id']);
        }
        
        return $user;
	}
	
    public function getIssuesAssignedToUser($id)
    {
        $q = 'SELECT i.id,
                     i.title
              FROM issue i
              WHERE 1=1
              AND i.assigned_to = :id';
              
        $stmt = $this->db->prepare($q);
        $stmt->execute(array('id' => $id));
        $issues = $stmt->fetchAll();       
        
        return $issues ? $issues : array();
    }
    
    public function getIssuesOpenedByUser($id)
    {
        $q = 'SELECT i.id,
                     i.title
              FROM issue i
              WHERE 1=1
              AND i.opened_by = :id';
              
        $stmt = $this->db->prepare($q);
        $stmt->execute(array('id' => $id));
        $issues = $stmt->fetchAll();       
        
        return $issues ? $issues : array();
    }
    
	public function getUsers()
	{
		$q    = 'SELECT u.id,
						u.created_at,
                        u.login,
                        COALESCE(u.display_name, u.login) AS displayName
				 FROM user u
				 ORDER BY login';
                 
		$stmt = $this->db->prepare($q);
        $stmt->execute();
        $users = $stmt->fetchAll();       
        
        return $users ? $users : array();
	}
	
    /**
     * Gets user information based on the provided
     * openID identity
     * @param string $identity - openID identity string
     * @return object | false
     *  
     */
	public function getUserByOpenID($identity)
	{
		$q    = 'SELECT u.id,
                        u.login,
						u.created_at AS createdAt,
						o.friendly_name AS friendlyName,
                        CASE WHEN LENGTH(u.display_name) > 0
                        THEN u.display_name
                        WHEN LENGTH(u.login) > 0
                        THEN u.login
                        END AS displayName
				 FROM user u
				 INNER JOIN openid_account o ON o.user_id = u.id
				 WHERE o.uri = :identity';
		return $this->Fetch($q, array(':identity' => $identity));
	}
}






