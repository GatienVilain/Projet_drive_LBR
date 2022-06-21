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
        foreach($idFiles as $id)
        {
            $fileObject = $connection->get_file(intval($id));
            $fileExtension=$fileObject['extension'];
            $filePath = $fileObject["source"] . '\\' . strval($id_fichier) . '.' . $fileExtension;
            array_push($files, $filePath);
            
        }
        
        $zipname = __DIR__.'/../../storage/TMP/'.$_SESSION['email'].'/fichiers.zip';
        $zip = new \ZipArchive;
        $zip->open($zipname, \ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFile($file);
        }
        $zip->close();

        ///Then download the zipped file.
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);

        echo json_encode($response);

    
    }

}




?>