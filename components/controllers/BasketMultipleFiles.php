<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class BasketMultipleFiles
{
    public function execute()
    {
        $response = array('status'=>false);
        $connection = new DatabaseConnection();
        $arrayIdFiles = explode(" ", $_GET['idFiles']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($arrayIdFiles); //Supprime le dernier élément du tableau (espace)
        $user = $_SESSION['email'];
        $userRole = $connection->get_user($user)['role'];
        if($userRole == 'invite')
        {
            foreach($arrayIdFiles as $idFile)
            {
                $idFile = intval($idFile);
                if($connection->get_file($idFile)['email'] == $user)
                {
                    $response = array('status'=>true);
                    $result = $connection->basket_file($idFile); //met le fichier dans la corbeille
                    if($result == -1)
                    {
                        $response = array('status'=>false);
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
                            $response = array('status'=>true);
                            $writtingRight == true;
                            $result=$connection->basket_file($idFile); //met le fichier dans la corbeille
                            if($result == -1)
                            {
                                $response = array('status'=>false);
                            }
                        }  
                        $index++;
                    }
                }
            }
        }
        else if($userRole == 'admin')
        {
            $response = array('status'=>true);
            foreach($arrayIdFiles as $idFile)
            {
                $idFile = intval($idFile);
                $result=$connection->basket_file($idFile);
                if($result == -1)
                {
                    $response = array('status'=>false);
                }
            }
        }
        
        //Renvoie que tout s'est bien passé
        echo json_encode($response);

    
    }

}




?>