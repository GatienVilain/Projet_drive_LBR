<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class DeleteTagOrCategory
{
    public function execute()
    {
        $response = array('status'=>false);
        $connect = new DatabaseConnection();
        $option = $_GET['option'];

        if($option == 'deleteTag')
        {
            if(isset($_GET['idTag']))
            {
                $idTag = $_GET['idTag'];
                if(gettype($idTag) == 'string')
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

        elseif($option == 'deleteCategory')
        {
            if(isset($_GET['categoryName']))
            {
                $categoryName = $_GET['categoryName'];
                if(gettype($categoryName) == 'string')
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
