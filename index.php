<?php // Router

require_once("components/Controllers/Homepage.php");
require_once("components/Controllers/Password/Change.php");
require_once("components/Controllers/Password/Recover.php");
require_once("components/Controllers/Login.php");
require_once("components/Controllers/SendRecoveryEmail.php");
require_once("components/Controllers/VerifyRecoveryCode.php");
require_once("components/Controllers/History.php");

require_once("components/Model/User.php");



use Application\Controllers\Homepage;
use Application\Controllers\Password\ChangePassword;
use Application\Controllers\Password\RecoverPassword;
use Application\Controllers\Login;
use Application\Controllers\SendRecoveryEmail;
use Application\Controllers\VerifyRecoveryCode;
use Application\Controllers\History;

use Application\Model\User;


try
{
    if ( isset($_GET['action']) && $_GET['action'] !== '')
    {

        switch ($_GET['action'])
        {
            case 'history':

                if ( (new User())->is_connected() )
                {
                    // Actions possible lorsque l’on est connecté
                    switch ($_GET['action'])
                    {
                        case 'history':
                            ( new History() )->execute();
                            break;
                    }
                }
                break;

            // Actions disponible dans tous les cas

            case 'login':

                (new Login())->execute();
                break;

            case 'logout':

                (new User())->logout();
                break;

            case 'recoverPassword':

                (new RecoverPassword())->execute();
                break;

            case 'recoverPassword':

                (new RecoverPassword())->execute();
                break;

            case 'sendRecoveryEmail':

                (new SendRecoveryEmail())->execute();
                break;

            case 'verifyRecoveryCode':

                (new VerifyRecoveryCode())->execute();
                break;

            case 'changePassword':

                (new ChangePassword())->execute();
                break;

            default:

                throw new Exception("La page que vous recherchez n'existe pas.");
        }
    }
    else
    {
        if ( (new User())->is_connected() )
        {
            (new Homepage())->execute();
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