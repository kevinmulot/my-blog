<?php

namespace Controller;


/**
 * Class SessionController
 * @package Controller
 */
class SessionController
{
    /**
     * @param int $id
     * @param string $username
     * @param string $email
     */
    public function createSession(int $id, string $username, string $email, string $statut)
    {
        $_SESSION['user'] = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'statut' => $statut
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
    public function checkStatut($info)
    {
        if ($info == 'admin') {
            return $info = true;

        }
        if ($info == 'normal') {
            return $info = false;
        }
    }

    /**
     * @param $message
     * @param string $type
     */
    public function setAlert($message, $type = 'error')
    {
        $_SESSION['alert'] = array(
            'message' => $message,
            'type' => $type
        );
    }

    /**
     *
     */
    public function closeAction()
    {
        if (array_key_exists('alert', filter_var_array($_SESSION))) {

            unset ($_SESSION['alert']);
        }
    }
}
