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
        $req = $db->prepare('SELECT * FROM posts ORDER BY id DESC');
        $req->execute();
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

    /**
     * @param $title
     * @param $author
     * @param $lead
     * @param $content
     * @param $id
     * @return bool
     */
    public function updatePost($title, $author, $lead, $content, $id)
    {
        $db = $this->connectDB();
        $req = $db->prepare('UPDATE posts SET title = ?, author = ?, lead = ? , content = ? WHERE id =  ? ');

        $req->execute(array($title, $author, $lead, $content, $id));
        return true;

    }

    /**
     * @param $title
     * @param $author
     * @param $lead
     * @param $content
     * @return bool
     */
    public function addPost($title, $author, $lead, $content)
    {
        $db = $this->connectDB();
        $req = $db->prepare('INSERT INTO posts (title, author, lead, content, add_date ) VALUES (?,?,?,?, NOW())');

        $req->execute(array($title, $author, $lead, $content));
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deletePost($id)
    {
        $db = $this->connectDB();
        $req = $db->prepare('DELETE FROM posts WHERE id = ?');
        $req->execute(array($id));
        return true;
    }
}
