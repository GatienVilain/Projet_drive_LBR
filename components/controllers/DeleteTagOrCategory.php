<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");
require_once("components/Model/Log.php");

use Application\Tools\Database\DatabaseConnection;
use Application\Model\Log;


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
                        $tag_name = $connect->get_tag($idTag)['nom_tag'];
                        $categoryName = $connect->get_tag_category($idTag)[0]['nom_categorie_tag'];

                        $response['status'] = true;
                        $result = $connect->delete_tag($idTag); //On supprime le tag de la base de données
                        if($result == -1)
                        {
                            $response['status'] = false;
                        }
                        else {
                            //On écrit un log
                            $message = 'a supprimé le tag "' . $tag_name . '" de la categorie "' . $categoryName . '"';
                            ( new Log() )->ecrire_log($_SESSION['email'], $message);
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
                    else {
                        //On écrit un log
                        $message = 'a supprimé la categorie "' . $categoryName . '"';
                        ( new Log() )->ecrire_log($_SESSION['email'], $message);
                    }
                }
            } 
        }
        echo json_encode($response);
    }
}
