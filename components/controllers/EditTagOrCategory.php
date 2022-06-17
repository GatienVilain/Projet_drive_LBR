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
                $previousCategoryName = $connect->get_tag_category($idTag);
                
                if(gettype($idTag) == 'string' && gettype($newName) == 'string' && gettype($categoryName) == 'string')
                {
                    if($previousCategoryName != $categoryName)
                    {
                        $arrayEdit = array("nom_tag" => $newName,"nom_categorie_tag" => $categoryName);
                        
                        $result = $connect->modify_tag($idTag, $arrayEdit);
                        //$result=0;
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
                        $arrayEdit = array("nom_tag" => $newName);
                        var_dump($arrayEdit);
                        $result = $connect->modify_tag($idTag, $arrayEdit);
                        //$result=0;
                        if($result == 0)
                        {
                            $response['status'] = true;
                        }

                        else
                        {
                            
                        }
                    }
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
                    $result = $connect->modify_tag_category_name($categoryName,$newName);
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
