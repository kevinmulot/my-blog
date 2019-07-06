<?php

namespace Controller;

use Model\User;
use Model\UserManager;

session_start();

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
            $user = new UserManager();
            // register user if there are no errors in the form
            if ($user->checkMail($email) == true) {
                $error1 = "Cette email est deja utilise !";
                $errorNb++;
            }
            if ($user->checkUsername($username) == true) {
                $error2 = "Ce Nom est deja utilise !";
                $errorNb++;
            }
            if ($password !== $passwordConfirm) {
                $error3 = "Vos passwords sont differents !";
                $errorNb++;
            }
            if ($errorNb > 0) {
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
            }
            if ($errorNb === 0) {
                $password = password_hash($password, PASSWORD_BCRYPT);//encrypt the password before saving in the database
                $user->createUser($firstname, $lastname, $username, $email, $password);
                $info = $user->getUser($email);
                $user = new User($info);
                $_SESSION['username'] = $user->getUsername();
                $this->alert("Votre compte a ete cree avec succes !");

                return $this->render('home.twig', array('session' => $_SESSION));
            }
        }
        $error = array("fields" => "Veuillez remplir tous les champs");

        Return $this->render("register.twig", array("error" => $error));
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
            if ($errorNb > 0) {
                $error = array(
                    "emailLog" => $error1,
                    "passwordLog" => $error2);

                return $this->render("home.twig",
                    array("error" => $error,
                        "email" => $email));
            }
            if ($errorNb === 0) {
                $user = new User($info);
                $_SESSION['username'] = $user->getUsername();
                $this->alert("Vous etes maintenant connecte !");

                return $this->render('home.twig', array('session' => $_SESSION));
            }
        }
        $this->alert("Veuillez remplir tous les champs !");

        return $this->render("home.twig");
    }
}
