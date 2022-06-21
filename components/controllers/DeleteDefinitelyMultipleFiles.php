<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class DeleteDefinitelyMultipleFiles
{
    public function execute()
    {
        $response = array('status'=>false);
        $connection = new DatabaseConnection();
        $arrayIdFiles = explode(" ", $_GET['idFiles']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($arrayIdFiles); //Supprime le dernier élément du tableau (espace)
        for($i=0; $i<count($arrayIdFiles);$i++)
        {
            $result=$connection->delete_file(intval($arrayIdFiles[$i]));
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