<?php
/*
 * User - provides various user functionalities
 *
 */
namespace module;
use application\thirdparty\LightOpenID\LightOpenID as LightOpenID;
use application\UserSession                        as UserSession;
use model\User                                     as UserModel;
use model\Issue                                    as IssueModel;
use model\Comment                                  as CommentModel;
use model\OpenIDUser                               as OpenIDUserModel;

class UserModule extends BaseModule
{
    public function __construct()
    {
        $routes   = array();
        
        // Used to limit access to areas that require signed
        // in users
        $checkSignInCallback = array($this, 'CheckSignIn');
        
        // Account created successfully        
        $routes[] = array('pattern'  => '#^/user/account-successfully-created$#',
                          'callback' => array($this, 'AccountCreatedSuccessfully'));
        
        // New account (POST)
        $routes[] = array('pattern'  => '#^/user/new-account$#',
                          'method'   => 'POST',
                          'callback' => array($this, 'CreateNewAccount'));
        
        // New account (GET)
        $routes[] = array('pattern'  => '#^/user/new-account$#',
                          'callback' => array($this, 'DisplayNewAccount'));
                          
        // Sign in
        $routes[] = array('pattern'  => '#^/user/sign-in$#',
                          'callback' => array($this, 'DisplaySignIn'));
                          
        // User auth
        $routes[] = array('pattern'  => '#^/user/authenticate/openid$#',
                          'callback' => array($this, 'AuthenticateUser'));
                          
        // Set credentials    
        $routes[] = array('pattern' => '#^/user/setCredentials(.*)$#',
                          'callback' => array($this, 'SetCredentials'));
                          
        // Auth cancel           
        $routes[] = array('pattern' => '#^/user/authenticate/cancel$#',
                          'callback' => array($this, 'AuthCancel'));
                          
        // Sign out         
        $routes[] = array('pattern' => '#^/user/sign-out$#',
                          'callback' => array($this, 'SignOut'));
                          
        // Profile  
        $routes['DisplayProfile'] = array('pattern'  => '#^/user/(\d+)$#',
                                          'before'   => $checkSignInCallback,
                                          'callback' => array($this, 'DisplayProfile'));
                          
        $this->SetRoutes($routes);
    }
    
    /*
     * Displayed after creating a new account
     *
     */
    public function AccountCreatedSuccessfully()
    {
        $this->GetView()->Display(array('tpl' => '../view/User/AccountCreatedSuccessfully.template.php'));
    }
    
    /**
     * Display user profile
     *
     *
     */
    public function DisplayProfile()
    {
        $routes             = $this->GetRoutes();
        $userProfilePattern = $routes['DisplayProfile']['pattern'];

        preg_match_all($userProfilePattern, 
					   $_SERVER['REQUEST_URI'], 
					   $matches);
		$userID  = isset($matches[1][0]) ? $matches[1][0] : 0;
        $objUser = new UserModel($this->GetConnection());
        $user    = $objUser->GetUserByID($userID);
        
        if( $user )
        {
            // Get user profile information
            $objIssue 	    = new IssueModel($this->GetConnection());
            $openedIssues   = $objIssue->GetIssuesOpenedByUser($userID);
            $assignedIssues = $objIssue->GetIssuesAssignedToUser($userID);
            
            // Get comments for this user
            $objComment = new CommentModel($this->GetConnection());
            $comments = $objComment->GetCommentCountByIssueID($userID);
            
            $this->GetView()->Display(array('tpl'     => '../view/User/UserProfile.template.php',
                                            'tplVars' => array('openedIssues'   => $openedIssues,
                                                               'assignedIssues' => $assignedIssues,
                                                               'user'           => $user,
                                                               'comments'       => $comments)));
        }
        else
        {
            $this->GetView()->Display(array('tpl' => '../view/User/UserNotFound.template.php'));
        }        
    }
    
    public function SignOut()
    {
        $this->GetUserSession()->SignOut();
        header('Location: /user/sign-in');
        die;
    }
    
    public function AuthCancel()
    {
        $this->GetView()->Display(array('tpl' => '../view/User/UserAuthCancel.template.php'));
    }
    
