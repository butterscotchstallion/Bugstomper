<?php
/*
 * Comment - issue comments
 *
 */
namespace model;
class Comment extends Model
{	
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
    
    /**
     * Adds a comment
     * @param object $objComment
     *
     * text    - comment text
     * userID  - author of comment
     * issueID - issue to add a commment on
     *
     */
    public function Add($objComment)
    {
        $q = 'INSERT INTO issue_comment(issue_id,
                                        text,
                                        created_at,
                                        created_by)
              VALUES(:issueID,:text,CURDATE(),:userID)';
        return $this->Save($q, array(':issueID' => intval($objComment->issueID),
                                     ':text'    => $objComment->text,
                                     ':userID'  => intval($objComment->userID)));
    }
}