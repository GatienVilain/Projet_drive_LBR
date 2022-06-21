<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class basketFile
{
    public function execute()
    {
        // First Check if file exists
        $response = array('status'=>false);
        $connect = new DatabaseConnection();
        $result = $connect->basket_file((int)$_GET['idFile']);
		$result = $connect->modify_file_date((int)$_GET['idFile']);

        if( $result == 0 ) {
            $response['status'] = true;
        }

        echo json_encode($response);

    
    }

}




?>