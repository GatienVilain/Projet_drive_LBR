<?php // Router

require_once("components/Controllers/Homepage.php");
require_once("components/Controllers/Basket.php");
require_once("components/Controllers/Profile/Get.php");
require_once("components/Controllers/Profile/ChangePassword.php");
require_once("components/Controllers/Profile/ChangeDescription.php");
require_once("components/Controllers/Password/Change.php");
require_once("components/Controllers/Password/Recover.php");
require_once("components/Controllers/Login.php");
require_once("components/Controllers/SendRecoveryEmail.php");
require_once("components/Controllers/VerifyRecoveryCode.php");
require_once("components/Controllers/History.php");
require_once("components/Controllers/deleteFile.php");
require_once("components/Controllers/AddNewTag.php");
require_once("components/Controllers/AddNewCategory.php");
require_once("components/Controllers/DeleteTagOrCategory.php");
require_once("components/Controllers/EditTagOrCategory.php");
require_once("components/Controllers/SortMaj.php");
require_once("components/Controllers/UsersModeration/Get.php");
require_once("components/Controllers/UsersModeration/Delete.php");
require_once("components/Controllers/UsersModeration/GetAdd.php");
require_once("components/Controllers/UsersModeration/Add.php");

require_once("components/Model/User.php");


use Application\Controllers\Homepage;
use Application\Controllers\Basket;
use Application\Controllers\Profile\GetProfile;
use Application\Controllers\Profile\ChangePassword as ChangePasswordProfile;
use Application\Controllers\Profile\ChangeDescription;
use Application\Controllers\Password\ChangePassword;
use Application\Controllers\Password\RecoverPassword;
use Application\Controllers\Login;
use Application\Controllers\SendRecoveryEmail;
use Application\Controllers\VerifyRecoveryCode;
use Application\Controllers\History;
use Application\Controllers\deleteFile;
use Application\Controllers\AddNewTag;
use Application\Controllers\AddNewCategory;
use Application\Controllers\DeleteTagOrCategory;
use Application\Controllers\EditTagOrCategory;
use Application\Controllers\SortMaj;
use Application\Controllers\UsersModeration\GetUsersModeration;
use Application\Controllers\UsersModeration\DeleteUser;
use Application\Controllers\UsersModeration\GetAddPage;
use Application\Controllers\UsersModeration\AddUser;

use Application\Model\User;


try
{
    if ( isset($_GET['action']) && $_GET['action'] !== '')
    {
        $action_found = False;

        if ( (new User())->is_connected() )
        {
            // Actions possible lorsque l’on est connecté
            if ($_GET['action'] === 'history')
            {
               ( new History() )->execute();
               $action_found = True;
            }
            elseif ($_GET['action'] === 'profile')
            {
                ( new GetProfile() )->execute();
                $action_found = True;
            }
            elseif ($_GET['action'] === 'changePasswordProfile')
            {
                (new ChangePasswordProfile())->execute();
                $action_found = True;
            }
            elseif ($_GET['action'] === 'basket')
            {
                (new Basket())->execute();
                $action_found = True;
            }

            elseif ($_GET['action'] === 'deleteFile')
            {
                (new deleteFile())->execute();
                $action_found = True;
            }

            elseif ($_GET['action'] === 'addNewTag')
            {
                (new AddNewTag())->execute();
                $action_found = True;
            }

            elseif ($_GET['action'] === 'addNewCategory')
            {
                (new AddNewCategory())->execute();
                $action_found = True;
            }

            elseif($_GET['action']==='sortMaj')
            {
                (new SortMaj())->execute();
                $action_found = True;
            }

            elseif($_GET['action']==='deleteTagOrCategory')
            {
                (new DeleteTagOrCategory())->execute();
                $action_found = True;
            }

            elseif($_GET['action']==='editTagOrCategory')
            {
                (new EditTagOrCategory())->execute();
                $action_found = True;
            }

			elseif ($_GET['action'] === 'usersmoderation')
            {
                if ( isset($_POST['button']) && $_POST['button'] !== '')
                {
                    if ( $_POST['button'] === 'supprimer' )
                    {
                        (new DeleteUser())->execute();
                        $action_found = True;
                    }
                    elseif ( $_POST['button'] === 'ajouter' )
                    {
                        (new GetAddPage())->execute();
                        $action_found = True;
                    }
                }
                else {
                    (new GetUsersModeration())->execute();
                    $action_found = True;
                }
            }
            elseif ( $_GET['action'] === 'addUser' )
            {
                (new AddUser())->execute();
                $action_found = True;
            }
            elseif ($_GET['action'] === 'changeDescription')
            {
                (new ChangeDescription())->execute();
                $action_found = True;
            }
        }

        // Actions disponible dans tous les cas
        if ($_GET['action'] === 'login')
        {
            (new Login())->execute();
            $action_found = True;
        }
        elseif ($_GET['action'] === 'logout')
        {
            (new User())->logout();
            $action_found = True;
        }
        elseif ($_GET['action'] === 'recoverPassword')
        {
            (new RecoverPassword())->execute();
            $action_found = True;
        }
        elseif ($_GET['action'] === 'sendRecoveryEmail')
        {
            (new SendRecoveryEmail())->execute();
            $action_found = True;
        }
        elseif ($_GET['action'] === 'verifyRecoveryCode')
        {
            (new VerifyRecoveryCode())->execute();
            $action_found = True;
        }
        elseif ($_GET['action'] === 'changePassword')
        {
            (new ChangePassword())->execute();
            $action_found = True;
        }

        if ( $action_found = False )
        {
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