    // This step occurs after the OpenID provider
    // asks the user for permission
    public function SetCredentials()
    {
        // Check if logged in
        $objOpenID    = new LightOpenID(BS_DOMAIN);
        $userSignedIn = $objOpenID->validate();
        $attr         = $objOpenID->getAttributes();
        $userLogin    = $userSignedIn ? $attr['contact/email'] : '';
        $identity     = isset($objOpenID->data['openid_identity']) ? $objOpenID->data['openid_identity'] : false; 
        $claimedID    = isset($objOpenID->data['openid_claimed_id']) ? $objOpenID->data['openid_claimed_id'] : false; 
        
        // Sign in successful
        if( $userSignedIn && $claimedID && $userLogin )
        {
            // See if a user is associated with this openID
            $objUser = new UserModel($this->GetConnection());
            $user    = $objUser->GetUserByOpenID($identity);
            
            // Store OpenID info
            $this->GetUserSession()->SetOpenID($identity);
            
            // User exists
            if( $user )
            {
                $this->GetUserSession()->SignIn(array('userID'    => $user->id,
                                                      'userLogin' => $userLogin,
                                                      'displayName' => $user->displayName));
                
                header('Location: /issues');
                die;
            }
            /*
             * - Can't find user
             * - Create new account
             *
             */
            else
            {
                header('Location: /user/new-account');
                die;
            }
        }
        // Something went wrong
        else
        {
            header('Location: /user/sign-in');
            die;
        }
    }
    
    /*
     * Displays sign in screen
     *
     */
    public function DisplaySignIn()
    {
        $this->GetView()->Display(array('tpl' => '../view/User/UserSignIn.template.php'));
    }
    
    /*
     * Authenticates using OpenID
     * 1. Auth with provider
     * 2. Return to setCredentials to set session info
     *
     */
    public function AuthenticateUser()
    {
        try
        {
            $objOpenID = new LightOpenID(BS_DOMAIN);
            
            switch( $objOpenID->mode )
            {
                // If user does not allow permission
                case 'cancel':
                    header('Location: /user/authenticate/cancel');
                    die;
                
                // Proceed!
                default:
                    $objOpenID->required  = array('namePerson/friendly', 'contact/email');
                    $objOpenID->identity  = sprintf('https://www.google.com/accounts/o8/id?site-xrds?hd=%s', BS_DOMAIN);
                    $objOpenID->returnUrl = sprintf('http://%s/user/setCredentials', BS_DOMAIN);
                    header(sprintf('Location: %s', $objOpenID->authUrl()));
                    die;
            }
        }
        catch(Exception $e) 
        {
            throw new \RuntimeException($e);
        }
    }
    
    // POST - submitting the form that creates a new account
    // using an OpenID
    public function CreateNewAccount()
    {
        $openIDURI = $this->GetUserSession()->GetOpenID();
        $friendly  = $this->GetUserSession()->UserLogin();
        
        // If user doesn't have an OpenID, then they didn't sign
        // in and don't belong here
        if( ! $openIDURI )
        {
            header('Location: /user/sign-in');
            die;
        }
        
        // Create base user account and then associate it with
        // the OpenID
        $objUser           = new UserModel($this->GetConnection());
        $objUser->login    = $friendly;
        $objUser->password = '';
        $userID            = $objUser->Add($objUser);
        
        // User created successfully
        if( $userID )
        {
            $objOpenIDUser = new OpenIDUserModel($this->GetConnection());
            $objOpenIDUser->Add(array('userID'   => $userID,
                                      'friendly' => $friendly,
                                      'uri'      => $openIDURI));
            
            // Update session with new account info            
            $this->GetUserSession()->SetUserID($userID);
            
            header('Location: /user/account-successfully-created');
            die;
        }
        
        // Error creating user
        throw new \RuntimeException('Error creating user');
    }
    
    // Create new account using openID credentials
    public function DisplayNewAccount()
    {
        $openID = $this->GetUserSession()->GetOpenID();
        
        // If user doesn't have an OpenID, then they didn't sign
        // in and don't belong here
        if( ! $openID )
        {
            header('Location: /user/sign-in');
            die;
        }
        
        $this->GetView()->Display(array('tpl'     => '../view/User/NewAccount.template.php',
                                        'tplVars' => array('openID' => $openID)));
    }
}