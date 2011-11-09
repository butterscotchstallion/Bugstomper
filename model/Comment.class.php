<?php
/*
 * Comment - model for comments on issues
 *
 */
class Comment extends Model
{
	public function __construct($connection)
	{
		parent::__construct($connection);
	}
	
	public function GetComments($issueID)
	{
		$q = 'SELECT c.text,
					 c.created_at AS createdAt,
					 c.updated_at AS updatedAt,
					 c.created_by AS createdBy,
					 o.friendly_name AS createdByLogin
			  FROM  issue_comment c
			  LEFT  JOIN openid_account o ON o.user_id = c.created_by
			  INNER JOIN issue 			i ON i.id 	   = c.issue_id
			  WHERE i.id = :issueID';
		return $this->FetchAll($q, array('issueID' => intval($issueID)));
	}
}