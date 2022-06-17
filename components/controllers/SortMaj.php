<?php

namespace Application\Controllers;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class SortMaj
{
    public function execute()
    {
        if(isset($_GET['option']))
        {
            $option = $_GET['option'];
            if($option == "sortModificationDate" || $option == "sortAlphabetic")
            {
                $_SESSION['optionSort'] = $option;

                if($option == "sortAlphabetic")
                {

                    unset($_SESSION['sortModificationDate']);

                    if(!isset($_SESSION['alphabeticOrder']))
                    {
                        $_SESSION['alphabeticOrder'] = 'asc';
                    }

                    elseif($_SESSION['alphabeticOrder'] == 'asc')
                    {
                        $_SESSION['alphabeticOrder'] = 'desc';
                    }

                    else
                    {
                        $_SESSION['alphabeticOrder'] = 'asc';
                    }
              
                }

                elseif($option == "sortModificationDate")
                {
                    unset($_SESSION['alphabeticOrder']);

                    if(!isset($_SESSION['modificationDateOrder']))
                    {
                        $_SESSION['modificationDateOrder'] = 'asc';
                    }

                    elseif($_SESSION['modificationDateOrder'] == 'asc')
                    {
                        $_SESSION['modificationDateOrder'] = 'desc';
                    }

                    else
                    {
                        $_SESSION['modificationDateOrder'] = 'asc';
                    }

                }
            
            }

            elseif($option == 'sortFilter'){
                $tagIdList = explode(" ", $_GET['tags']); // On transforme string contenant les idTag en tableau (en supprimant les espace)
                array_pop($tagIdList); //Supprime le dernier élément du tableau (espace)
                $_SESSION['tagIdList'] = $tagIdList; //Variable de session pour contenir les tags sélectionnés pour tri


                $extensionList = explode(" ", $_GET['extensions']);
                array_pop($extensionList);
                $_SESSION['extensionList'] = $extensionList;//Variable de session pour contenir les extensions sélectionnées pour tri
            }

        }

        
        

            
        
       
        $response = array('status'=>true);//Renvoie que tout s'est bien passé
        echo json_encode($response);

    
    }

}




?>