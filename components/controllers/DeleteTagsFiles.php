<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class DeleteTagsFiles
{
    public function execute()
    {
        $response['status'] = false;
        $arrayIdFiles = explode(" ", $_GET['files']); // On transforme string contenant les idFiles en tableau (en supprimant les espace)
        array_pop($arrayIdFiles); //Supprime le dernier élément du tableau (espace)
        $arrayIdTags = explode(" ", $_GET['tags']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($arrayIdTags); //Supprime le dernier élément du tableau (espace)
        //On vérifie que l'utilisateur a sélectionné au moins un fichier et au moins un tag à supprimer
        if(count($arrayIdFiles) > 0 && count($arrayIdTags) > 0)
        {
            $user = $_SESSION['email'];
            $connection = new DatabaseConnection();
            $role = $connection->get_user($user)['role'];//On récupère le rôle de l'utilisateur
            if($role != -1)
            {
                //Si l'utilisateur est un invité
                if($role == 'invite')
                {
                    //On parcourt tous les fichiers sélectionnés par l'utilisateur
                    foreach($arrayIdFiles as $idFile)
                    {
                        $idFile = intval($idFile);
                        //Si l'utilisateur est le propriétaire du fichier, il peut supprimer tous les tags associés
                        if($connection->get_file($idFile)['email'] == $user)
                        {
                            $response['status'] = true;
                            //On parcourt tous les tags sélectionnés par l'utilisateur
                            foreach($arrayIdTags as $idTag)
                            {
                                $idTag = intval($idTag);
                                $connection->delete_link($idFile,$idTag);//On supprime le tag d'id 'idTag)
                            }
                            //Si le fichier n'est plus associé à un tag après les suppressions
                            //on lui associe le tag 'sans tags'
                            if(empty($connection->get_link($idFile)))
                            {
                                $result = $connection->add_link($idFile, 1);//On associe le fichier au tag 'sans tags'
                                if($result == -1)
                                {
                                    $response['status'] = false;
                                }
                            }
                        }     
                    }
                }
                //Si l'utilisateur est un administrateur il peut supprimer tous les tags qu'il veut sur les fichiers
                elseif($role == 'admin')
                {
                    $response['status'] = true;
                    foreach($arrayIdFiles as $idFile)
                    {
                        $idFile = intval($idFile);
                        foreach($arrayIdTags as $idTag)
                        {
                            $idTag = intval($idTag);
                            $connection->delete_link($idFile,$idTag);
                        }
                        if(empty($connection->get_link($idFile)))
                        {
                            $result = $connection->add_link($idFile, 1);
                            if($result == -1)
                            {
                                $response['status'] = false;
                            }              
                        }
                    }
                }
            }            
        }     
        //On envoie si la suppression est un succès ou un échec
        echo json_encode($response);
    }
}