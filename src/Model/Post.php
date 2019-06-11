<?php


namespace Model;




/**
 *
 * Class Post
 * @package Framework
 */
class Post extends Manager
{


    public function getLast()
    {   $db = $this->connectDB();
        $posts = $db->query('SELECT * FROM posts ORDER BY id DESC LIMIT 10');
        return $posts;
    }



    public function getURL()
    {
        return 'index.php?p=blog&id=' . $this->id;
    }

    public function getExtrait()
    {
        $html = '<p>' . substr($this->content, 0, 70) . '...</p>';
        $html .= '<p><a href="' . $this->getURL() . '"></a></p>';
        return $html;
    }


    public function getPost()
    {$db = $this->connectDB();
        $req = $db->prepare('SELECT * FROM post WHERE id = ?', [$_GET['id']]);
    return $req->fetchAll();
    }

}
