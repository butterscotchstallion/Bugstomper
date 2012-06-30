<?php
/*
 * IssueReport - builds datasets that work
 * with Flot charts based on Issue data
 * 
 */
namespace model;
class IssueReport extends Model
{
    /**
     * Fetches comments and groups them by issue ID
     *
     */
    public function GetIssueCommentDistribution()
	{
		$q = "SELECT CONCAT('#', i.id) AS label,
					 COUNT(*) AS data
			  FROM issue i
			  JOIN issue_comment ic ON i.id = ic.issue_id
			  GROUP BY i.id";
		return $this->FetchAll($q);
	}
    
    /**
     * Fetches issues, grouped by status
     *
     */
	public function GetIssueStatusDistribution()
	{
		$q = 'SELECT st.name AS label,
					 COUNT(*) AS data
			  FROM issue i
			  JOIN issue_status st ON st.id = i.status
			  GROUP BY st.id';
		return $this->FetchAll($q);
	}
	
    /**
     * Fetches issues grouped by the user assigned to the issue
     *
     */
	public function GetIssueAssigneeDistribution()
	{
		$q = 'SELECT u.login AS label,
				     COUNT(*) AS data
			  FROM issue i
			  JOIN user u ON u.id = i.assigned_to
			  GROUP BY u.id';
		return $this->FetchAll($q);
	}
	
    /**
     * Fetches issues grouped by the user that opened it
     *
     */
	public function GetIssueOpenerDistribution()
	{
		$q = 'SELECT u.login AS label,
				     COUNT(*) AS data
			  FROM issue i
			  LEFT JOIN user u ON u.id = i.opened_by
			  GROUP BY u.id';
		return $this->FetchAll($q);
	}
}