<?php

namespace Model;

/**
 * Class UserManager
 * @package Model
 */
class UserManager extends Manager
{
    /**
     * @param $email
     * @return bool
     */
    public function checkMail($email)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('SELECT email FROM users WHERE  email = ? LIMIT 1');
        $req->execute(array($email));
        if ($req->fetchColumn()) {

            return true;
        }
        return false;
    }

    /**
     * @param $username
     * @return bool
     */
    public function checkUsername($username)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('SELECT username FROM users WHERE  username = ? LIMIT 1');
        $req->execute(array($username));
        if ($req->fetchColumn()) {

            return true;
        }
        return false;
    }

    /**
     * @param $email
     * @return bool|mixed|\PDOStatement
     */
    public function getUser($email)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('SELECT * FROM users WHERE email= ?');
        $req->execute(array($email));
        $req = $req->fetch();

        return $req;
    }

    /**
     * @param $firstname
     * @param $lastname
     * @param $username
     * @param $email
     * @param $password
     * @return bool|\PDOStatement
     */
    public function createUser($firstname, $lastname, $username, $email, $password)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("INSERT INTO users (firstname, lastname, username, email, password, status) VALUES (?, ?, ?, ?, ?, 'normal')");
        $req->execute(array($firstname, $lastname, $username, $email, $password));

        return $req;
    }
}
