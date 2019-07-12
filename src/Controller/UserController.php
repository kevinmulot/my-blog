<?php

namespace Controller;

use Model\UserManager;

/**
 * Class UserController
 * @package Controller
 */
class UserController extends Controller
{
    /**
     * @return \Twig\Environment
     */
    public function indexAction()
    {
        return $this->render('register.twig');
    }

    /**
     * @return \Twig\Environment
     */
    public function registerAction()
    {
        $errorNb = 0;
        $error1 = null;
        $error2 = null;
        $error3 = null;
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $passwordConfirm = filter_input(INPUT_POST, 'passwordconfirm', FILTER_SANITIZE_STRING);
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);

        // Ensure that the form is correctly filled
        if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($username) && !empty($password) && !empty($passwordConfirm)) {
            $userManager = new UserManager();
            // register user if there are no errors in the form
            if ($userManager->checkMail($email) == true) {
                $error1 = "Cette email est deja utilise !";
                $errorNb++;
            }
            if ($userManager->checkUsername($username) == true) {
                $error2 = "Ce Nom est deja utilise !";
                $errorNb++;
            }
            if ($password !== $passwordConfirm) {
                $error3 = "Vos passwords sont differents !";
                $errorNb++;
            }
            switch (true) {

                case ($errorNb > 0) :
                $error = array(
                    "email" => $error1,
                    "username" => $error2,
                    "password" => $error3);

                return $this->render("register.twig",
                    array("error" => $error,
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "username" => $username,
                        "email" => $email));

                case ($errorNb === 0) :
                    $password = password_hash($password, PASSWORD_BCRYPT);//encrypt the password before saving in the database
                    $userManager->createUser($firstname, $lastname, $username, $email, $password);
                    $info = $userManager->getUser($email);
                    $status = $this->session->checkStatus($info['status']);
                    $this->session->createSession($info['id'], $info['username'], $info['email'], $status);
                    $this->alert("Votre compte a ete cree avec succes !");

                    return $this->render('home.twig', array('session' => filter_var_array($_SESSION)));
            }

        }
        $this->alert("Veuillez remplir tous les champs !");

        Return $this->render("register.twig");
    }

    /**
     * @return \Twig\Environment
     */
    public function loginAction()
    {
        $errorNb = 0;
        $error1 = null;
        $error2 = null;
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (!empty($email) && !empty($password)) {
            $userManager = new UserManager();
            $info = $userManager->getUser($email);
            $passVerif = password_verify($password, $info['password']);

            if ($userManager->checkMail($email) == false) {
                $error1 = "Email inexistant !";
                $errorNb++;
            }
            if ($passVerif == false) {
                $error2 = "Mauvais Password !";
                $errorNb++;
            }
            switch (true) {

                case ($errorNb > 0) :
                    $error = array(
                        "emailLog" => $error1,
                        "passwordLog" => $error2);

                    return $this->render("home.twig",
                        array("error" => $error,
                            "email" => $email));

                case ($errorNb === 0) :
                    $status = $this->session->checkStatus($info['status']);
                    $this->session->createSession($info['id'], $info['username'], $info['email'], $status);
                    $this->alert("Vous etes maintenant connecte !");

                    return $this->render('home.twig', array('session' => filter_var_array($_SESSION)));
            }
        }
        $this->alert("Veuillez remplir tous les champs !");

        return $this->render("home.twig");
    }

    /**
     * @return \Twig\Environment
     */
    public function logoutAction()
    {
        if ($this->session->isLogged()) {
            $this->session->destroySession();
        }
        return $this->render('home.twig', array('session' => filter_var_array($_SESSION)));
    }
}
