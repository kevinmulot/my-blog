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
        $req = $db->prepare("SELECT * FROM comments WHERE posts_id = ? AND validation = 1");
        $req->execute(array($id));

        return $req->fetchAll();
    }

    /**
     * @return array
     */
    public function getAllComments()
    {
        $db = $this->connectDB();
        $req = $db->prepare("SELECT * FROM comments ORDER BY add_date DESC");
        $req->execute();

        return $req->fetchAll();
    }

    /**
     * @param $id
     */
    public function deleteComment($id)
    {
        $db = $this->connectDB();
        $req = $db->prepare("DELETE FROM comments WHERE posts_id = ? ");
        $req->execute(array($id));
    }

    /**
     * @param $id
     */
    public function validate($id)
    {
        $db = $this->connectDB();
        $req = $db->prepare("UPDATE comments SET validation = 1 WHERE id = ? ");
        $req->execute(array($id));
    }
}
