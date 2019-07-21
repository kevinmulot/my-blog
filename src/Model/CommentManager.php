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
    public function getComments(int $idy)
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
        $req = $dtb->prepare("SELECT * FROM comments ORDER BY validation");
        $req->execute();

        return $req->fetchAll();
    }

    /**
     * @param $idy
     * @param $userId
     * @return array|bool
     */
    public function getWaitingComments(int $idy, int $userId)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("SELECT * FROM comments WHERE posts_id = ? AND users_id = ? AND validation = 0 ORDER BY add_date");
        if ($req->execute(array($idy, $userId))) {
            return $req->fetchAll();
        }
        return false;
    }

    /**
     * @param int $idy
     * @param $row
     */
    public function deleteComment(int $idy, $row)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("DELETE FROM comments WHERE $row = ? ");
        $req->execute(array($idy));
    }

    /**
     * @param $idy
     */
    public function validate(int $idy)
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
    public function addComment(string $author, string $content, int $posts_id, int $idy)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("INSERT INTO comments (author, content, add_date, validation, posts_id, users_id) VALUES (?, ?, NOW(), '0', ?, ?)");
        $req->execute(array($author, $content, $posts_id, $idy));
    }
}
