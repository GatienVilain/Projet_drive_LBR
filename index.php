<?php // Router

require_once("components/Controllers/Password/Change.php");
require_once("components/Controllers/Password/Recover.php");
require_once("components/Controllers/Login.php");
require_once("components/Controllers/SendRecoveryEmail.php");
require_once("components/Controllers/VerifyRecoveryCode.php");

require_once("components/Model/User.php");


use Application\Controllers\Password\ChangePassword;
use Application\Controllers\Password\RecoverPassword;
use Application\Controllers\Login;
use Application\Controllers\SendRecoveryEmail;
use Application\Controllers\VerifyRecoveryCode;

use Application\Model\User;


try
{
    if ( isset($_GET['action']) && $_GET['action'] !== '')
    {
        if ( (new User())->is_connected() )
        {
            // Actions possible lorsque l’on est connecté
            echo "tu es co et tu fais des actions";
        }

        // Actions disponible dans tous les cas
        if ($_GET['action'] === 'login')
        {
            (new Login())->execute();
        }
        elseif ($_GET['action'] === 'logout')
        {
            (new User())->logout();
        }
        elseif ($_GET['action'] === 'recoverPassword')
        {
            (new RecoverPassword())->execute();
        }
        elseif ($_GET['action'] === 'sendRecoveryEmail')
        {
            (new SendRecoveryEmail())->execute();
        }
        elseif ($_GET['action'] === 'verifyRecoveryCode')
        {
            (new VerifyRecoveryCode())->execute();
        }
        elseif ($_GET['action'] === 'changePassword')
        {
            (new ChangePassword())->execute();
        }
        else
        {
            throw new Exception("La page que vous recherchez n'existe pas.");
        }
    }
    else
    {
        if ( (new User())->is_connected() )
        {
            // (new Homepage())->execute();`
            echo "tu es co";
        }
        else {
            (new Login())->execute();
        }
    }
}
catch (Exception $e)
{
    echo "Erreur : " . $e->getMessage();
}