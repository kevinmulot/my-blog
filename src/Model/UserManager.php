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
        $db = $this->connectDB();
        $req = $db->prepare('SELECT email FROM users WHERE  email = ? LIMIT 1');
        $req->execute(array($email));
        if ($req->fetchColumn()) {

            return true;
        }
    }

    /**
     * @param $username
     * @return bool
     */
    public function checkUsername($username)
    {
        $db = $this->connectDB();
        $req = $db->prepare('SELECT username FROM users WHERE  username = ? LIMIT 1');
        $req->execute(array($username));
        if ($req->fetchColumn()) {

            return true;
        }
    }

    /**
     * @param $email
     * @return bool|mixed|\PDOStatement
     */
    public function getUser($email)
    {
        $db = $this->connectDB();
        $req = $db->prepare('SELECT * FROM users WHERE email= ?');
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
        $db = $this->connectDB();
        $req = $db->prepare("INSERT INTO users (firstname, lastname, username, email, password, statut) VALUES (?, ?, ?, ?, ?, 'normal')");
        $req->execute(array($firstname, $lastname, $username, $email, $password));

        return $req;
    }
}
