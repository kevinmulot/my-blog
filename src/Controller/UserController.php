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
     *
     */
    public function registerAction()
    {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $passwordconfirm = filter_input(INPUT_POST, 'passwordconfirm', FILTER_SANITIZE_STRING);
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);

        // Ensure that the form is correctly filled
        if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($username) && !empty($password) && !empty($passwordconfirm)) {
            $user = new UserManager();

            // register user if there are no errors in the form
            if ($user->checkMail($email) == false) {

                if ($user->checkUsername($username) == false) {

                    if ($password === $passwordconfirm) {
                        $password = password_hash($password, PASSWORD_BCRYPT);//encrypt the password before saving in the database
                        $user->createUser($firstname, $lastname, $username, $email, $password);
                        $info = $user->getUser($email);
                        $user = new User($info);
                        $_SESSION['username'] = $user->getUsername();

                        echo "<script>alert(\"Votre compte a ete cree avec succes !\")</script>";
                        echo $this->render('home.twig', array('session' => $_SESSION));
                    } else {
                        echo "<script>alert(\"Les mots de passes ne sont pas identiques !\")</script>";
                        echo $this->render("register.twig");
                    }
                } else {
                    echo "<script>alert(\"Ce pseudo existe deja !\")</script>";
                    echo $this->render("register.twig");
                }
            } else {
                echo "<script>alert(\"cet email existe deja !\")</script>";
                echo $this->render("register.twig");
            }
        } else {
            echo "<script>alert(\"Des champs n'ont pas ete remplis !\")</script>";
            echo $this->render("register.twig");
        }
    }

    /**
     *
     */
    public function loginAction()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (!empty($email) && !empty($password)) {
            $info = (new UserManager())->getUser($email);

            if (password_verify($password, $info['password'])) {
                $user = new User($info);
                $_SESSION['username'] = $user->getUsername();

                echo "<script>alert(\"Vous etes maintenant connecte !\")</script>";
                echo $this->render('home.twig', array('session' => $_SESSION));

            } else {
                echo "<script>alert(\"Email ou Password incorrecte !\")</script>";
                echo $this->render("home.twig");
            }
        } else {
            echo "<script>alert(\"Des champs n'ont pas ete remplis !\")</script>";
            echo $this->render("home.twig");
        }
    }
}
