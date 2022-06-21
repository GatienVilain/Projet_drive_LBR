<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetFilesSize
{
    public function execute()
    {
        $sizeFiles = 0;
        $connection = new DatabaseConnection();
        $arrayIdFiles = explode(" ", $_GET['files']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($arrayIdFiles); //Supprime le dernier élément du tableau (espace)
        $response=$arrayIdFiles;
        for($i=0; $i<count($arrayIdFiles);$i++)
        {
            $result=$connection->get_file(intval($arrayIdFiles[$i]))["taille_Mo"];
            
            if($result != -1)
            {
                $sizeFiles =$sizeFiles+$result;
            }
        }

        $response=round($sizeFiles,2);
        
        //Renvoie que tout s'est bien passé
        echo json_encode($response);

    
    }

}




?>