<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetFilesSize
{
    public function execute()
    {  
        $response['status'] = false;
        $arrayIdFiles = explode(" ", $_GET['files']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
        array_pop($arrayIdFiles); //Supprime le dernier élément du tableau (espace)
        //On vérifie que l'utilisateur a bien sélectionné un fichier
        if(count($arrayIdFiles) > 0)
        {
            $totalSize = 0;
            $connection = new DatabaseConnection();
            //On parcourt tous les fichiers sélectionnés pour récupérer leur taille et l'additionner 
            for($i=0; $i<count($arrayIdFiles);$i++)
            {
                $response['status'] = true;
                //On récupère la taille du fichier
                $sizeFile=$connection->get_file(intval($arrayIdFiles[$i]))["taille_Mo"];
                if($sizeFile != -1)
                {
                    $totalSize = $totalSize+$sizeFile;
                }
                else
                {
                    //Erreur lors de la récupération de la taille
                    $response['status'] = false;
                }
            }
            //On stocke la taille des fichiers, en arrondissant le résultat avec 2 chiffre après la virgule
            $response['size']=round($totalSize,2); 
        }
        //On renvoie la taille des fichiers et si la fonction a été un succès ou un échec
        echo json_encode($response);
    }
}