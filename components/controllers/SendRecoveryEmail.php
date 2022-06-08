<?php

namespace Application\Controllers;

require_once('components/Model/Code.php');
require_once('components/Model/Email.php');
require_once("components/Tools/Database/DatabaseConnection.php");


use Application\Model\Code;
use Application\Model\Email;
use Application\Tools\Database\DatabaseConnection;

class SendRecoveryEmail
{
    public function execute()
    {
        // session_start();
        try {
            if ( !empty($_POST['email'] ) && (isset($_POST['email'] )) )
            {
                $email_address = $_POST['email'];

                if ( (new DatabaseConnection)->get_user($email_address) != -1 )
                {
                    $subject  = "Code de recupération du mot de passe";
                    $code = new Code(6);
                    $message = "Voici votre code pour modifier votre mot de passe : " . $code->getValue();

                    $email = new Email($email_address, $subject, $message);
                    $email->SendEmail($email, $message);

                    $_SESSION['email'] = $email->getAddress();
                    $_SESSION['code']  = $code->getValue();

                    $error = "";
                    require('public\view\verify_recovery_code.php');
                }
                else {
                    throw new \Exception('Adresse mail invalide');
                }
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
