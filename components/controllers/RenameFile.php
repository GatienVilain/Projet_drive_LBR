<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Log.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Model\Log;

class RenameFile
{
    public function execute()
    {
        if ( isset($_GET['idFile']) && isset($_GET['new_name']) )
        {
            $connection = new DatabaseConnection;
            $idFile = $_GET['idFile'];

            $file = $connection->get_file($idFile);
            $oldfilename = $file['nom_fichier'];
            $file_extension = $file['extension'];

            $res = $connection->modify_filename($idFile, $_GET['new_name']);
            if($res != -1)
            {
                $response = array('status'=>true);
                $connection->modify_file_date($idFile);

                $txt = 'a renommé le fichier "'. $oldfilename . '.' . $file_extension . '" en "' . $_GET['new_name'] . '.' . $file_extension . '"';
                ( new Log() )->ecrire_log($_SESSION['email'], $txt);
            }
            else
            {
                $response = array('status'=>false);
            }
        }
        else {
            $response = array('status'=>false);
        }
        echo json_encode($response);
    }
}