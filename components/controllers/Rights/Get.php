<?php

namespace Application\Controllers\Rights;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetRights
{
    private string $email ;

    public function __construct()
	{
		$this->email = str_replace('_','.',array_keys($_POST)[0]);
	}

    public function execute()
    {
        $connection = new DatabaseConnection();

        $informations = $connection->get_user($this->email);

        $name = $informations['prenom'] . " " . $informations['nom'];
        $role = $informations['role'];
        $description = $informations['descriptif'];
        $registration_date = $informations['date_inscription'];

        $preview_array_category = $connection->get_tag_category();
        $table = $this->getRights($connection, $preview_array_category);

        $email = $this->email;

        $error = "";
        require('public/view/rights.php');
    }

    private function getRights(DatabaseConnection $connection, array $categories)
    {
        $table = array();
        foreach ($categories as $value)
        {
            $key = $value["nom_categorie_tag"];

            $table[$key] = [];

        }

        $rights_of_user = $connection->get_rights_of_user($this->email);

        if ( $rights_of_user != -1 )
        {
            foreach ($rights_of_user as $value)
            {
                $key = $connection->get_tag_category($value["id_tag"]);
                $value["nom_tag"] = $connection->get_tag($value["id_tag"])["nom_tag"];

                $table[$key[0]["nom_categorie_tag"]][] = $value;
            }
        }

        return $table;
    }

    private function prepareListTags(array $table)
    {
        $list_tags = array();
        foreach ($table as $categorie)
        {
            if (!empty($categorie))
            {
                foreach ($categorie as $tag)
                {
                    $list_tags[] = $tag;
                }
            }
        }

        return $list_tags;
    }


}