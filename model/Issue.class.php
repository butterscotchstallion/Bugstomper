<?php 
/*
 * Issue - model for storing issues
 *
 */
namespace model;
class Issue extends Model
{
	protected $objOld;

	public function Update($objIssue, $objOld)
	{
		$assignedTo = isset($objIssue->assignedTo) ? $objIssue->assignedTo : null;
		
		$q    = 'UPDATE issue
				 SET title 		 = :title,
					 description = :description,
					 updated_at  = CURDATE(),
					 severity 	 = :severity,
					 status 	 = :status,
					 assigned_to = :assignedTo
			     WHERE 1=1
				 AND id 		 = :id';
		
		// Log changes
		$this->AddStatusChange($objOld);
		
		$params = $this->Object2ParamArray($objIssue);
		
		return $this->Save($q, $params);
	}
	
	public function Add($objIssue)
	{
		$q      = 'INSERT INTO issue(title, 
								     description, 
								     created_at, 
								     opened_by,
								     slug)
				   VALUES(:title, 
						  :description, 
						  CURDATE(), 
						  :openedBy,
						  :slug)';
				   
		$params = array(':title' 		=> $objIssue->title,
						':description'  => $objIssue->description,
						':openedBy' 	=> $objIssue->openedBy,
						':slug'			=> $objIssue->slug);
						
