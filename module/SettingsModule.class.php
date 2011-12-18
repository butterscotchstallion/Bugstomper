<?php
/*
 * Settings Module - settings and thangs
 *
 */
namespace module;
use model\User as UserModel;

class SettingsModule extends BaseModule
{
    public function __construct()
    {
        $routes = array();
        
        $checkSignInCallback = array($this, 'CheckSignIn');
        
        // Display Settings
        $routes['DisplaySettings'] = array('pattern'  => '#^/settings$#',
                                           'before'   => $checkSignInCallback,
                                           'callback' => array($this, 'DisplaySettings'));
        
        $this->SetRoutes($routes);
    }
    
    /**
     * Display settings
     *
     *
     */
    public function DisplaySettings()
    {
        // Get user display name
        $objUser     = new UserModel($this->GetConnection());
        $userID      = $this->GetUserSession()->UserID();
        $user        = $objUser->GetUserByID($userID);
        $displayName = $user->displayName;
        
        //print_r($_SESSION);
        
        $this->GetView()->AddCSS('SettingsModuleCSS');
        $this->GetView()->Display(array('tpl'     => '../view/Settings/Settings.template.php',
                                        'tplVars' => array('userDisplayName' => $displayName)));
    }
}