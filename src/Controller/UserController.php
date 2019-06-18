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
     *
     */
    public function registerAction()
    {
        // REGISTER USER
        if (isset($_POST['reg_user'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password_1 = filter_input(INPUT_POST, 'password_1', FILTER_SANITIZE_STRING);
            $password_2 = filter_input(INPUT_POST, 'password_2', FILTER_SANITIZE_STRING);
            $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
            $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);

            // form validation: ensure that the form is correctly filled
            // by adding (array_push()) corresponding error unto $errors array
            if (empty($username)) {
                array_push($errors, "Un pseudo est requis");
            }
            if (empty($email)) {
                array_push($errors, "Un email est requis");
            }
            if (empty($password_1)) {
                array_push($errors, "Un password est requis");
            }
            if (empty($firstname)) {
                array_push($errors, "Un prénom est requis");
            }
            if (empty($lastname)) {
                array_push($errors, "Un nom de famille est requis");
            }
            if ($password_1 != $password_2) {
                array_push($errors, "Les deux passwords ne correspondent pas");
            }
            $user = new UserManager();
            $usercheck = $user->checkUser($email, $username);
            if ($usercheck['pseudo'] === $username) {

                array_push($errors, "Cet email existe déjà");
            }
            if ($usercheck['email'] === $email) {
                array_push($errors, "Ce pseudo existe déjà");
            }
            // register user if there are no errors in the form
            if (count($errors) == 0) {
                $password = password_hash($password_1, PASSWORD_BCRYPT, array('cost' => 12));//encrypt the password before saving in the database
                $user->createUser($firstname, $lastname, $username, $email, $password);
                $_SESSION['email'] = $email;
                $_SESSION['success'] = "Vous êtes maintenant connecté";
                header('location: index.php');
            } else $this->render("register.twig", array('error' => $errors));
        }
    }


}

