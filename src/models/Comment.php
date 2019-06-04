<?php



/**
 * Class Comment
 * @package App
 */
class Comment extends Manager
{
    /**
     * @param $postId
     * @return mixed
     */
    public function getComments($postId)
    {
        $sql = 'SELECT * FROM comments Where post_id = ? AND validation = 1';
        $comments = $this->executeRequest($sql, array($postId));
        return $comments;
    }

    /**
     * @return mixed
     */
    public function getCommentlist()
    {
        $sql = 'SELECT * FROM comments ORDER BY post_id DESC';
        $comments = $this->executeRequest($sql);
        return $comments;
    }
}