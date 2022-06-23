<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class DeleteFiles
{
    public function execute()
    {
        $response['status'] = false;
        $arrayIdFiles = explode(" ", $_GET['idFiles']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($arrayIdFiles); //Supprime le dernier élément du tableau (espace)
        //On vérifie que l'utilisateur a sélectionné au moins un fichier
        if(count($arrayIdFiles) > 0)
        {
            $connection = new DatabaseConnection();
            $user = $_SESSION['email'];
            $userRole = $connection->get_user($user)['role'];
            //Si l'utilisateur est un admin, il peut supprimer tous les fichiers sélectionnés
            if($userRole == 'admin')
            {
                $response['status'] = true;
                //On parcourt tous les fichiers sélectionnés pour les supprimer
                for($i=0; $i<count($arrayIdFiles);$i++)
                {
                    $result=$connection->delete_file(intval($arrayIdFiles[$i]));//On supprime le fichier
                    if($result == -1)
                    {
                        $response['status'] = false;
                    }
                }
            }
            //Si l'utilisateur est un invité, il faut que le fichier lui appartienne pour qu'il puisse le supprimer
            else if ($userRole=='invite')
            {
                $response['status'] = true;
                //On parcourt tous les fichiers sélectionnés  
                for($i=0; $i<count($arrayIdFiles);$i++)
                {
                    //On vérifie que l'invité est le propriétaire du fichier
                    if($connection->get_file($arrayIdFiles[$i])['email'] == $user)
                    {
                        //On supprime le fichier
                        $result=$connection->delete_file(intval($arrayIdFiles[$i]));
                        //la suppression n'a pas fonctionné
                        if($result == -1)
                        {
                            $response['status'] = false;
                        }
                    }
                }  
            }
        }
        //On indique au client si la suppression des fichiers est un succès ou un échec
        echo json_encode($response);
    }
}