		return $this->Save($q, $params);
	}
	
	public function GetIssueByID($issueID)
	{
		$q    = "SELECT b.id,
						b.title,
						b.description,
						b.created_at AS createdAt,
						bs.id AS severityID,
						b.slug,
						bs.id AS severityID,
						bs.name AS severityName,
						s.id AS statusID,
						s.name AS statusName,
						s.description AS statusDescription,
						u.login AS openedByUserLogin,
						b.opened_by AS openedByUserID,
						COALESCE(ua.login, 'Unassigned') AS assignedToUserLogin,
						b.assigned_to AS assignedToUserID
				 FROM issue b
				 INNER JOIN issue_severity bs ON bs.id = b.severity
				 INNER JOIN issue_status s ON s.id = b.status
				 INNER JOIN user u ON u.id = b.opened_by
				 LEFT JOIN user ua ON b.assigned_to = ua.id
				 WHERE b.id = :issueID";
		
		$objIssue = $this->Fetch($q, array(':issueID' => intval($issueID)));
		
		if( $objIssue )
		{
			$objIssue->images = $this->GetIssueImages($issueID);
			return $objIssue;
		}
		
		return false;
	}
	
	public function GetIssueTypes()
	{
		$q = 'SELECT bt.id,
					 bt.name
			  FROM issue_type bt';
		return $this->FetchAll($q);
	}
	
	public function GetIssueImages($id)
	{
		$q = 'SELECT bi.id,
					 bi.filename,
					 bi.title,
					 bi.issue_id AS issueID
			  FROM issue_image bi
			  WHERE bi.id = :id';
		return $this->FetchAll($q, array(':id' => intval($id)));
	}
	
	public function GetSeverity()
	{
		$q = 'SELECT id,
					 name,
					 description
			  FROM issue_severity';
		return $this->FetchAll($q);
	}
	
	public function GetStatus()
	{
		$q = 'SELECT id,
					 name,
					 style_name AS styleName,
					 description
			  FROM issue_status
			  ORDER BY display_order';
		return $this->FetchAll($q);
	}
	
	public function GetIssues($filters = array())
	{
		$params 	    = array();
		
		// Search title/description
		$searchQuery    = isset($filters['query']) ? urldecode($filters['query']) : '';
		$searchQuerySQL = '';
		
		if( $searchQuery )
		{
			$searchQuerySQL 		= sprintf('AND (b.title 		 LIKE :searchQuery 
											   OR   b.description    LIKE :searchQueryDesc)');
			$params[':searchQuery']     = sprintf('%%%s%%', $searchQuery); 
			$params[':searchQueryDesc'] = sprintf('%%%s%%', $searchQuery); 
		}
		
		// Status filter
		$status    = isset($filters['status']) ? array_map('intval', $filters['status']) : array();
		$statusSQL = '';
		if( $status )
		{
			$tmp  	   		   = array_map('intval', $status);
			$statusIDs 		   = implode(',', $tmp);
			$statusSQL 		   = sprintf('AND b.status IN(%s)', $statusIDs);
		}
		
		// Severity filter
		$severity    = isset($filters['severity']) ? intval($filters['severity']) : 0;
		$severitySQL = '';
		if( $severity )
		{
			$severitySQL 		 = 'AND b.severity = :severity';
			$params[':severity'] = $severity;
		}
		
		// Assigned filter
		$assigned    = isset($filters['assigned']) ? intval($filters['assigned']) : 0;
		$assignedSQL = '';
		if( $assigned > 0 || $assigned === -1 )
		{
			$assignedSQL 		 = 'AND b.assigned_to = :assigned';
			$params[':assigned'] = $assigned;
			
			// Unassigned is set to -1
			if( $assigned === -1 )
			{
				$assignedSQL = 'AND b.assigned_to IS NULL';
				
				// Won't be needing this then
				unset($params[':assigned']);
			}
		}
		
		$params = array_filter($params);
		
		$q    = sprintf("SELECT b.id,
						 		b.title,
						 		b.slug,
						 		b.description,
								sv.name AS severity,
						 		b.created_at AS createdAt,
						 		u.login AS openedByUserLogin,
						 		bs.name AS statusName,
						 		bs.style_name AS statusStyleName,
								COALESCE(ua.login, 'Unassigned') AS assignedToUserLogin,
								b.assigned_to AS assignedToUserID
						 FROM issue b
						 INNER JOIN user 		    u ON b.opened_by   = u.id
						 INNER JOIN issue_status   bs ON b.status      = bs.id  
						 INNER JOIN issue_severity sv ON sv.id 		   = b.severity
						 LEFT JOIN  user 		   ua ON b.assigned_to = ua.id
						 WHERE 1=1 
						 %s
						 %s
						 %s
						 %s
						 ORDER BY b.created_at DESC, b.updated_at DESC",
						 $statusSQL,
						 $severitySQL,
						 $assignedSQL,
						 $searchQuerySQL);
						 
		return $this->FetchAll($q, $params);
	}
	
	public function GetIssueChangeLog($id)
	{
		$q    = "SELECT b.id,
						b.title,
						b.description,
						bs.id AS severityID,
						bs.id AS severityID,
						bs.name AS severityName,
						s.id AS statusID,
						s.name AS statusName,
						COALESCE(ua.login, 'Unassigned') AS assignedToUserLogin,
						b.assigned_to AS assignedToUserID
				 FROM issue_change_log b
				 INNER JOIN issue_severity bs ON bs.id = b.severity
				 INNER JOIN issue_status s ON s.id = b.status
				 LEFT JOIN user ua ON b.assigned_to = ua.id
				 WHERE b.id = :issueID";
				 
		return $this->FetchAll($q, array(':issueID' => intval($id)));
	}
	
	public function GetIssuesOpenedByUser($userID)
	{
		$q    = "SELECT i.title,
						i.id,
						i.slug
				 FROM issue i
				 WHERE i.opened_by = :userID
				 ORDER BY i.created_at DESC";
				 
		return $this->FetchAll($q, array(':userID' => intval($userID)));
	}
	
	public function GetIssuesAssignedToUser($userID)
	{
		$q    = "SELECT i.title,
						i.id,
						i.slug
				 FROM issue i
				 WHERE i.assigned_to = :userID
				 ORDER BY i.created_at DESC";
				 
		return $this->FetchAll($q, array(':userID' => intval($userID)));
	}
	
	public function AddStatusChange($objOld)
	{
		$q = 'INSERT INTO issue_change_log(title, 
										 description,
										 severity,
										 status,
										 assigned_to,
										 issue_id,
										 updated_at,
										 updated_by_user_id)
			  VALUES(:title, 
					 :description,
					 :severity,
					 :status,
					 :assignedTo,
					 :issueID,
					 CURDATE(),
					 :updatedByUserID)';
					 
		$params = $this->Object2ParamArray($objOld);

		return $this->Save($q, $params);
	}
}






