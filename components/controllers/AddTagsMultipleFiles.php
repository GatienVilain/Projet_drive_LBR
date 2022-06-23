<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class AddTagsMultipleFiles
{
    public function execute()
    {
        $connection = new DatabaseConnection();
        $arrayIdFiles = explode(" ", $_GET['files']); // On transforme string contenant les idFiles en tableau (en supprimant les espace)
        array_pop($arrayIdFiles); //Supprime le dernier élément du tableau (espace)
        $arrayIdTags = explode(" ", $_GET['tags']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($arrayIdTags); //Supprime le dernier élément du tableau (espace)
        $response = array('status'=>true);
        $user = $_SESSION['email'];    
        if($connection->get_user($user)['role'] == 'invite')
        {
            foreach($arrayIdFiles as $idFile)
            {
                $idFile = intval($idFile);
                if($connection->get_file($idFile)['email'] == $user)
                {
                    foreach($arrayIdTags as $idTag)
                    {
                        $idTag = intval($idTag);
                        $result = $connection->add_link($idFile,$idTag);
                        if($result == -1)
                        {
                            $response = array('status'=>false);
                        }
                    }
                }
                else
                {
                    $allIdLinkToFile = $connection->get_link($idFile);
                    $index = 0;
                    $writtingRight = false;
                    while($writtingRight == false &&  $index < count($allIdLinkToFile))
                    {
                        $userWritingRights = $connection->get_rights($user, $allIdLinkToFile[$index]['id_tag'])['ecriture'];
                        if($userWritingRights == 1)
                        {
                            $writtingRight == true;
                            foreach($arrayIdTags as $idTag)
                            {
                                $idTag = intval($idTag);
                                $result=$connection->add_link($idFile,$idTag);
                                if($result == -1)
                                {
                                    $response = array('status'=>false);
                                }
                            }
                        }  
                        $index++;
                    }
                }
                if(count($connection->get_link($idFile))>1)
                {
                    $connection->delete_link($idFile, 1);
                }
            }
        }
        else
        {
            foreach($arrayIdFiles as $idFile)
            {
                $idFile = intval($idFile);
                foreach($arrayIdTags as $idTag)
                {
                    $idTag = intval($idTag);
                    $result=$connection->add_link($idFile,$idTag);
                    if($result == -1)
                    {
                        $response = array('status'=>false);
                    }
                }
                if(count($connection->get_link($idFile))>1)
                {
                    $connection->delete_link($idFile, 1);
                }
            }
        }   
        echo json_encode($response);
    }
}
