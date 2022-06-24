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
		
		$page = $_GET['page'];
		$maxpage = 'max_'.$page;
		
		if (isset($_SESSION[$page]) && isset($_SESSION[$maxpage])) {
			$_SESSION[$page] = $_SESSION[$page] + 1;
			if($_SESSION[$page] > $_SESSION[$maxpage]) { $_SESSION[$page] = $_SESSION[$maxpage]; }
			$response = array('status'=>true);
		}
        echo json_encode($response);
    }
}
?>