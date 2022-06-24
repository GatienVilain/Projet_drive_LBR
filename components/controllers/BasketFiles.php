<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Log.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Model\Log;

class BasketFiles
{
    public function execute()
    {
        $response['status'] = false;
        $arrayIdFiles = explode(" ", $_GET['idFiles']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($arrayIdFiles); //Supprime le dernier élément du tableau (espace)
        //On vérifie que l'utilisateur a sélectionné au moins un fichier
        if(count($arrayIdFiles) > 0)
        {
            $user = $_SESSION['email'];
            $connection = new DatabaseConnection();
            $userRole = $connection->get_user($user)['role'];
            //Si l'utilisateur est un invité, le fichier doit soit lui appartenir
            //Soit il doit avoir un droit d'écriture sur un tag associé au fichier
            if($userRole == 'invite')
            {
                //On parcourt tous les fichiers sélectionnés par l'utilisateur
                foreach($arrayIdFiles as $idFile)
                {
                    $idFile = intval($idFile);
                    $file = $connection->get_file($idFile);
                    //Si le fichier appartient à l'utilisateur, il peut le mettre dans la corbeille
                    if($file['email'] == $user)
                    {
                        $response['status'] = true;
                        $result = $connection->basket_file($idFile); //met le fichier dans la corbeille
                        if($result == -1)
                        {
                            $response['status'] = false;
                        }
                        else {
                            $txt = 'a mis "'. $file['nom_fichier'] . '.' . $file['extension'] . '" à la corbeille';
                            ( new Log() )->ecrire_log($_SESSION['email'], $txt);
                        }
                    }
                    else
                    {
                        $IdTagsLinkToFile = $connection->get_link($idFile);//On récupère les tags associé au fichier
                        $index = 0;
                        $writtingRight = false;
                        //On vérifie si l'invité possède un tag avec le droit d'écriture qui est associé au fichier
                        while($writtingRight == false &&  $index < count($IdTagsLinkToFile))
                        {
                            $userWritingRight = $connection->get_rights($user, $IdTagsLinkToFile[$index]['id_tag'])['ecriture'];
                            //Si l'invité a les droits d'écritures sur le fichier
                            if($userWritingRight == 1)
                            {
                                $response['status'] = true;
                                $writtingRight == true;
                                $result=$connection->basket_file($idFile); //On met le fichier dans la corbeille
                                if($result == -1)
                                {
                                    $response['status'] = false;
                                }
                                else {
                                    $txt = 'a mis "'. $file['nom_fichier'] . '.' . $file['extension'] . '" à la corbeille';
                                    ( new Log() )->ecrire_log($_SESSION['email'], $txt);
                                }
                            }  
                            $index++;
                        }
                    }
                }
            }
            //Si l'utilisateur est un admin, il peut mettre tous les fichiers qu'il veut dans la corbeille
            else if($userRole == 'admin')
            {
                $response['status'] = true;
                foreach($arrayIdFiles as $idFile)
                {
                    $idFile = intval($idFile);
                    $file = $connection->get_file($idFile);
                    $result=$connection->basket_file($idFile);//On met le fichier dans la corbeille
                    if($result == -1)
                    {
                        $response = array('status'=>false);
                    }
                    else {
                        $txt = 'a mis "'. $file['nom_fichier'] . '.' . $file['extension'] . '" à la corbeille';
                        ( new Log() )->ecrire_log($_SESSION['email'], $txt);
                    }
                }
            }
        }     
        //On indique au client si la mise des fichiers dans la corbeille a été un succès ou un échec
        echo json_encode($response);
    }
}