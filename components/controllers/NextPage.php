<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class NextPage
{
    public function execute()
    {
        $response = array('status'=>false);
		//increase pagination
		if (isset($_SESSION['page']) && isset($_SESSION['max_page'])) {
			$_SESSION['page'] = $_SESSION['page'] + 1;
			
			if($_SESSION['page'] > $_SESSION['max_page']) { $_SESSION['page'] = $_SESSION['max_page']; }
			$response = array('status'=>true);
		}
        echo json_encode($response);
    }
}
?>