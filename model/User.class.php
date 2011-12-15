<?php
/*
 * User - operations on the User model
 *
 */
namespace model;
class User extends Model
{
	public function Update($objUser)
	{
		$q    = 'UPDATE user
				 SET 	password 	 = :password
			     WHERE id 		 = :id';
		return $this->Save($q, array(':title' => $objUser->password,
									 ':id'	   => $objUser->id));
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
		$q    = 'SELECT u.login,
						u.created_at AS createdAt
				 FROM user u
				 WHERE u.id = :userID';
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






