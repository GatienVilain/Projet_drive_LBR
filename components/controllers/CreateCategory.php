<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class CreateCategory
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

        else{
            $response['status']=$result;
        }

        echo json_encode($response);

    
    }

}




?>