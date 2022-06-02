<?php // Routeur

require_once("components/controllers/login.php");
require_once("components/controllers/recuperation.php");
require_once("components/controllers/verification_recuperation.php");
require_once("components/controllers/reinitialisation_mdp.php");

require_once("components/tools/utilisateur.php");

$utilisateur_courant = new utilisateur();

try
{
    if ( !$utilisateur_courant->est_connecte() )
    {
        if ( isset($_GET['page']) && $_GET['page'] !== '')
        {
            if ($_GET['page'] === 'recuperation_mdp')
            {
                recuperation_mdp();
            }
            elseif ($_GET['page'] === 'verification_code')
            {
                verification_code();
            }
            elseif ($_GET['page'] === 'reinitialisation_mdp')
            {
                reinitialisation_mdp();
            }
        }
        else
        {
            login();
        }
    }
    else
    {
        echo "tu est co";
    }
}
catch (Exception $e)
{
    echo "Erreur serveur";
}