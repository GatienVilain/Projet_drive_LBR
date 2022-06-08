<?php

namespace Application\Controllers\Profil;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetProfil
{
    public function execute()
    {
        $informations = (new DatabaseConnection)->get_user($_SESSION['email']);

        $name = $informations['prenom'] . " " . $informations['nom'];
        $role = $informations['role'];
        $description = $informations['description'];
        $registration_date = $informations['date_inscription'];

        $error = "";
        require('public/view/profil.php');
    }
}
