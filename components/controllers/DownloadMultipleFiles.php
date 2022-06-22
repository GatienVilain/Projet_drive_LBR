<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
use Application\Tools\Database\DatabaseConnection;

class DownloadMultipleFiles
{
    public function execute()
    {
        
        
        $connection = new DatabaseConnection();
        $idFiles = explode(" ", $_GET['files']);
        array_pop($idFiles);
        $files = array();
        $zip = new \ZipArchive;
        if(!is_dir("storage\\tmp\\".$_SESSION['email']))
        {
            mkdir("storage\\tmp\\".$_SESSION['email']);
        }


        
        $zipName = "fichiers.zip";
        $zipPath = "storage\\tmp\\".$_SESSION['email']."\\".$zipName; 
        if(is_file($zipPath))
        {
            unlink($zipPath);
        }
        $res = $zip->open($zipPath, \ZipArchive::CREATE);
        
        foreach($idFiles as $id)
        {
            $fileObject = $connection->get_file(intval($id));
            $fileExtension=$fileObject['extension'];
            $filePath = $fileObject["source"] . '\\' . $id . '.' . $fileExtension;
            array_push($files, $filePath);
            
            $fileName = $fileObject['nom_fichier'].'-('.rand().')'.'.'.$fileExtension;
            $filePath = $fileObject["source"] . '\\' . strval($id). '.' . $fileExtension;
            $zip->addFile($filePath,$fileName);
        }
        
        $zip->close();
        $response['status'] = true;
        $response['zipPath'] = $zipPath;
        
        echo json_encode($response);

    
    }

}




?>