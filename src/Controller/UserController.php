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
        $data['username'] = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $passwordConfirm = filter_input(INPUT_POST, 'passwordconfirm', FILTER_SANITIZE_STRING);
        $data['firstname'] = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['lastname'] = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
        // Ensure that the form is correctly filled
        if (!empty($data['firstname']) && !empty($data['lastname']) && !empty($data['email']) && !empty($data['username']) && !empty($data['password']) && !empty($passwordConfirm))
        {
            $userManager = new UserManager();
            $error = [];
            $info = $userManager->checkUser($data['email']);
            // register user if there are no errors in the form
            if ($info == true) {
                $error['email'] = "Cette email est deja utilise !";
                $error['nb']++;
            }
            if ($userManager->checkUsername($data['username']) == true) {
                $error['username'] = "Ce Nom est deja utilise !";
                $error['nb']++;
            }
            if (!empty($error['nb']) or $data['password'] !== $passwordConfirm) {
                $this->alert('Erreur lors de l\'enregistrement ! Vérifiez bien que les  2 passwords sont identiques');
                return $this->render("register.twig", array("error" => $error, "data" => $data));
            }
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);//encrypt the password before saving in the database
            $userManager->createUser($data);
            $info = $userManager->getUser($data['email']);
            $status = $this->session->checkStatus($info['status']);
            $this->session->createSession($info['id'], $info['username'], $info['email'], $status);
            $this->alert("Votre compte a ete cree avec succes !");

            return $this->render('home.twig', array('session' => filter_var_array($_SESSION)));
        }
        $this->alert("Veuillez remplir tous les champs !");

        Return $this->render("register.twig");
    }

    /**
     * @return \Twig\Environment
     */
    public function loginAction()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (!empty($email) && !empty($password)) {
            $userManager = new UserManager();
            $info = $userManager->checkUser($email);
            if ($info !== false) {
                $info = $userManager->getUser($email);
                if (password_verify($password, $info['password']) === true) {
                    $status = $this->session->checkStatus($info['status']);
                    $this->session->createSession($info['id'], $info['username'], $info['email'], $status);
                    $this->alert("Vous etes maintenant connecte !");

                    return $this->render('home.twig', array('session' => filter_var_array($_SESSION)));
                }
            }
            $this->alert('Informations incorrectes, veuillez réessayer !');
            return $this->render("home.twig");
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
