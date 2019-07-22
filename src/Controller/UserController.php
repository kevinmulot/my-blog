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
    public function editAction()
    {
        $data = (new UserManager())->getUser(filter_var($_SESSION['user']['email']));

        return $this->render('user.twig', array('data' => $data));
    }

    /**
     * @return \Twig\Environment
     */
    public function registerAction()
    {
        $data['username'] = ucfirst(strtolower(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS)));
        $data['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $data['$password2'] = filter_input(INPUT_POST, 'passwordconfirm', FILTER_SANITIZE_STRING);
        $data['firstname'] = ucfirst(strtolower(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS)));
        $data['lastname'] = ucfirst(strtolower(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS)));
        // Ensure that the form is correctly filled
        if (!empty($data['firstname']) && !empty($data['lastname']) && !empty($data['email']) && !empty($data['username']) && !empty($data['password']) && !empty($data['$password2'])) {
            $userManager = new UserManager();
            // register user if there are no errors in the form
            $error = $this->verifyUser($data);
            if (!empty($error)) {
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
        $email = filter_input(INPUT_POST, 'emaillog', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'passwordlog', FILTER_SANITIZE_STRING);

        if (!empty($email) && !empty($password)) {
            $userManager = new UserManager();
            $info = $userManager->checkUser($email);
            if ($info !== false) {
                $info = $userManager->getUser($email);
                if (password_verify($password, $info['password']) === true) {
                    $status = $this->session->checkStatus($info['status']);
                    $this->session->createSession($info['id'], $info['username'], $info['email'], $status);
                    $this->alert("Vous êtes maintenant connecté !");

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

    /**
     * @return \Twig\Environment
     */
    public function updateAction()
    {
        $data['username'] = ucfirst(strtolower(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS)));
        $data['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['password0'] = filter_input(INPUT_POST, 'oldpassword', FILTER_SANITIZE_STRING);
        $data['password'] = filter_input(INPUT_POST, 'newpassword', FILTER_SANITIZE_STRING);
        $data['$password2'] = filter_input(INPUT_POST, 'passwordconfirm', FILTER_SANITIZE_STRING);
        $data['oldemail'] = filter_var($_SESSION['user']['email']);

        $userManager = new UserManager();
        $info = $userManager->getUser($data['oldemail']);
        $error = $this->verifyUser($data);

        if (!empty($data['password0']) && empty($data['password']) || empty($data['password0']) && !empty($data['password'])) {
            $error['password0'] = 'Veuillez remplir tous les champs !';
        }
        if (!empty($data['password0'] && password_verify($data['password0'], $info['password']) === false)) {
            $error['password0'] = "Mauvais password !";
        }
        if (!empty($error)) {
            return $this->render("user.twig", array("error" => $error, "data" => $info));
        }
        $data = $this->updateData($data);
        $info = $userManager->getUser($data['oldemail']);
        $status = $this->session->checkStatus($info['status']);
        $this->session->createSession($info['id'], $info['username'], $info['email'], $status);
        $this->alert("Modifications enregistrées !");

        return $this->render("home.twig", array('session' => filter_var_array($_SESSION)));
    }

    /**
     * @param $data
     * @param $info
     * @return bool
     */
    public function updateData($data)
    {
        $userManager = new UserManager();
        if (!empty($data['email'])) {
            $userManager->update($data['email'], 'email', $data['oldemail']);
            $data['oldemail'] = $data['email'];
        }
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);//encrypt the password before saving in the database
            $userManager->update($data['password'], 'password', $data['oldemail']);
        }
        if (!empty($data['username'])) {
            $userManager->update($data['username'], 'username', $data['oldemail']);
        }
        return $data;
    }

    /**
     * @param $data
     * @return array
     */
    public function verifyUser($data)
    {
        $error = [];
        $userManager = new UserManager();
        if (!empty($data['email']) && $userManager->checkUser($data['email']) == true) {
            $error['email'] = "Cet email est déjà utilisé !";
        }
        if (!empty($data['username']) && $userManager->checkUsername($data['username']) == true) {
            $error['username'] = "Ce Nom est déjà utilisé !";
        }
        if (!empty($data['password']) && $data['password'] !== $data['$password2']) {
            $error['password'] = "Vos passwords sont différents !";
        }
        return $error;
    }
}
