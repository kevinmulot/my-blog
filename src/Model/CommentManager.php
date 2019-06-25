<?php
namespace Model;

/**
 * Class CommentManager
 * @package Model
 */
class CommentManager extends Manager
{
    /**
     * @param $id
     * @return array
     */
    public function getComments($id)
    {
        $db = $this->connectDB();
        $req = $db->prepare("SELECT * FROM comments WHERE posts_id = ? ");
        $req->execute(array($id));

        return $req->fetchAll();
    }
}
