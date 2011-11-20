<?php
/*
 * PrettyGraphsAndStuff - graphs issue data
 *
 */
namespace module;
use model\IssueReport as IssueReport;

class PrettyGraphsAndStuff extends Module implements iModule
{
    public function __construct()
    {
        $routes   = array();
    
        // JSON opener distribution
        $routes['OpenerDistribution'] = array('pattern'  => '#^/pretty-graphs-and-stuff/opener-distribution$#',
                                              'callback' => array($this, 'OpenerDistribution'),
                                              'accept'   => 'application/json');
                                                
        // JSON assignee distribution
        $routes['AssigneeDistribution'] = array('pattern'  => '#^/pretty-graphs-and-stuff/assignee-distribution$#',
                                                'callback' => array($this, 'AssigneeDistribution'),
                                                'accept'   => 'application/json');
                                              
        // JSON status distribution
        $routes['StatusDistribution'] = array('pattern'  => '#^/pretty-graphs-and-stuff/issue-distribution$#',
                                              'callback' => array($this, 'StatusDistribution'),
                                              'accept'   => 'application/json');
        
        // Index
        $routes['StatusIndex'] = array('pattern'  => '#^/pretty-graphs-and-stuff$#',
                                       'callback' => array($this, 'StatusIndex'));
                                  
        $this->SetRoutes($routes);
    }
    
    public function StatusIndex()
    {
        $this->GetView()->GetAsset()->AddJS('report');	
        return $this->GetView()->Display(array('tpl' => '../view/Report/IssueStatus.template.php'));
    }
    
    public function OpenerDistribution()
    {
        $objIssue           = new IssueReport($this->GetConnection());
        $openerDistribution = $objIssue->GetIssueOpenerDistribution();
        
        $this->DisplayGraphData($openerDistribution);
    }
    
    public function AssigneeDistribution()
    {
        $objIssue             = new IssueReport($this->GetConnection());
        $assigneeDistribution = $objIssue->GetIssueAssigneeDistribution();
        
        $this->DisplayGraphData($assigneeDistribution);
    }
    
    public function StatusDistribution()
    {
        $objIssue           = new IssueReport($this->GetConnection());
        $statusDistribution = $objIssue->GetIssueStatusDistribution();
    
        $this->DisplayGraphData($statusDistribution);
    }
    
    /*
     * DisplayGraphData - displays graph data, sends
     * JSON header and dies.
     * @param object $jsonResultSet - json_encoded result set
     * @return null
     *
     */
    public function DisplayGraphData($jsonResultSet)
    {
        header('Content-Type: application/json');
        echo $jsonResultSet;
        die;
    }
}