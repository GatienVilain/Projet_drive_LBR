<?php

session_start();

if ( isset($_POST['codeverif']) )
{
    if ( isset($_SESSION['code']) )
    {
        if ( $_POST['codeverif'] == $_SESSION['code'] )
        {
            header('Location: index.php?page=reinitialisation_mdp');
            exit();
        }
        else
        {
            $erreur = "Le code de récupération n’est pas correcte.";
        }
    }
    else
    {
        $erreur = "La session a expiré. Veuillez retenter l’opération.";
        header('Location: index.php?page=recuperation_mdp');
        exit();
    }
}