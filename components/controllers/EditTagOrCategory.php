<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class EditTagOrCategory
{
    public function execute()
    {
        $response['status'] = false;  
        //On vérifie que la valeur pour option a bien été envoyée
        if(isset($_GET['option']))
        {
            $option = $_GET['option'];
            $user = $_SESSION['email'];
            //Si l'utilisateur souhaite modifier un tag
            if($option == 'editTag')
            {
                //On vérifie que les valeurs pour category et newName et idTag ont bien été envoyées
                if(isset($_GET['category']) && isset($_GET['newName']) && isset($_GET['idTag']))
                {
                    $connect = new DatabaseConnection();
                    $userRole = $connect->get_user($user)['role'];
                    //On convertit idTag en integer
                    $idTag = intval($_GET['idTag']);
                    $newName = $_GET['newName'];
                    $categoryName = $_GET['category'];
                    //On stocke le nouveau nom et la nouvelle catégorie dans un tableau
                    $arrayEdit = array("nom_tag" => $newName,"nom_categorie_tag" => $categoryName);
                    //Si l'utilisateur est un admin ou qu'il possède un droit d'écriture sur le tag, alors il peut le modifier
                    if(($userRole == 'admin') || ($userRole == 'invite' && $connect->get_rights($user, $idTag)['ecriture'] == 1))
                    {
                        //On récupère la catégorie auquelle le tag est actuellement associé
                        $previousCategoryName = $connect->get_tag_category($idTag); 
                        //Si l'utilisateur vaut modifier la catégorie dans laquelle se trouve le tag         
                        if($previousCategoryName != $categoryName)
                        {
                            $response['status'] = true;
                            //On modifie le nom du tag et la catégorie qui lui est associée
                            $result = $connect->modify_tag($idTag, $arrayEdit);
                            if($result == -1)
                            {
                                $response['status'] = false;
                            }
                        }
                        else
                        {
                            //On stocke le nouveau nom pour le tag dans un tableau
                            $arrayEdit = array("nom_tag" => $newName);
                            //On modifie le nom du tag
                            $result = $connect->modify_tag($idTag, $arrayEdit);
                            if($result == -1)
                            {
                                $response['status'] = false;
                            }
                        }        
                    }                          
                }         
            }
            //Si l'utilisateur souhaite modifier une catégorie
            elseif($option == 'editCategory')
            {
                //On vérifie que les valeurs pour categoryName et newName ont bien été envoyées
                if(isset($_GET['categoryName']) && isset($_GET['newName']))
                {
                    $connect = new DatabaseConnection();
                    $role = $connect->get_user($user)['role'];
                    //Si l'utilisateur est un admin il peut modifier une catégorie, sinon ce n'est pas possible
                    if($role == 'admin')
                    {
                        $response['status'] = true;
                        $categoryName = $_GET['categoryName'];
                        $newName = $_GET['newName'];
                        //On modifie le nom de la catégorie
                        $result = $connect->modify_tag_category_name($categoryName,$newName);
                        if($result == -1)
                        {
                            $response['status'] = false;
                        }
                    }  
                }   
            }
        }       
        echo json_encode($response);
    }
}