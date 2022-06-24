<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Log.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Model\Log;

class CreateTag
{
    public function execute()
    {      
        $response['status'] = false;
        //On vérifie qu'une valeur pour tag et category a bien été envoyée
        if(isset($_GET['tag']) && isset($_GET['category']))
        {
            $connect = new DatabaseConnection();
            $tagName = $_GET['tag'];
            $selectedCategory = $_GET['category'];
            $response['status'] = true;
            //On ajoute le tag à la base de données en l'associant à la catégorie sélectionnée
            $result = $connect->add_tag($tagName, $selectedCategory);
            if( $result == -1 ) {
                $response['status'] = false; //En cas d'erreur lors de la création du tag on renvoie false au client
            }
            else {
                $message = 'a créé le tag "' . $tagName . '" dans la categorie "' . $selectedCategory. '"';
                ( new Log() )->ecrire_log($_SESSION['email'], $message);
            }
        }
        echo json_encode($response);   
    }
}