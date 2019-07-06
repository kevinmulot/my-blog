<?php

namespace Model;

/**
 * Class Comment
 * @package Model
 */
class Comment
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $author;
    /**
     * @var
     */
    private $content;
    /**
     * @var
     */
    private $date;
    /**
     * @var
     */
    private $validation;
    /**
     * @var
     */
    private $posts_id;
    /**
     * @var
     */
    private $users_id;

    /**
     * Comment constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @param mixed $validation
     */
    public function setValidation($validation): void
    {
        $this->validation = $validation;
    }

    /**
     * @return mixed
     */
    public function getPostsId()
    {
        return $this->posts_id;
    }

    /**
     * @param mixed $posts_id
     */
    public function setPostsId($posts_id): void
    {
        $this->posts_id = $posts_id;
    }

    /**
     * @return mixed
     */
    public function getUsersId()
    {
        return $this->users_id;
    }

    /**
     * @param mixed $users_id
     */
    public function setUsersId($users_id): void
    {
        $this->users_id = $users_id;
    }


}