<?php

namespace Application\Controllers\UsersModeration;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Password.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Model\Password;

class AddUser
{
    public function execute()
	{
        $validation=FALSE;
        $error='';


        if (isset($_POST['name']))
        {
            $name= $_POST['name'];
        }
        if (isset($_POST['first_name']))
        {
            $first_name=$_POST['first_name'];
        }
        if (isset($_POST['mail']))
        {
            $mail=$_POST['mail'];

        }
        if (isset($_POST['new-password-field'])){
            $password2=new Password($_POST['new-password-field']);
            $password=$_POST['new-password-field'];
        }
        if (isset($_POST['profile-description']))
        {
            $profile_description=$_POST['profile-description'];
        }
        else
        {
            $profile_description='';
        }

        if (filter_var($mail, FILTER_VALIDATE_EMAIL))
        {
            if($_POST['new-password-field']==$_POST['confirmation-password-field'])
            {

            
                if($password2->checkFormat())
                {
                    $password=password_hash($password, PASSWORD_DEFAULT);
                    $validation=TRUE;
                }
                else
                {
                    $error.='mdp invalide';
                }
            }
            else{
                $error.='mots de passe différents'; 
            }
        }
        else
        {
            $error.='mail invalide';
        }

        



    if ($validation)
    {

        (new DatabaseConnection())->add_user($mail,$first_name,$name,$password,$profile_description,'admin');
        header('Location: index.php?action=usersmoderation');
    }
    else
    {
        require('public/view/add_user.php');
    }
                




        
		
	}
}