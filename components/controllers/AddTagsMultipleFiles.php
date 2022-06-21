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
        $response = array('status'=>false);
           
        for($i=0; $i<count($arrayIdTags);$i++)
        {
            $idTag = intval($arrayIdTags[$i]);
            for($j=0; $j<count($arrayIdFiles);$j++){
                $idFile = intval($arrayIdFiles[$j]);
                $result=$connection->add_link($idFile,$idTag);
                if($result != -1)
                {
                    $response = array('status'=>true);
                }
            }
        }

        for($j=0; $j<count($arrayIdFiles);$j++){
            $idFile = intval($arrayIdFiles[$j]);
            $result=$connection->get_link($idFile);
            if(count($result)>1)
            {
                $connection->delete_link($idFile, 1);
            }
        }
        //Renvoie que tout s'est bien passé
        echo json_encode($response);

    
    }

}


?>