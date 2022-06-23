<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class DeleteTagOrCategory
{
    public function execute()
    {
        $response['status'] = false;   
        if(isset($_GET['option']))
        {
            $option = $_GET['option'];
            $connect = new DatabaseConnection();
            $user = $_SESSION['email'];
            $userRole = $connect->get_user($user)['role']; //On récupère le rôle de l'utilisateur connecté
            if($userRole != -1)
            {
                //L'utilisateur souhaite supprimer un tag, et on vérfie qu'une valeur pour idTag a bien été envoyée
                if($option == 'deleteTag' && isset($_GET['idTag']))
                {
                    $idTag = $_GET['idTag'];
                    //On vérifie que la conversion en int se soit bien passé 
                    //et que l'utilisateur ait bien le droit d'écriture sur le tag à supprimer
                    if(($userRole == 'invite' && $connect->get_rights($user, $idTag)['ecriture'] == 1) || ($userRole == 'admin'))
                    {   
                        $response['status'] = true;
                        $result = $connect->delete_tag($idTag); //On supprime le tag de la base de données     
                        if($result == -1)
                        {
                            $response['status'] = false;
                        }
                    }        
                }
                //L'utilisateur souhaite supprimer une catégorie, on vérifie qu'une valeur pour categoryName a bien été envoyée
                //et qu'il s'agit d'un administrateur
                elseif($option == 'deleteCategory' && $userRole == 'admin' && isset($_GET['categoryName']))
                {
                    $categoryName = $_GET['categoryName'];
                    $response['status'] = true;
                    $result = $connect->delete_tag_category($categoryName);//On supprime la catégorie de la base de données
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
