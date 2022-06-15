<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class AddNewCategory
{
    public function execute()
    {
        $response = array('status'=>false);
        $connect = new DatabaseConnection();
        $categoryName = $_GET['category'];
        
        $result = $connect->add_tag_category($categoryName);

        if( $result == 0 ) {
            $response['status'] = true;
        }

        echo json_encode($response);

    
    }

}




?>