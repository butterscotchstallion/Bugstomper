<?php
/*
 * OpenIDUser - operations on the User model
 *
 */
namespace model;
class OpenIDUser extends Model
{
    /*
     * Adds information about an OpenID, such as
     * friendly name/email and provider
     *
     * @param array $userInfo -
     * array('userID'       => 1,
     *       'friendlyName' => 'Bill',
             'uri'          => 'http://google.com/some-token'
     * );
     */
    public function Add($userInfo)
    {
        $userID   = isset($userInfo['userID'])   ? $userInfo['userID']   : 0;
        $friendly = isset($userInfo['friendly']) ? $userInfo['friendly'] : '';
        $uri      = isset($userInfo['uri'])      ? $userInfo['uri']      : '';
        
        $q = 'INSERT INTO openid_account(user_id, 
                                         friendly_name, 
                                         uri)
              VALUES(:userID, 
                     :friendly, 
                     :uri)';
              
        $this->Save($q, array(':userID'   => $userID,
                              ':friendly' => $friendly,
                              ':uri'      => $uri));
    }
}