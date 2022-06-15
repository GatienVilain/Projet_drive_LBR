<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class AddNewTag
{
    public function execute()
    {
        $response = array('status'=>false);
        $connect = new DatabaseConnection();
        $tagName = $_GET['tag'];
        $selectedCategory = $_GET['category'];
        
        $result = $connect->add_tag($tagName);

        if( $result == 0 ) {
            $response['status'] = true;
        }

        echo json_encode($response);

    
    }

}




?>