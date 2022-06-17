<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class SortMaj
{
    public function execute()
    {
        
        $tagIdList = explode(" ", $_GET['tags']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($tagIdList); //Supprime le dernier élément du tableau (espace)
        $_SESSION['tagIdList'] = $tagIdList; //Variable de session pour contenir les tags sélectionnés pour tri


        $extensionList = explode(" ", $_GET['extensions']);
        array_pop($extensionList);
        $_SESSION['extensionList'] = $extensionList;//Variable de session pour contenir les extensions sélectionnées pour tri

        $response = array('status'=>true);//Renvoie que tout s'est bien passé
        echo json_encode($response);

    
    }

}




?>