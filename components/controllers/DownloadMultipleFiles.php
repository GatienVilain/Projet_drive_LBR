<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
use Application\Tools\Database\DatabaseConnection;

class DownloadMultipleFiles
{
    public function execute()
    {
        $response['status'] = true;
        
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
        $zipName = rand().'.zip';
        $res = $zip->open("storage\\tmp\\".$zipName, \ZipArchive::CREATE);
        
        foreach($idFiles as $id)
        {
            $fileObject = $connection->get_file(intval($id));
            $fileExtension=$fileObject['extension'];
            $fileName = $fileObject['nom_fichier'].'-('.rand().')'.'.'.$fileExtension;
            $filePath = $fileObject["source"] . '\\' . strval($id). '.' . $fileExtension;
            $zip->addFile($filePath,$fileName);
        }
        
        $zip->close();
        $response['zipName'] = $zipName;
        echo json_encode($response);
        

    
    }

}




?>