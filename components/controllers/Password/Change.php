<?php

namespace Application\Controllers\Password;

require_once("components/Model/Password.php");

use Application\Model\Password;
use Application\Model\User;

class ChangePassword
{
    public function execute()
    {
        try
        {
            if ( isset($_SESSION['verify']) )
            {
                if ( isset($_POST['password']) )
                {
                    if ( $_POST['password'] === $_POST['confirmation_password'] ) {

                        $password = new Password($_POST['password']);

                        if ($password->checkFormat()){
                            //requête sql

                            $error = "Mot de passe enregistré";
                            (new User)->logout();
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
