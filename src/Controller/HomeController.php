<?php

namespace App\Controller;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends Controller
{
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction()
    {
        return $this->render('home.twig');
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function emailAction()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        if (isset($email))
            // EDIT THE 2 LINES BELOW AS REQUIRED
            $_to = "kevin.mulot@hotmail.fr";
        $email_subject = "Nouveau contact";
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING); // required
        $email_from = filter_input(INPUT_POST, 'email_from', FILTER_SANITIZE_STRING); // required
        $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING); // not required
        $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING); // required

        // validation expected data exists
        if (!empty($name) && !empty($email_from) && !empty($comments)) {
            $email_message = "Détails du message\n\n";
            $email_message .= "Nom : " . $this->clean_string($name) . "\n";
            $email_message .= "Email : " . $this->clean_string($email_from) . "\n";
            $email_message .= "Téléphone : " . $this->clean_string($telephone) . "\n";
            $email_message .= "Message : " . $this->clean_string($comments) . "\n";

            // create email headers
            $headers = 'From: ' . $email_from . "\r\n" .
                'Reply-To: ' . $email_from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($_to, $email_subject, $email_message, $headers);

            $this->alert("Email envoyé !");
        }
        return $this->render("home.twig");
    }

    /**
     * @param $string
     * @return mixed
     */
    private function clean_string($string)
    {
        $bad = array("content-type", "bcc:", "to:", "cc:", "href");

        return str_replace($bad, "", $string);
    }
}
