<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Log.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Model\Log;

class Login
{
    public function execute()
    {
        if ( !empty($_POST['email']) && (!empty($_POST['password'])) )
        {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Ce connecte à la base de donnée
            $connection = new DatabaseConnection();

            // Recupére les identifiants
            $result = $connection->get_user_password($email);
            // $result = ['mot_de_passe' => '1234']; // mot de passe valide temporaire

            // Les comparer avec ceux donné par l’utilisateur
            if ( $result != -1 && $password === $result['mot_de_passe'] ){
                //on connecte
                $_SESSION['connected'] = 1;
                $_SESSION['verify'] = 1;

                ( new Log() )->ecrire_log($email,'connecté');

                header('Location: index.php');
            }
            else {
                $error = "Identifiants incorrects";
            }
        }
        else {
            $error = "";
        }

        require('public/view/login.php');
    }
}
