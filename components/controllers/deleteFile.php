<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class deleteFile
{
    public function execute()
    {
        // First Check if file exists
        (new DatabaseConnection())->basket_file($_GET['idFile']);

    
    }

}




?>