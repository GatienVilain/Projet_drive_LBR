<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class AddTagFile
{
    public function execute()
    {
        $connection = new DatabaseConnection();
        $tagIdList = explode(" ", $_GET['tags']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($tagIdList); //Supprime le dernier élément du tableau (espace)
        $idFile = intval($_GET['idFile']);
        $response = array('status'=>$tagIdList);
        for($i=0; $i<count($tagIdList);$i++)
        {
            $idTag = intval($tagIdList[$i]);
            $result=$connection->add_link($idFile,$idTag);
            if($result == -1)
            {
                $response = array('status'=>false);
            }
            else
            {
                $response = array('status'=>true);
            }
        }
        
        //Renvoie que tout s'est bien passé
        echo json_encode($response);

    
    }

}




?>