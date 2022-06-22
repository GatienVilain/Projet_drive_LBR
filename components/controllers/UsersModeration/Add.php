<?php

namespace Application\Controllers\UsersModeration;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Password.php");
require_once("components/Model/Log.php");
require_once('components/Model/Email.php');

use Application\Model\Log;
use Application\Model\Email;
use Application\Tools\Database\DatabaseConnection;
use Application\Model\Password;

class AddUser
{
    public function execute()
	{
        $validation=FALSE;
        $error='';

        try
        {
            if (isset($_POST['mail']))
            {
                $mail = $_POST['mail'];
                $connexion = new DatabaseConnection();

                if (filter_var($mail, FILTER_VALIDATE_EMAIL))
                {
                    $user = $connexion->get_user($mail);

                    if ( !($user == -1 || $user['compte_supprime'] == 1) )
                    {
                        throw new \Exception('Cette email est déjà utilisé.');
                    }
                }
                else
                {
                    throw new \Exception('Email invalide');
                }
            }


            if (isset($_POST['first_name']))
            {
                $first_name = $_POST['first_name'];
            }


            if (isset($_POST['name']))
            {
                $name = $_POST['name'];
            }


            if (isset($_POST['role']))
            {
                $role = $_POST['role'];

                if ($role == 'invité')
                {
                    $role = 'invite';
                }
            }


            if ( isset($_POST['new-password-field']) && isset($_POST['confirmation-password-field']) )
            {
                if($_POST['new-password-field'] == $_POST['confirmation-password-field'])
                {
                    $password = new Password($_POST['new-password-field']);

                    if ($password->checkFormat())
                    {
                        $password = password_hash($password->getValue(), PASSWORD_DEFAULT);
                        $validation = TRUE;
                    }
                    else {
                        throw new \Exception('mot de passe invalide');
                    }
                }
                else {
                    throw new \Exception('mots de passes différents');
                }
            }

            if (isset($_POST['profile-description']))
            {
                $profile_description = $_POST['profile-description'];
            }
            else
            {
                $profile_description = '';
            }


            if ($validation)
            {
                $connexion->add_user($mail,$first_name,$name,$password,$profile_description,$role);

                $subject="codes d'accès Drive LBR";
                $message="Vos codes d'accès au Drive des Briques rouges sont: <br>";
                $message.="mail de connection:  ";
                $message.=$mail;
                $message.="<br>mot de passe:  ";
                $message.=$_POST['new-password-field'];

                $email = new Email($mail, $subject, $message);
                $email->SendEmail();

                $txt = 'à créé le compte de '. $first_name . ' ' . $name;
                ( new Log() )->ecrire_log($_SESSION['email'], $txt);

                header('Location: index.php?action=usersModeration');
            }
        }
        catch (\Exception $e)
        {
            $error = $e->getMessage();
            require('public/view/add_user.php');
        }
	}
}