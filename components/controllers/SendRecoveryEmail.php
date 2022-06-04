<?php

namespace Application\Controllers;

require_once('components/Model/Code.php');
require_once('components/Model/Email.php');

use Application\Model\Code;
use Application\Model\Email;

class SendRecoveryEmail
{
    public function execute()
    {
        // session_start();
        try {
            if ( !empty($_POST['email'] ) && (isset($_POST['email'] )) )
            {
                $subject  = "Code de recupération du mot de passe";
                $code = new Code(10);
                $message = "Voici votre code pour modifier votre mot de passe : " . $code->getValue();

                $email = new Email($_POST['email'], $subject, $message);
                $email->SendEmail($email, $message);

                $_SESSION['email'] = $email->getAddress();
                $_SESSION['code']  = $code->getValue();

                $error = "";
                require('public\view\verify_recovery_code.php');
            }
            else {
                throw new \Exception('Aucun email renseigné');
            }
        }
        catch (\Exception $e) {
            $error = $e->getMessage();
            require('public\view\recover_password.php');
        }
    }
}
