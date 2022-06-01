<?php // Routeur

require_once("components/tools/utilisateur.php");
require_once("components/controllers/login.php");

$utilisateur_courant = new utilisateur();

try
{
    if ( !$utilisateur_courant->est_connecte() ) {
        login();
    }
    else {
        echo "tu est co";
    }
}
catch (Exception $e)
{
    echo "Erreur serveur";
}