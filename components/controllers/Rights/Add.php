<?php

namespace Application\Controllers\Rights;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;
use Exception;

class AddRight
{
    public function execute()
    {
        $email = $_GET['for'];

        try {
            if ( $_POST['tag'] != "" )
            {
                if ($_POST['type'] == "ecriture")
                {   // Droit en écriture modifié

                    ( new DatabaseConnection() )->add_writing_right($email, $_POST['tag']);
                }
                elseif ($_POST['type'] == "lecture")
                {   // Droit en lecture modifié
                    // echo 'lecture';
                    ( new DatabaseConnection() )->add_reading_right($email, $_POST['tag']);
                }
            }
            else {
                throw new Exception("Pas de tag selectionné");
            }
        }
        catch (Exception $e){
        }

        header('Location: index.php?action=usersmoderation');
    }
}