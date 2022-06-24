<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class RenameFile
{
    public function execute()
    {
        if ( isset($_GET['idFile']) && isset($_GET['new_name']) )
        {
            $connection = new DatabaseConnection;
            $idFile = $_GET['idFile'];
            $res = $connection->modify_filename($idFile, $_GET['new_name']);
            if($res != -1)
            {
                $response = array('status'=>true);
                $connection->modify_file_date($idFile);
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