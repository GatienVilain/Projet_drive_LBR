<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class EditTagOrCategory
{
    public function execute()
    {
        $response = array('status'=>false);
        $connect = new DatabaseConnection();
        $option = $_GET['option'];

        if($option == 'editTag')
        {
            if(isset($_GET['category']) && isset($_GET['newName']) && isset($_GET['idTag']))
            {
                $idTag = $_GET['idTag'];
                $newName = $_GET['newName'];
                $categoryName = $_GET['category'];
                if(gettype($idTag) == 'string' && gettype($newName) == 'string' && gettype($categoryName) == 'string')
                {
                    //$result = $connect->delete_tag($idTag);
                    $result=0;
                    if($result == 0)
                    {
                        $response['status'] = true;
                    }

                    else
                    {
                        
                    }
                }

                else
                {

                }
            }

            else
            {

            }
            
        }

        elseif($option == 'editCategory')
        {
            if(isset($_GET['categoryName']) && isset($_GET['newName']))
            {
                $categoryName = $_GET['categoryName'];
                $newName = $_GET['newName'];
                if(gettype($categoryName) == 'string' && gettype($newName) == 'string')
                {
                    //$result = $connect->delete_tag_category($categoryName);
                    $result = 0;
                    if($result == 0)
                    {
                        $response['status'] = true;
                    }

                    else
                    {

                    }
                }

                else
                {

                }
            }

            else
            {

            }
        
        }


        else
        {
            echo json_encode($response);
        }

        echo json_encode($response);
    
    }

}
