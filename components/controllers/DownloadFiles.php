<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class DownloadFiles
{
    public function execute()
    {
        //On récupère les id des fichiers à télécharger et on les stocke dans un tableau
        $response['status'] = false;
        $idFiles = explode(" ", $_GET['files']);
        array_pop($idFiles);
        //Si l'utilisateur veut télécharger plusieurs fichiers
        if(count($idFiles) > 1)
        {
            $connection = new DatabaseConnection();
            $zip = new \ZipArchive;
            $dirPath="storage\\tmp\\".$_SESSION['email'];
            //Si le dossier qui va contenir le zip côté serveur n'existe pas, on le crée
            if(!is_dir($dirPath))
            {
                mkdir($dirPath);
            }            
            $zipName = "fichiers.zip";
            $zipPath = $dirPath."\\".$zipName;
            //Si un zip existe déjà à cet emplacement, on le supprime
            //L'utilisateur ne peut télécharger qu'un ensemble de fichiers à la fois 
            if(is_file($zipPath))
            {
                unlink($zipPath);
            }
            //On crée le zip
            $res = $zip->open($zipPath, \ZipArchive::CREATE); 
            //Si la création s'est bien passée, on ajoute tous les fichiers sélectionnés dedans
            if($res == true)
            {
                //On va sélectionner tous les fichiers sélectionnés pour les placer dans le zip
                foreach($idFiles as $id)
                {
                    $fileObject = $connection->get_file(intval($id));//On récupère l'objet correspondant au fichier sélectionné.
                    $fileExtension=$fileObject['extension'];      
                    $fileName = $fileObject['nom_fichier'].'-('.rand().')'.'.'.$fileExtension;
                    $filePath = $fileObject["source"] . '\\' . strval($id). '.' . $fileExtension;
                    $zip->addFile($filePath,$fileName);//On place le fichier dans le zip
                }  
                //On ferme le zip
                $zip->close();
                $response['status'] = true;
                $response['zipPath'] = $zipPath;//On renvoie le chemin du zip pour le téléchargement
                $response['mode'] = 'multiple';//On indique qu'il s'agit d'un téléchargement de plusieurs fichiers
            }      
        }
        //Si l'utilisateur veut télécharger qu'un fichier
        else if(count($idFiles) == 1)
        {
            $connection = new DatabaseConnection();
            //On récupère l'objet correspondant au fichier sélectionné.
            $fileObject = $connection->get_file(intval($idFiles[0]));
            if($fileObject == -1)
            {
                $response['status'] = false;
            }
            else
            {
                $response['status'] = true;
                $response['mode'] = 'unique';//On indique qu'il s'agit du téléchargement d'un fichier
                $response['filePath'] = $fileObject["source"] . '\\' . $idFiles[0] . '.' . $fileObject['extension'];
                $response['fileName'] = $fileObject['nom_fichier'].'.'.$fileObject['extension'];
            }     
        }       
        //On renvoie des informations côté client
        echo json_encode($response);   
    }
}