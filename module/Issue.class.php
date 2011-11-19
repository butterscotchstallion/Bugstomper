<?php
/*
 * Issue Module - handles controller actions for issues
 *
 *
 */
namespace module;
use model\User as UserModel;
use model\Issue as IssueModel;
use application\Util as Util;

class Issue extends Module implements iModule
{
    private $objIssue;
    
    public function __construct()
    {
        $routes   = array();
        
        // Display issues
        $routes[] = array('pattern'  => '#^/issues(.*)#',
                          'callback' => array($this, 'DisplayIssueList'));
                          
        $this->SetRoutes($routes);
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
