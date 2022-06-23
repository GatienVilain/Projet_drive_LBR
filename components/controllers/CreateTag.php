<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class CreateTag
{
    public function execute()
    {
        $connect = new DatabaseConnection();
        $response = array('status'=>false);
        $tagName = $_GET['tag'];
        $selectedCategory = $_GET['category'];
        $role = $connect->get_user($_SESSION['email'])['role'];
        //var_dump($selectedCategory);
        


        $result = $connect->add_tag($tagName, $selectedCategory);
        if( $result == 0 ) {
            $response['status'] = true;
        }

        echo json_encode($response);

    
    }

}




?>