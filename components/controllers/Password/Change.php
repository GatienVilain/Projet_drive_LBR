<?php

namespace Application\Controllers\Password;

require_once("components/Model/Password.php");
require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Model\Password;
use Application\Model\User;
use Application\Tools\Database\DatabaseConnection;

class ChangePassword
{
    public function execute()
    {
        try
        {
            if ( isset($_SESSION['verify']) && isset($_SESSION['email']) )
            {
                if ( isset($_POST['password']) )
                {
                    if ( $_POST['password'] === $_POST['confirmation_password'] ) {

                        $password = new Password($_POST['password']);

                        if ($password->checkFormat()){

                            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                            # Prérare le changement de mot de passe
                            // $change = array("mot_de_passe" => $password->getValue());
                            $change = array("mot_de_passe" => $password); // ! A modifier en OOP

                            // Ce connecte à la base de donnée et change le mot de passe
                            if ( ( new DatabaseConnection() )->update_user($_SESSION['email'], $change) == 0 )
                            {
                                $error = "Mot de passe enregistré";
                                ( new Log() )->ecrire_log($_SESSION['email'],'à changé son mdp');
                                (new User)->logout();
                            }
                            else {
                                throw new \Exception("Erreur serveur : Le mot de passe n’a pas pu être changé");
                            }
                        }
                        else {
                            throw new \Exception("Mot de passe invalide");
                        }
                    }
                    else {
                        throw new \Exception("Les mots de passe ne sont pas identiques");
                    }
                }
                else {
                    throw new \Exception('Aucun mot de passe renseigné');
                }
            }
            else {
                $error = "La session a expiré. Veuillez retenter l’opération.";
                header('Location: index.php?action=recoverPassword');
            }
        }
        catch (\Exception $e)
        {
            $error = $e->getMessage();
            require('public/view/change_password.php');
        }
    }
}
