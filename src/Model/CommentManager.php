<?php

namespace Model;

/**
 * Class CommentManager
 * @package Model
 */
class CommentManager extends Manager
{
    /**
     * @param $idy
     * @return array
     */
    public function getComments($idy)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("SELECT * FROM comments WHERE posts_id = ? AND validation = 1");
        $req->execute(array($idy));

        return $req->fetchAll();
    }

    /**
     * @return array
     */
    public function getAllComments()
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("SELECT * FROM comments ORDER BY add_date DESC");
        $req->execute();

        return $req->fetchAll();
    }

    /**
     * @param $idy
     */
    public function deleteComment($idy)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("DELETE FROM comments WHERE id = ? ");
        $req->execute(array($idy));
    }

    /**
     * @param $idy
     */
    public function deleteComments($idy)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("DELETE FROM comments WHERE posts_id = ? ");
        $req->execute(array($idy));
    }

    /**
     * @param $idy
     */
    public function validate($idy)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("UPDATE comments SET validation = 1 WHERE id = ? ");
        $req->execute(array($idy));
    }

    /**
     * @param $author
     * @param $content
     * @param $posts_id
     * @param $idy
     */
    public function addComment($author, $content, $posts_id, $idy)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("INSERT INTO comments (author, content, add_date, validation, posts_id, users_id) VALUES (?, ?, NOW(), '0', ?, ?)");
        $req->execute(array($author, $content, $posts_id, $idy));
    }
}
