<?php

namespace App\Model;

/**
 * Class PostManager
 * @package App\Model
 */
class PostManager extends Manager
{
    /**
     * @return mixed
     */
    public function countPosts()
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('SELECT COUNT(*) AS total FROM posts');
        $req->execute();
        $total = $req->fetch();

        return $total;
    }

    /**
     * @return array
     */
    public function getPostsPP($page, $article)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("SELECT * FROM posts ORDER BY add_date DESC LIMIT " . (($page - 1) * $article) . ",$article");
        $req->execute();

        return $req->fetchAll();
    }

    /**
     * @return array
     */
    public function getPosts()
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('SELECT * FROM posts ORDER BY add_date DESC');
        $req->execute();

        return $req->fetchAll();
    }

    /**
     * @param $idy
     * @return mixed
     */
    public function getPost(int $idy)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('SELECT * FROM posts WHERE id = ?');
        $req->execute(array($idy));

        return $req->fetchObject();
    }

    /**
     * @param $title
     * @param $author
     * @param $lead
     * @param $content
     * @param $idy
     * @return bool
     */
    public function updatePost($data)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('UPDATE posts SET title = ?, author = ?, headline = ? , content = ? , add_date = NOW() WHERE id =  ? ');
        $req->execute(array($data['title'], $data['author'], $data['headline'], $data['content'], $data['idy']));

        return true;
    }

    /**
     * @param $title
     * @param $author
     * @param $lead
     * @param $content
     * @return bool
     */
    public function addPost(string $title, string $author, string $headline, string $content)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('INSERT INTO posts (title, author, headline, content, add_date ) VALUES (?,?,?,?, NOW())');
        $req->execute(array($title, $author, $headline, $content));

        return true;
    }

    /**
     * @param $idy
     * @return bool
     */
    public function deletePost(int $idy)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('DELETE FROM posts WHERE id = ?');
        $req->execute(array($idy));

        return true;
    }
}
