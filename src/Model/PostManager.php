<?php


namespace Model;


/**
 *
 * Class Post
 * @package Framework
 */
class PostManager extends Manager
{


    /**
     * @return array
     */
    public function getPosts()
    {
        $db = $this->connectDB();
        $req = $db->query('SELECT * FROM posts ORDER BY id DESC LIMIT 10');
        return $req->fetchAll();
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getPost($id)
    {
        $db = $this->connectDB();
        $req = $db->prepare('SELECT * FROM posts WHERE id = ?');
        $req->execute(array($id));
        return $req->fetchObject();
    }

}
