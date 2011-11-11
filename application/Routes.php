<?php
/*
 * Routes - configure mapping of URLs to resources
 *
 *
 */

$objRouter = new Router();	
$objRouter->SetDebug(true);

// User tracking
$objUserSession = new UserSession();
$userIdentity   = $objUserSession->UserID();
$userLogin 		= $objUserSession->UserLogin();

// Asset management
$objAsset = new Asset();
$objAsset->SetJSGroups(array('global'));

// Checks if signed in and sends them to the sign in page if not
// http://code.google.com/p/openid-selector/wiki/StayingLoggedInBetweenPages
$checkSignInCallback = function() use($userIdentity)
{
	if( $userIdentity )
	{
		// Auth using immediate mode
		try
		{
			$objOpenID 			  = new LightOpenID(BS_DOMAIN);
			$objOpenID->required  = array('namePerson/friendly', 'contact/email');
			$objOpenID->identity  = sprintf('https://www.google.com/accounts/o8/id?site-xrds?hd=%s', BS_DOMAIN);
			$objOpenID->returnUrl = sprintf('http://%s/user/setCredentials', BS_DOMAIN);
			$objOpenID->authUrl($userIdentity);
		}
		catch(Exception $e)
		{
			header('Location: /user/sign-in');
			die;
		}
	}
	else
	{
		header('Location: /user/sign-in');
		die;
	}
};

/*
 * INDEX
 *
 */
$indexCallback = function()
{
	echo '<a href="/issues">issues</a>';
};

$objRouter->AddRoute(array('pattern'  => '#^/$#',
						   'callback' => $indexCallback));


/*
 * Edit issue (GET)
 *
 */
$editGETPattern  = '#^/issues/(\d+)/(.*)/edit$#';
$editGETCallback = function() use($connection, 
								  $editGETPattern,
								  $userLogin,
								  $objAsset) 
{
	$objIssueController   = new IssueController($connection);
	$tplVars 			  = $objIssueController->Edit($editGETPattern);
	$tplVars['userLogin'] = $userLogin;
	$tplVars['objAsset']  = $objAsset;
	$tplVars['objAsset']  = $objAsset;
	
    $objTpl 	          = new Template();
    $tplVars['objTpl']    = $objTpl;
    
	View::Display(array('tpl' 	  => '../view/Issue/Issue.template.php',
						'tplVars' => $tplVars));
};

$objRouter->AddRoute(array('pattern'  => $editGETPattern,
						   'before'   => $checkSignInCallback,
						   'callback' => $editGETCallback)); 
						   
/*
 * Bugs - specific issue
 *
 */
$spMapPattern  		   = '#^/issues/(\d+)/(.*)$#';	 
$specificIssueCallback = function() use($connection, 
									    $spMapPattern,
										$userLogin,
										$objAsset) 
{
	// Get issue ID out of URI
	preg_match_all($spMapPattern, 
				   $_SERVER['REQUEST_URI'], 
				   $matches);
	
	// Get issue
	$objIssue  	 = new Issue($connection);
	$id		 	 = isset($matches[1][0]) ? intval($matches[1][0]) : 0;
	$issue     	 = $objIssue->GetIssueByID($id);

	// Change log
	$changelog     = $objIssue->GetIssueChangeLog($id);
	$objTpl 	   = new Template();
	$issueSeverity = $objIssue->GetSeverity();
	$issueStatus   = $objIssue->GetStatus();

	// Issue comments
	$objComment    = new Comment($connection);
	$issueComments = $objComment->GetComments($id); 
	
	View::Display(array('tpl'     => '../view/Issue/Issue.template.php',
                        'tplVars' => array('issueComments' => $issueComments,
                                           'readOnly'      => true,
                                           'issueStatus'   => $issueStatus,
                                           'issueSeverity' => $issueSeverity,
                                           'objTpl'        => $objTpl,
                                           'issue'         => $issue,
                                           'objAsset'      => $objAsset)));
};

$objRouter->AddRoute(array('pattern'  => $spMapPattern,
						   'callback' => $specificIssueCallback)); 
						   
/*
 * Issue listing
 *
 */
$issuesCallback = function() use($connection,
								 $userLogin,
								 $objAsset) 
{
	$objIssue       = new Issue($connection);
	$statusList     = $objIssue->GetStatus();
	$issueSeverity  = $objIssue->GetSeverity();
	$query          = isset($_GET['q']) ? $_GET['q'] : '';
	$severityFilter = isset($_GET['sv']) ? intval($_GET['sv']) : 0;
	$statusFilters  = isset($_GET['s']) ? (array) $_GET['s'] : Util::FlattenObjArray($statusList, 'id');
	$assignedFilter = isset($_GET['a']) ? intval($_GET['a']) : '';
	
	// Get issues with filters
	$filters       = array('query'    => $query,
					       'status'   => $statusFilters,
						   'severity' => $severityFilter,
						   'assigned' => $assignedFilter);
	$issues        = $objIssue->GetIssues($filters);
	
	// List for assigned users
	$objUser     = new User($connection);
	$users		 = $objUser->GetUsers();
    
	View::Display(array('tpl'     => '../view/Issue/IssuesList.template.php',
                        'tplVars' => array('users'          => $users,
                                           'issues'         => $issues,
                                           'issueSeverity'  => $issueSeverity,
                                           'statusFilters'  => $statusFilters,
                                           'statusList'     => $statusList,
                                           'assignedFilter' => $assignedFilter,
                                           'query'          => $query,
                                           'objAsset'       => $objAsset)));
};

