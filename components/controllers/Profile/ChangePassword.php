<?php

namespace Application\Controllers\Profile;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class ChangePassword
{
    public function execute()
    {
        try
        {
            if ( isset($_POST['old_password']) && $_POST['old_password'] !== '' )
            {
                $current_password = (new DatabaseConnection())->get_user_password($_SESSION['email']);

                if ( $current_password == $_POST['old_password'] )
                {
                    $_SESSION['verify'] = 1;

                    header('Location: index.php?action=changePassword');
                }
                else {
                    throw new \Exception("L’ancien mot de passe n’est pas correct");
                }
            }
            else {
                throw new \Exception('Ancien mot de passe manquant');
            }
        }
        catch (\Exception $e)
        {
            $error = $e->getMessage();
            require('public/view/profile.php');
        }
    }
}