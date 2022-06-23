<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class ResetPage
{
    public function execute()
    {
        $response = array('status'=>true);
		//reset pagination
		$_SESSION['page'] = 0;
		
        echo json_encode($response);
    }
}
?>