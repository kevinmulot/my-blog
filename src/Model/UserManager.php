<?php

namespace App\Model;

/**
 * Class UserManager
 * @package App\Model
 */
class UserManager extends Manager
{
    /**
     * @param $email
     * @return bool
     */
    public function checkUser($email)
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
    public function checkUsername(string $username)
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
    public function getUser(string $email)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare('SELECT * FROM users WHERE email= ?');
        $req->execute(array($email));
        $req = $req->fetch();

        return $req;
    }

    /**
     * @return array|bool|\PDOStatement
     */
    public function getAllUsers()
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("SELECT * FROM users WHERE status='normal' ORDER BY username");
        $req->execute();
        $req = $req->fetchAll();

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
    public function createUser($data)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("INSERT INTO users (firstname, lastname, username, email, password, status) VALUES (?, ?, ?, ?, ?, 'normal')");
        $req->execute(array($data['firstname'], $data['lastname'], $data['username'],$data['email'], $data['password']));

        return $req;
    }

    /**
     * @param $idy
     */
    public function deleteUser(int $idy)
    {
        $dtb = $this->connectDB();
        $req = $dtb->prepare("DELETE FROM users WHERE id = ? AND status = 'normal'");
        $req->execute(array($idy));
    }

    /**
     * @param $data
     * @param $row
     * @param $email
     */
    public function update($data, $row, $email){
        $dtb = $this->connectDB();
        $req = $dtb->prepare("UPDATE users SET $row = ? WHERE email = ? ");
        $req->execute(array($data, $email));
    }
}
