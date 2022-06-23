<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class CreateCategory
{
    public function execute()
    {
        $response['status'] = false;  
        //On vérifie qu'une valeur pour category a bien été envoyée  
        if(isset($_GET['category']))
        {
            $categoryName = $_GET['category'];
            $connect = new DatabaseConnection();
            $response['status'] = true;
            //On ajoute la catégorie à la base de données
            $result = $connect->add_tag_category($categoryName);
            //En cas d'erreur lors de la création d'une catégorie
            if($result == -1)
            {
                $response['status'] = false;
            }
        }
        echo json_encode($response);  
    }
}