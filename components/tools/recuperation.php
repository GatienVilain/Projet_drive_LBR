<?php

$info = "";

session_start();

if ( !empty($_POST['email'] ) && (isset($_POST['email'] )) )
{
    $headers  = "Content-Type: text/plain; charset=utf-8\r\n";
    $subject  = "Code de recupération de mot de passe";
    $headers .= "From: totolvroum@gmail.com\r\n";
    $mail     = $_POST['email'];

    $message  = "Voici votre code pour modifier votre mot de passe : ";

    for ( $i=0; $i<9; $i++) {
        $code = mt_rand(0,9);
    }

    $message .= $code;

    if ( mail($mail,$subject,$message,$headers) ) {
        $info = "code envoyé";

        $_SESSION['email']=$mail;
        $_SESSION['code']=$code;

        header('Location: index.php?page=verification_code');
        exit();
    }
    else {
        $info = "Erreur serveur : le mail de récupération n’a pas pu être envoié";
    }

}