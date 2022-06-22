<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class PreviousPage
{
    public function execute()
    {
        $response = array('status'=>false);
		//decrease pagination
		if (isset($_SESSION['page'])) {
			$_SESSION['page'] = $_SESSION['page'] - 1;
			
			if($_SESSION['page'] < 0) { $_SESSION['page'] = 0; }
			$response = array('status'=>true);
		}
        echo json_encode($response);
    }
}
?>