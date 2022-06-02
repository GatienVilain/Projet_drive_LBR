<?php

$erreur = "";

if ( !empty($_POST['email']) && (!empty($_POST['password'])) )
{
    //on teste les identifiants
    if ( $_POST['email'] === 'celestincaptal@gmail.com' && $_POST['password'] === '1234' ){
        //on connecte
         session_start();
         $_SESSION['connecte'] = 1;
         header('Location: index.php?page=home');
         exit();
    }
    else {
        $erreur = "Identifiants incorrects";
    }
}