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
            if ( $result != -1 && password_verify($password,$result['mot_de_passe']) ){
                //on connecte
                $_SESSION['connected'] = 1;
                $_SESSION['verify'] = 1;
                $_SESSION['email'] = $email;

                if ($_POST['remember_me']== true){
                    //-------------------------
                    setcookie(
                        'LOGGED_USER',
                        $email,
                        [
                            'expires' => time() + 30*24*3600,
                            'secure' => true,
                            'httponly' => true,
                        ]);
                    //-------------------------
                    setcookie(
                        'PASSWORD_USER',
                        $password,
                        [
                            'expires' => time() + 30*24*3600,
                            'secure' => true,
                            'httponly' => true,
                        ]);
                }
                else {
                    setcookie(
                        'LOGGED_USER',
                        $email,
                        [
                            'expires' => time() - 30*24*3600,
                            'secure' => true,
                            'httponly' => true,
                        ]);
                    
                        setcookie(
                            'PASSWORD_USER',
                            $password,
                            [
                                'expires' => time() - 30*24*3600,
                                'secure' => true,
                                'httponly' => true,
                            ]);

                }

                ( new Log() )->ecrire_log($email,'connecté');

                header('Location: index.php');
            }
            else {
                $error = "Identifiants incorrects";
            }
        }
        else {
            $error = "";
            $mail_memoire = $this->mail_cookie();
            $mdp_memoire = $this->mdp_cookie();
        }

        require('public/view/login.php');
    }

    public function mail_cookie()
    {
        if (isset($_COOKIE['LOGGED_USER'])){
            return $_COOKIE['LOGGED_USER'];

        }
        else{
            return "";
        }


    }
    public function mdp_cookie()
    {
        if (isset($_COOKIE['PASSWORD_USER'])){
            return $_COOKIE['PASSWORD_USER'];

        }
        else{
            return "";
        }


    }

}
