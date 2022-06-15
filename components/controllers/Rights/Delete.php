<?php

namespace Application\Controllers\Rights;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class DeleteRights
{
    public function execute()
    {
        $email = $_GET['for'];

        foreach ($_POST as $name=>$value)
        {
            if ($name[0] == "e")
            {   // Droit en écriture modifié

                $id_tag = substr($name, 1); // Récupère l’id du tag
                ( new DatabaseConnection() )->modify_rights($email, $id_tag, 0, 1);

            }
            elseif ($name[0] == "l")
            {   // Droit en lecture modifié

                $id_tag = substr($name, 1); // Récupère l’id du tag
                ( new DatabaseConnection() )->modify_rights($email, $id_tag, 0, 0);

            }
        }

        header('Location: index.php?action=usersmoderation');
    }
}