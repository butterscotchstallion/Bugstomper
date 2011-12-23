<?php
/*
 * Settings Module - settings and thangs
 *
 */
namespace module;
use model\User as UserModel;

class SettingsModule extends BaseModule
{
    public function __construct($dependencies)
    {
        parent::__construct($dependencies);
        
        $routes = array();
        
        $checkSignInCallback = array($this, 'CheckSignIn');
        
        // Display Settings
        $routes['DisplaySettings'] = array('pattern'  => '#^/settings$#',
                                           'before'   => $checkSignInCallback,
                                           'callback' => array($this, 'DisplaySettings'));
                                           
        // Save settings
        $routes['SaveSettings'] = array('pattern'  => '#^/settings$#',
                                        'before'   => $checkSignInCallback,
                                        'method'   => 'POST',
                                        'callback' => array($this, 'SaveSettings'));
        
        $this->SetRoutes($routes);
    }
    
    /**
     * Save settings (POST)
     *
     */
    public function SaveSettings()
    {
        $settings    = isset($_POST['settings']) ? $_POST['settings'] : array();
        $displayName = isset($settings['displayName']) ? $settings['displayName'] : '';
        
        // Update
        $objUser     = new UserModel($this->GetConnection());
        $userID      = $this->GetUserSession()->UserID();
        $update      = $objUser->Update(array('display_name' => $displayName,
                                              'id'           => $userID));
                                              
        if( $update )
        {
            header('Location: /settings');
            die;
        }                      
        else
        {
            throw new \RuntimeException('Error updating settings!');
        }         
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