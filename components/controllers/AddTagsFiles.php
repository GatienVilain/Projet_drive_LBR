<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class AddTagsFiles
{
    public function execute()
    {
        $arrayIdFiles = explode(" ", $_GET['files']); // On transforme string contenant les idFiles en tableau (en supprimant les espace)
        array_pop($arrayIdFiles); //Supprime le dernier élément du tableau (espace)
        $arrayIdTags = explode(" ", $_GET['tags']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($arrayIdTags); //Supprime le dernier élément du tableau (espace)
        $response['status'] = false;
        if(count($arrayIdFiles) > 0 && count($arrayIdTags) > 0)
        {
            $user = $_SESSION['email'];
            $connection = new DatabaseConnection();
            $userRole = $connection->get_user($user)['role'];
            //Si l'utilisateur est un invité, il doit posséder le fichier ou avoir un tag avec le droit d'écriture
            //associé à celui-ci pour ajouter des tags
            if($userRole == 'invite')
            {
                $response['status'] = true;
                //On parcourt tous les fichiers sélectionnés
                foreach($arrayIdFiles as $idFile)
                {
                    $idFile = intval($idFile);
                    //Si le fichier appartient à l'utilisateur, il peut lui associer des tags
                    if($connection->get_file($idFile)['email'] == $user)
                    {
                        //On ajoute tous les tags sélectionnés par l'invité
                        foreach($arrayIdTags as $idTag)
                        {
                            $idTag = intval($idTag);
                            $result = $connection->add_link($idFile,$idTag);//On associe un tag au fichier
                            if($result == -1)
                            {
                                $response['status'] = false;
                            }
                        }
                    }
                    //Si l'utilisateur ne possède pas le fichier, il doit avoir un tag avec le droit d'écriture
                    //associé à la vidéo pour pouvoir ajouter des tags
                    else
                    {
                        //On récupère tous les tags associés au fichier
                        $IdTagsLinkToFile = $connection->get_link($idFile);
                        $index = 0;
                        $writtingRight = false;
                        //On vérifie si l'invité possède un tag avec le droit d'écriture qui est associé au fichier
                        while($writtingRight == false &&  $index < count($IdTagsLinkToFile))
                        {
                            $userWritingRights = $connection->get_rights($user, $IdTagsLinkToFile[$index]['id_tag'])['ecriture'];
                            //Si l'invité a les droits d'écritures sur le fichier
                            if($userWritingRights == 1)
                            {
                                $writtingRight == true;
                                foreach($arrayIdTags as $idTag)
                                {
                                    $idTag = intval($idTag);
                                    $result=$connection->add_link($idFile,$idTag);//On associe le tag au fichier
                                    if($result == -1)
                                    {
                                        $response['status'] = false;
                                    }
                                }
                                //On modifie la date de dernière modification
                                $connection->modify_file_date($idFile);
                            }  
                            $index++;
                        }
                    }
                    //Si le fichier possède plus d'un tag, on supprime le tag 'sans-tags'
                    if(count($connection->get_link($idFile))>1)
                    {
                        $connection->delete_link($idFile, 1);
                    }
                }
            }
            //Si l'utilisateur est un administrateur, il peut ajouter des tags à tous les fichiers
            else if ($userRole == 'admin')
            {
                $response['status'] = true;
                foreach($arrayIdFiles as $idFile)
                {
                    $idFile = intval($idFile);
                    foreach($arrayIdTags as $idTag)
                    {
                        $idTag = intval($idTag);
                        $result=$connection->add_link($idFile,$idTag);
                        if($result == -1)
                        {
                            $response['status'] = false;
                        }
                    }
                    //On modifie la date de dernière modification
                    $connection->modify_file_date($idFile);
                    if(count($connection->get_link($idFile))>1)
                    {
                        $connection->delete_link($idFile, 1);
                    }
                }
            }
        }       
        echo json_encode($response);
    }
}
