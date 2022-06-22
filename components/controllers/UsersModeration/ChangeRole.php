<?php

namespace Application\Controllers\UsersModeration;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class ChangeRole
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
            if ( isset($_POST['role']) && $_POST['role'] !== '' )
            {
                $change = array("role" => $_POST['role']);

                // Ce connecte à la base de donnée et change la description
                ( new DatabaseConnection() )->update_user($this->email, $change);
            }
            else {
                throw new \Exception('Role manquante');
            }
        }
        catch (\Exception $e){ }

        header("Location: index.php?action=editRights&for=" . $this->email);
    }
}