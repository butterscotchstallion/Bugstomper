<?php
/*
 * Comment - issue comments
 *
 */
namespace model;
class Comment extends Model
{	
    /**
     * Gets number of comments for each issue
     *
     * @param int $author - user ID to filter on (optional) 
     *
     */
    public function GetCommentCountByIssueID($author = NULL)
	{
        // If an author is specified, then filter the comments
        // using that author
        $authorClause = '';
        $userJoin     = '';
        $params       = array();
        
        if( $author )
        {
            $authorClause = ' WHERE u.id = :author ';
            $userJoin     = ' INNER JOIN user u ON u.id = c.created_by ';
            $params       = array(':author' => intval($author));
        }
        
		$q = sprintf('SELECT c.issue_id AS issueID,
                             i.title AS issueTitle,
                             i.slug AS issueSlug,
                             COUNT(*)   AS commentCount
                      FROM  issue_comment c
                      INNER JOIN issue i ON i.id = c.issue_id
                      %s
                      %s
                      GROUP BY c.issue_id
                      ORDER BY c.created_at DESC',
                      $userJoin,
                      $authorClause);
              
		$tmp = $this->FetchAll($q, $params);
        
        // Organize comments by issue ID
        if( $tmp )
        {
            $comments = array();
            
            foreach( $tmp as $k => $c )
            {
                $comments[$c->issueID] = $c;
            }
            
            return $comments;
        }
        
        return array();
	}
    
    /**
     * Gets comments on an issue
     * @param int $issueID - the issue to get comments for
     *
     */
	public function GetComments($issueID)
	{
		$q = "SELECT c.comment_text AS text,
					 c.created_at AS createdAt,
					 c.updated_at AS updatedAt,
					 c.created_by AS createdBy,
					 CASE WHEN LENGTH(u.display_name) > 0
                     THEN u.display_name
                     WHEN LENGTH(u.login) > 0
                     THEN u.login
                     ELSE 'Unassigned'
                     END AS createdByLogin,
                     u.id AS createdByUserID
			  FROM  issue_comment c
			  INNER JOIN issue 	  i ON i.id = c.issue_id
              INNER JOIN user     u ON u.id = c.created_by
			  WHERE i.id = :issueID
              ORDER BY c.created_at DESC";
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
                                        comment_text,
                                        created_at,
                                        created_by)
              VALUES(:issueID,:text,NOW(),:userID)';
        return $this->Save($q, array(':issueID' => intval($objComment->issueID),
                                     ':text'    => $objComment->text,
                                     ':userID'  => intval($objComment->userID)));
    }
}