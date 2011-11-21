<?php
/*
 * Issue Module - handles controller actions for issues
 *
 *
 */
namespace module;
use model\User as UserModel;
use model\Issue as IssueModel;
use model\Comment as CommentModel;
use application\Util as Util;
use application\Template as Template;

class Issue extends Module
{
    private $objIssue;
    
    public function __construct()
    {
        $routes   = array();
        
        // Edit issue
        $routes['EditIssue'] = array('pattern'  => '#^/issues/(\d+)/(.*)/edit$#',
                                     'method'   => 'POST',
                                     'callback' => array($this, 'EditIssue'));
                                     
        // Display edit issue
        $routes['DisplayEditIssue'] = array('pattern'  => '#^/issues/(\d+)/(.*)/edit$#',
                                            'callback' => array($this, 'DisplayEditIssue'));
                                            
        // Display specific issue
        $routes['DisplayIssue'] = array('pattern'  => '#^/issues/(\d+)/(.*)$#',
                                        'callback' => array($this, 'DisplayIssue'));
                         
        // Display issues
        $routes['DisplayIssueList'] = array('pattern'  => '#^/issues(.*)#',
                                            'callback' => array($this, 'DisplayIssueList'));
        
        $this->SetRoutes($routes);
    }
    
    public function EditIssue()
    {
        $objNew   = isset($_POST['issue']) ? (object) $_POST['issue'] : false;
        $objOld   = isset($_POST['old']) ? (object) $_POST['old'] : false;
        $return   = isset($_POST['returnTo']) ? $_POST['returnTo'] : '';
        
        $objIssue   = new IssueModel($this->GetConnection());
        $newBugID = $objIssue->Update($objNew, $objOld);

        header('HTTP 1.1 202 Accepted');
        header(sprintf('Location: %s', $return));
        die;
    }
    
    public function DisplayEditIssue()
    {
        $routes = $this->GetRoutes();
        
		// Get issue ID out of URI
		preg_match_all($routes['DisplayEditIssue']['pattern'], 
					   $_SERVER['REQUEST_URI'], 
					   $matches);

		$objIssue  	 = new IssueModel($this->GetConnection());
		$id		 	 = isset($matches[1][0]) ? intval($matches[1][0]) : 0;
		$issue     	 = $objIssue->GetIssueByID($id);

		$issueSeverity = $objIssue->GetSeverity();
		$issueStatus   = $objIssue->GetStatus();

		// List for assigned users
		$objUser     = new UserModel($this->GetConnection());
		$users		 = $objUser->GetUsers();

		// Issue comments
		$objComment    = new CommentModel($this->GetConnection());
		$issueComments = $objComment->GetComments($id); 

		$tplVars = array( 'issueComments' => $issueComments
                         ,'users' 		  => $users
                         ,'issue'		  => $issue
                         ,'issueSeverity' => $issueSeverity
                         ,'issueStatus'   => $issueStatus
                         ,'readOnly' 	  => false);

        return $this->GetView()->Display(array('tpl' 	 => '../view/Issue/Issue.template.php',
                                               'tplVars' => $tplVars));
    }
    
    public function DisplayIssue()
    {
        // Get issue ID out of URI
        $routes = $this->GetRoutes();
        
        preg_match_all($routes['DisplayIssue']['pattern'], 
                       $_SERVER['REQUEST_URI'], 
                       $matches);
 
        // Get issue
        $objIssue  	 = new IssueModel($this->GetConnection());
        $id		 	 = isset($matches[1][0]) ? intval($matches[1][0]) : 0;
        $issue     	 = $objIssue->GetIssueByID($id);

        // Change log
        $changelog     = $objIssue->GetIssueChangeLog($id);
        $objTpl 	   = new Template();
        $issueSeverity = $objIssue->GetSeverity();
        $issueStatus   = $objIssue->GetStatus();

        // Issue comments
        $objComment    = new CommentModel($this->GetConnection());
        $issueComments = $objComment->GetComments($id); 
        
        return $this->GetView()->Display(array('tpl'     => '../view/Issue/Issue.template.php',
                                               'tplVars' => array('issueComments' => $issueComments,
                                                                  'readOnly'      => true,
                                                                  'issueStatus'   => $issueStatus,
                                                                  'issueSeverity' => $issueSeverity,
                                                                  'objTpl'        => $objTpl,
                                                                  'issue'         => $issue)));
    }
    
    public function DisplayIssueList()
    {
        $objIssue       = new IssueModel($this->GetConnection());
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
        $objUser     = new UserModel($this->GetConnection());
        $users		 = $objUser->GetUsers();
        
        $this->GetView()->SetPageTitle('Issues');
        
        return $this->GetView()->Display(array('tpl'     => '../view/Issue/IssuesList.template.php',
                                               'tplVars' => array('users'          => $users,
                                                                  'issues'         => $issues,
                                                                  'issueSeverity'  => $issueSeverity,
                                                                  'statusFilters'  => $statusFilters,
                                                                  'statusList'     => $statusList,
                                                                  'assignedFilter' => $assignedFilter,
                                                                  'query'          => $query)));
    }
}
