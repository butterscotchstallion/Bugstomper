<?php
/*
 * User - operations on the User model
 *
 */
namespace model;
class User extends Model
{
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
	public function Update($properties)
	{
        $query  = 'UPDATE user SET ';
        $query .= $this->BuildUpdateQuery($properties);
        $query .= ' WHERE id = :userID ';
        
        $params      = array(':userID' => intval($properties['id']));
        $moreParams  = $this->BuildParams($properties);
        $params     += $moreParams;

		return $this->Save($query, $params);
	}
	
	public function Add($objUser)
	{
		$q    = 'INSERT INTO user(login, password, created_at)
			     VALUES(:login, :password, NOW())';
		return $this->Save($q, array(':login' 		=> $objUser->login,
									 ':password' 	=> $objUser->password));
	}
	
	public function GetUserByID($userID)
	{
		$q    = "SELECT u.login,
						u.created_at AS createdAt,
                        CASE WHEN LENGTH(u.display_name) > 0
                        THEN u.display_name
                        WHEN LENGTH(u.login) > 0
                        THEN u.login
                        END AS displayName
				 FROM user u
                 LEFT JOIN openid_account o ON o.user_id = u.id
				 WHERE u.id = :userID";
		return $this->Fetch($q, array(':userID' => $userID));
	}
	
	public function GetUsers()
	{
		$q    = 'SELECT u.id,
						u.login,
						u.created_at
				 FROM user u
				 ORDER BY login';
		return $this->FetchAll($q);
	}
	
	public function GetUserByOpenID($identity)
	{
		$q    = 'SELECT u.id,
                        u.login,
						u.created_at AS createdAt,
						o.friendly_name AS friendlyName
				 FROM user u
				 INNER JOIN openid_account o ON o.user_id = u.id
				 WHERE o.uri = :identity';
		return $this->Fetch($q, array(':identity' => $identity));
	}
}






