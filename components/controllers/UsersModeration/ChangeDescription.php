<?php

namespace Application\Controllers\UsersModeration;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class ChangeDescription
{
    private string $email;

    function __construct()
    {
        $this->email = $_GET['for'];
    }
    public function execute()
    {
        try
        {
            if ( isset($_POST['description']) && $_POST['description'] !== '' )
            {
                $change = array("descriptif" => $_POST['description']);

                // Ce connecte à la base de donnée et change la description
                ( new DatabaseConnection() )->update_user($this->email, $change);
            }
            else {
                throw new \Exception('Description manquante');
            }
        }
        catch (\Exception $e){ }

        header("Location: index.php?action=editRights&for=" . $this->email);
    }
}