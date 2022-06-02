<?php

require_once("components/tools/check_password.php");

if ( isset($_POST['password'] ) )
{
    if ( $_POST['password'] === $_POST['confirmation_password'] )
    {
        if ( verif_format_mdp($_POST['password']) === true )
        {
            $info = "Mot de passe valide" ;
            //requête sql

            header('Location: index.php');
            exit();
        }
        else
        {
            $info = "Mot de passe invalide" ;
        }
    }
    else
    {
        $info = "Les mots de passe ne sont pas identiques";
    }
}