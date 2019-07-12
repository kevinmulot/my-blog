<?php

namespace Controller;


/**
 * Class SessionController
 * @package Controller
 */
class SessionController
{
    /**
     * @param int $idy
     * @param string $username
     * @param string $email
     */
    public function createSession(int $idy, string $username, string $email, string $status)
    {
        $_SESSION['user'] = [
            'id' => $idy,
            'username' => $username,
            'email' => $email,
            'status' => $status
        ];
    }

    /**
     * @return void
     */
    public function destroySession()
    {
        $_SESSION['user'] = [];
        session_destroy();
    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        if (array_key_exists('user', filter_var_array($_SESSION))) {
            if (!empty(filter_var_array($_SESSION['user']))) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $info
     * @return bool
     */
    public function checkStatus($info)
    {
        if ($info == 'admin') {
            return true;
        }
        return false;
    }
}
