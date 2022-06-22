<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class RenameFile
{
    public function execute()
    {
        var_dump($_GET['idFile']);
        var_dump($_GET['new_name']);
        if ( isset($_GET['idFile']) && isset($_GET['new_name']) )
        {
            (new DatabaseConnection)->modify_filename($_GET['idFile'], $_GET['new_name']);

            $response = array('status'=>true);
        }
        else {
            $response = array('status'=>false);
        }
        echo json_encode($response);
    }
}