<?php

namespace Application\Controllers;

Class VerifyRecoveryCode
{
    public function execute()
    {
        try {
            if ( isset($_SESSION['code']) )
            {
                if ( isset($_POST['verification_code']) )
                {
                    if ( $_POST['verification_code'] == $_SESSION['code'] )
                    {
                        $_SESSION['verify'] = 1;

                        $error = "";
                        require('public/view/change_password.php');
                    }
                    else {
                        throw new \Exception("Le code de récupération n’est pas correcte.");
                    }
                }
                else {
                    throw new \Exception('Aucun code renseigné.');
                }
            }
            else {
                $error = "La session a expiré. Veuillez retenter l’opération.";
                header('Location: index.php?action=recoverPassword');
            }
        }
        catch (\Exception $e) {
            $error = $e->getMessage();
            require('public\view\verify_recovery_code.php');
        }
    }
}
