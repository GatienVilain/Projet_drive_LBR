<?php

namespace Application\Controllers\Rights;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetRights
{
    public function execute()
    {
        $connection = new DatabaseConnection();

        $categories = $connection->get_tag_category();

        $table = array();
        foreach ($categories as $value)
        {
            $key = $value["nom_categorie_tag"];

            $table[$key] = [];

        }

        $rights_of_user = $connection->get_rights_of_user(array_keys($_POST)[0]);

        foreach ($rights_of_user as $value)
        {
            $key = $connection->get_tag_category($value["id_tag"]);
            $table[$key] = $value;
        }

        print_r($table);
    }


}