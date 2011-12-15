<?php
/* 
 * Manages user session variables
 *
 */
namespace application;

class UserSession
{
	public function SignIn($userInfo)
	{
		if( $userInfo )
		{
			foreach( $userInfo as $prop => $val )
			{
				$_SESSION[$prop] = $val;
			}
		}
		
		return $userInfo;
	}
    
    public function SetOpenID($id)
    {
        $_SESSION['openID'] = $id;
    }

    public function GetOpenID()
    {
        return isset($_SESSION['openID']) ? $_SESSION['openID'] : '';
    }
    
	public function UserID()
	{
		return isset($_SESSION['userID']) ? $_SESSION['userID'] : '';
	}
	
	public function UserLogin()
	{
		return isset($_SESSION['userLogin']) ? $_SESSION['userLogin'] : '';
	}
	
	public function SignOut()
	{
		session_destroy();
		$_SESSION = array();
	}
}