$objRouter->AddRoute(array('pattern'  => '#^/issues(.*)#',
						   'before'   => $checkSignInCallback,
						   'callback' => $issuesCallback)); 


/*
 * Bug changes (GET)
 *
 */
$changesPattern  = '#^/issues/(\d+)/(.*)/changes$#';
$changesCallback = function() use($connection,
								  $changesPattern,
								  $objAsset) 
{
	$readOnly    = true;

	// Get issue ID out of URI
	preg_match_all($changesPattern, 
				   $_SERVER['REQUEST_URI'], 
				   $matches);
	
	$objTpl 	 = new Template();
	$objIssue  	 = new Issue($connection);
	$id		 	 = isset($matches[1][0]) ? intval($matches[1][0]) : 0;
	$issue     	 = $objIssue->GetIssueByID($id);

	require '../view/Issue/IssueChanges.template.php';
};

$objRouter->AddRoute(array('pattern'  => $changesPattern,
						   'callback' => $changesCallback)); 
						   			   

/*
 * Add issue (POST)
 *
 */
$addBugCallback = function() use($connection) 
{
	$issue       = isset($_POST['issue']) ? (object) $_POST['issue'] : false;
	$issue->slug = isset($issue->title) && $issue->title ? Util::Slugify($issue->title) : '';
	
	$objIssue    = new Issue($connection);
	$newBugID  = $objIssue->Add($issue);
	
	if( $newBugID )
	{
		header('HTTP 1.1 201 Created');
		header(sprintf('Location: /issues/%d/%s', $newBugID, $slug));
		die;
	}
	else
	{
		header('Location: /issues/new');
		die;
	}
};

$objRouter->AddRoute(array('pattern'  => '#^/issues/new$#',
						   'method'   => 'POST',
						   'callback' => $addBugCallback)); 
						   
/*
 * Edit issue (POST)
 *
 */
$editBugCallback = function() use($connection,
								  $objAsset) 
{

	$objNew   = isset($_POST['issue']) ? (object) $_POST['issue'] : false;
	$objOld   = isset($_POST['old']) ? (object) $_POST['old'] : false;
	$return   = isset($_POST['returnTo']) ? $_POST['returnTo'] : '';
	
	$objIssue   = new Issue($connection);
	$newBugID = $objIssue->Update($objNew, $objOld);

	header('HTTP 1.1 202 Accepted');
	header(sprintf('Location: %s', $return));
	die;
};

$objRouter->AddRoute(array('pattern'  => '#^/issues/(\d+)/(.*)/edit$#',
						   'method'   => 'POST',
						   'before'   => $checkSignInCallback,
						   'callback' => $editBugCallback));

/*
 * Add issue (GET)
 *
 */
$newBugCallback = function() use($connection) 
{
	$objIssue   = new Issue($connection);
	$issueTypes = $objIssue->GetIssueTypes();
	
	require '../view/Issue/NewIssue.template.php';
};

$objRouter->AddRoute(array('pattern'  => '#^/issues/new$#',
						   'before'   => $checkSignInCallback,
						   'callback' => $newBugCallback)); 

/*
 * User profile page
 *
 */
$userProfilePattern  = '#^/user/(\d+)$#';
$userProfileCallback = function() use($connection,
									  $userProfilePattern,
									  $objRouter,
									  $userLogin,
									  $objAsset) 
{
	$userID  = $objRouter->GetIDFromURI($userProfilePattern);
	$objUser = new User($connection);
	$user    = $objUser->GetUserByID($userID);
	
	// User not found
	if( ! $user )
	{
		header('Location: /404');
		die;
	}
	
	// Get user profile information
	$objIssue 	    = new Issue($connection);
	$openedIssues   = $objIssue->GetIssuesOpenedByUser($userID);
	$assignedIssues = $objIssue->GetIssuesAssignedToUser($userID);
	
	View::Display(array('tpl'     => '../view/User/UserProfile.template.php',
                        'tplVars' => array('openedIssues'   => $openedIssues,
                                           'assignedIssues' => $assignedIssues,
                                           'user'           => $user,
                                           'objAsset'       => $objAsset)));
};

$objRouter->AddRoute(array('pattern'  => $userProfilePattern,
						   'callback' => $userProfileCallback)); 

/*
 * User sign in page (GET)
 *
 */
$userSignInCallback = function() use($objAsset)
{
    View::Display(array('tpl'     => '../view/User/UserSignIn.template.php',
                        'tplVars' => array('objAsset' => $objAsset)));
};

