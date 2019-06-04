<?php




/**
 * Class Post
 * @package Framework
 */
class Post extends Manager
{
    /**
     * @return string
     */


    public function getPostlist(){

            $sql = 'SELECT * FROM posts ORDER BY id DESC LIMIT 10';
            $post = $this->executeRequest($sql);
                return $post;

    }

    /**
     * @param $postId
     * @return mixed
     */
    public function getPost($postId){
            $sql = 'SELECT * FROM posts WHERE id = ?';
            $post = $this->executeRequest($sql,array($postId));
            if ($post->rowCount() == 1)
                return $post->fetch();
            else
                throw new \Exception("Aucun post ne correspond à l'identifiant '$postId'");
    }

}
