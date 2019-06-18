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
     * @param $username
     * @return bool|\PDOStatement
     */
    public function checkUser($email, $username)
    {
        $db = $this->connectDB();
        $req = $db->prepare('SELECT * FROM users WHERE pseudo = $username OR email= $email LIMIT 1');
        $req->execute(array($email, $username));
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
        $req = $db->prepare("INSERT INTO users (firstname, lastname, username, mail, password) VALUES('$firstname', '$lastname', '$username', '$email', '$password')");
        $req->execute(array($firstname, $lastname, $username, $email, $password));
        return $req;
    }

}