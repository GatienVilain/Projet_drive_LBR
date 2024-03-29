<?php

namespace Application\Controllers\Rights;

require_once("components/Tools/Database/DatabaseConnection.php");

use Application\Tools\Database\DatabaseConnection;

class GetRights
{
    private string $email ;

    public function __construct()
	{
		$this->email =  $_GET['for'];
	}

    public function execute()
    {
        $connection = new DatabaseConnection();

        $informations = $connection->get_user($this->email);
        $categories = $connection->get_tag_category();

        $name = $informations['prenom'] . " " . $informations['nom'];
        $role = $informations['role'];
        $description = $informations['descriptif'];
        $registration_date = date("d/m/Y",strtotime($informations['date_inscription']));
        $email = $this->email;

        $preview_array_category = $categories;
        $preview_array_tag = $this->getAllRights($connection, $categories);
        $table = $this->getRightsOfUser($connection, $categories);

        $error = "";
        require('public/view/rights.php');
    }

    private function getRightsOfUser(DatabaseConnection $connection, array $categories)
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

    private function getAllRights(DatabaseConnection $connection, array $categories)
    {
        $table = array();
        foreach ($categories as $value)
        {
            $key = $value["nom_categorie_tag"];

            $result = $connection->get_tag_by_category($key);
            if ($result != -1)
            {
                $table[$key] = $result;

                for ($i = 0; $i < count($table[$key]);$i++)
                {
                    $cell = $table[$key][$i];
                    $cell["nom_tag"] = $connection->get_tag($cell["id_tag"])["nom_tag"];
                    $table[$key][$i] = $cell;
                }
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