$objRouter->AddRoute(array('pattern'  => '#^/user/sign-in$#',
						   'callback' => $userSignInCallback)); 
						   
/*
 * User auth (GET)
 *
 */
$userAuthCallback = function()  
{
	try
	{
		$objOpenID = new LightOpenID(BS_DOMAIN);
		
		switch( $objOpenID->mode )
		{
			case 'cancel':
				header('Location: /user/authenticate/cancel');
				die;
			break;
			
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
		die($e->getMessage());
	}
};

$objRouter->AddRoute(array('pattern'  => '#^/user/authenticate/openid$#',
						   'callback' => $userAuthCallback)); 
						   
/*
 * User set credentials (GET)
 *
 */
$userSetCredsCallback = function()  
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
		$objUserSession = new UserSession();
		$objUserSession->SignIn(array('userID'    => $claimedID,
									  'userLogin' => $userLogin));
									  
		header('Location: /issues');
		die;
	}
	// Something went wrong
	else
	{
		header('Location: /user/sign-in');
		die;
	}
};

$objRouter->AddRoute(array('pattern'  => '#^/user/setCredentials(.*)$#',
						   'callback' => $userSetCredsCallback)); 
						   

/*
 * User auth cancel (GET)
 *
 */
$userAuthCancelCallback = function() use($objAsset) 
{
	require '../view/User/UserAuthCancel.template.php';
};

$objRouter->AddRoute(array('pattern'  => '#^/user/authenticate/cancel$#',
						   'callback' => $userAuthCancelCallback)); 
						   
/*
 * User sign out (GET)
 *
 */
$userSignOutCallback = function() use($objUserSession)  
{
	$objUserSession->SignOut();
	header('Location: /user/sign-in');
	die;
};

$objRouter->AddRoute(array('pattern'  => '#^/user/sign-out$#',
						   'callback' => $userSignOutCallback)); 


/*
 * Reports - Pretty graphs and stuff (GET)
 *
 */
 
// Issue status distribution
$issueStatusReportCallback = function() use($connection)
{
	$objIssue           = new IssueReport($connection);
	$statusDistribution = $objIssue->GetIssueStatusDistribution();

	header('Content-Type: application/json');
	echo $statusDistribution;
    die;
};

$objRouter->AddRoute(array('pattern' => '#^/pretty-graphs-and-stuff/issue-distribution$#',
						   'callback' => $issueStatusReportCallback,
						   'accept'   => 'application/json'));
			
// Assignee distribution			
$assigneeDistributionReportCallback = function() use($connection)
{
	$objIssue           = new IssueReport($connection);
	$assigneeDistribution = $objIssue->GetIssueAssigneeDistribution();
	
	header('Content-Type: application/json');
	echo $assigneeDistribution;
    die;
};

$objRouter->AddRoute(array('pattern' => '#^/pretty-graphs-and-stuff/assignee-distribution$#',
						   'callback' => $assigneeDistributionReportCallback,
						   'accept'   => 'application/json'));
						   
// Opener distribution			
$openerDistributionReportCallback = function() use($connection)
{
	$objIssue           = new IssueReport($connection);
	$openerDistribution = $objIssue->GetIssueOpenerDistribution();
	
	header('Content-Type: application/json');
	echo $openerDistribution;
    die;
};

$objRouter->AddRoute(array('pattern' => '#^/pretty-graphs-and-stuff/opener-distribution$#',
						   'callback' => $openerDistributionReportCallback,
						   'accept'   => 'application/json'));
						   

// Display reports
$reportCallback = function() use($userLogin,
								 $objAsset) 
{	
	$objAsset->AddJS('report');	
	View::Display(array('tpl' => '../view/Report/IssueStatus.template.php',
                        'tplVars' => array('objAsset'  => $objAsset,
                                           'userLogin' => $userLogin)));
};

$objRouter->AddRoute(array('pattern'  => '#^/pretty-graphs-and-stuff$#',
						   'callback' => $reportCallback)); 

/*
 * 403 (GET)
 *
 */
$error403Callback = function() use($objAsset, $userLogin) 
{
    View::Display(array('tpl' => '../view/Error/403.template.php',
                        'tplVars' => array('objAsset'  => $objAsset,
                                           'userLogin' => $userLogin)));
};

$objRouter->AddRoute(array('pattern'  => '#^/error/AccessDenied$#',
						   'callback' => $error403Callback)); 
						   
/*
 * 404 (GET)
 *
 */
$error404Callback = function() use($objAsset, $userLogin) {
	header('HTTP/1.0 404 Not Found');
    View::Display(array('tpl'     => '../view/Error/404.template.php',
                        'tplVars' => array('objAsset'  => $objAsset,
                                           'userLogin' => $userLogin)));
};

$objRouter->AddRoute(array('pattern'  => '#^/404$#',
						   'callback' => $error404Callback)); 
		   
/*
 * Dispatch routes
 *
 */
$objRouter->SetErrorHandler(404, $error404Callback);
$objRouter->Route();





