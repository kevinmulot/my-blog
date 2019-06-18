<?php


namespace Controller;


/**
 * Class HomeController
 * @package Controller
 */
class HomeController extends Controller
{
    /**
     * @return \Twig\Environment
     */
    public function indexAction()
    {

        return $this->render('home.twig');

    }

    /**
     *
     */
    public function emailAction()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        if (isset($email))
            // EDIT THE 2 LINES BELOW AS REQUIRED
            $to = "kevin.mulot@gmail.com";
        $email_subject = "Nouvelle demande de contact";
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING); // required
        $email_from = filter_input(INPUT_POST, 'email_from', FILTER_SANITIZE_STRING); // required
        $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING); // not required
        $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING); // required

        // validation expected data exists
        if (empty($name) ||
            empty($email_from) ||
            empty($comments)) {

            $email_message = "Form details below.\n\n";
            $email_message .= "Name: " . $this->clean_string($name) . "\n";
            $email_message .= "Email: " . $this->clean_string($email_from) . "\n";
            $email_message .= "Telephone: " . $this->clean_string($telephone) . "\n";
            $email_message .= "Comments: " . $this->clean_string($comments) . "\n";

            // create email headers
            $headers = 'From: ' . $email_from . "\r\n" .
                'Reply-To: ' . $email_from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($to, $email_subject, $email_message, $headers);
            echo "envoie succes";
        }
        echo "erreur";
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