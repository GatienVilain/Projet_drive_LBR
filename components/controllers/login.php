<?php

namespace Application\Controllers;

// require_once('components/Tools/Database/Database.php');

// use Application\Controllers\Tools\Database\DatabaseConnection;

class Login
{
    public function execute()
    {
        if ( !empty($_POST['email']) && (!empty($_POST['password'])) )
        {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Ce connecte à la base de donnée
            // $data = new DatabaseConnection();

            // Recupére les identifiants
            // $result = $data->get_user_password($email);
            $result = ['mot_de_passe' => '1234']; // mot de passe valide temporaire

            // Les comparer avec ceux donné par l’utilisateur
            if ( $result != -1 && $password === $result['mot_de_passe'] ){
                //on connecte
                $_SESSION['connected'] = 1;
                $_SESSION['verify'] = 1;

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
