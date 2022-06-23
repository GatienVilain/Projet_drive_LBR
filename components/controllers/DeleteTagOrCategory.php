<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class DeleteTagOrCategory
{
    public function execute()
    {
        $response['status'] = false;
        $option = $_GET['option'];
        if(isset($_GET['option']))
        {
            $connect = new DatabaseConnection();
            $user = $_SESSION['email'];
            $userRole = $connect->get_user($user)['role'];
            if($userRole != -1)
            {
                if($option == 'deleteTag' && isset($_GET['idTag']))
                {
                    $idTag = $_GET['idTag'];
                    //On vérifie que la conversion en int se soit bien passé 
                    //et que l'utilisateur ait bien le droit d'écriture sur le tag à supprimer
                        if(($userRole == 'invite' && $connect->get_rights($user, $idTag)['ecriture'] == 1) || ($userRole == 'admin'))
                        {   
                            $response['status'] = true;
                            $result = $connect->delete_tag($idTag);      
                            if($result == -1)
                            {
                                $response['status'] = false;
                            }
                        }        
                }
                elseif($option == 'deleteCategory' && isset($_GET['categoryName']) && $userRole == 'admin')
                {
                    $categoryName = $_GET['categoryName'];
                    $response['status'] = true;
                    $result = $connect->delete_tag_category($categoryName);
                    if($result == -1)
                    {
                        $response['status'] = false;
                    }
                }
            }
            
        }
        echo json_encode($response);
    }
}
